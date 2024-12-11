<?php
class ModelThemesetCompanies extends Model {

	private $b24_hook = 'https://avclub.bitrix24.ru/rest/677/hgv4fvnz8xdrqk2k/';


	public function getCompanyInfo($company_id = 0, $show_info = false, $options = array()) {

		$log_file = 'company_info-' . date('Y-m-d') . '.log';
		$log = new Log($log_file);

		$message = "\n\n\n\n\n";

		//$bitrixWebHook = $this->b24_hook;

        $client_id = $this->config->get('themeset_bitrix_company_client_id');
        $client_secret = $this->config->get('themeset_bitrix_company_client_secret');

		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		/*$result = CRest::call(
			'crm.company.get', 
			[
				"id" 	=> $company_id,
			]
		);*/

		$result = $this->getCompanyFromB24($company_id);

		$message .= "------------------\nRESULT\n------------------\n";
		if(!empty($result['result'])) {
			foreach($result['result'] as $key=>$value) {
				$message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
			}
		}else{
			$message .= json_encode($result, JSON_UNESCAPED_UNICODE);
		}
		$log->write($message);

		if(!empty($show_info)) {
			echo '<pre>';
			print_r($result);
			echo '</pre>';
		}

		if(!isset($result['result']['ID'])) {
			$alert_data = array(
				'alert'				=> 'Данные компании не получены',
				'company_id'	=> $company_id,
				'result'			=> $result,
			);
			$this->alert($alert_data);


			if(!empty($options['show_result'])) {
				$text = '';
				$text .= '<br>' . 'Синхронизация <strong>НЕ УДАЛАСЬ</strong><br>';
				$text .= '<br>' . 'company_id: <strong>' . $company_id . '</strong>';
				echo $text;

				echo '<pre>';
				print_r($result);
				echo '</pre>';
			}

			return;
		}

		// UF_CRM_1639493528 = 3045 -- обновление компании = да

		$sync = 0;
		if(isset($result['result']['UF_CRM_1639493528']) && $result['result']['UF_CRM_1639493528'] == 3045) {
			$sync = 1;
		}

		$flag_archive = isset($result['result']['UF_CRM_1687952516']) && $result['result']['UF_CRM_1687952516'] == 0 ? 0 : 1;

		$info = $result['result'];

		$title = explode('/', $info['TITLE']);
		$name = $title ? trim($title[0]) : '';

		$filter_tags = array(
			'personal'	=> !empty($info['UF_CRM_PERSONAL_TAG_ITEMS_FIELD']) ? array($info['UF_CRM_PERSONAL_TAG_ITEMS_FIELD']) : array(),
			'branch'	=> !empty($info['UF_CRM_1676452514']) ? $info['UF_CRM_1676452514'] : array(),
			'product'	=> !empty($info['UF_CRM_1676472006']) ? $info['UF_CRM_1676472006'] : array(),
		);

		$tags = $this->getCompanyFields($filter_tags);
		$tag_personal = (!empty($info['UF_CRM_PERSONAL_TAG_ITEMS_FIELD']) && !empty($tags['tag_personal'][$info['UF_CRM_PERSONAL_TAG_ITEMS_FIELD']])) ? $tags['tag_personal'][$info['UF_CRM_PERSONAL_TAG_ITEMS_FIELD']] : '';
		$tag_branch = array();
		$tag_product = array();
		$activity = !empty($info['UF_CRM_1641805321009']) ? $tags['activity'][$info['UF_CRM_1641805321009']] : '';

		if(!empty($tags['tag_product'])) {
			foreach($tags['tag_product'] as $item) {
				$tag_product[] = $item;
			}
		}

		if(!empty($tags['tag_branch'])) {
			foreach($tags['tag_branch'] as $item) {
				$tag_branch[] = $item;
			}
		}

		$logo = '';
		if(!empty($info['UF_CRM_1670916428']['downloadUrl'])) {
			$logo = $this->saveCompanyLogo($info['UF_CRM_1670916428']['downloadUrl'], $company_id);
		}
		$social = array();
		if(!empty($info['IM'])) {
			foreach($info['IM'] as $soc_item) {
				$social[] = array(
					'link'	=> $soc_item['VALUE'],
					'type'	=> $soc_item['VALUE_TYPE'],
				);
			}
		}

		switch($info['COMPANY_TYPE']) {
			case 'RESELLER': $type_id = $this->config->get('themeset_bitrix_company_reseller');break;
			default: $type_id = $this->config->get('themeset_bitrix_company_investor');
		}

		/* alternate */
		$alternate = array($info['TITLE']);
		if(!empty($info['UF_CRM_1674121599']) && is_array($info['UF_CRM_1674121599'])) {
			foreach($info['UF_CRM_1674121599'] as $al_item) {
				$alternate[] = $al_item;
			}
		}

		/* city */
		$city = '';
		if(!empty($info['UF_CRM_1644845604412'])) {
			$city_arr = explode("|", $info['UF_CRM_1644845604412']);

			if(!empty($city_arr[0])) {
				$city = trim($city_arr[0]);
			}
		}

		$company_info = array(
			'b24id'					=> $company_id,
			'title'					=> $name,
			'alternate'			=> implode(" ", $alternate),
			'logo'					=> $logo,
			'phone_1'				=> !empty($info['PHONE'][0]['VALUE']) ? $info['PHONE'][0]['VALUE'] : '',
			'phone_2'				=> !empty($info['PHONE'][1]['VALUE']) ? $info['PHONE'][1]['VALUE'] : '',
			'email'					=> !empty($info['EMAIL'][0]['VALUE']) ? $info['EMAIL'][0]['VALUE'] : '',
			'web'						=> !empty($info['WEB'][0]['VALUE']) ? $info['WEB'][0]['VALUE'] : '',
			'description'		=> $info['UF_CRM_1639069033128'],
			'city'					=> $city,
			'social'				=> $social,
			'type_id'				=> $type_id,
			'tag_personal'	=> $tag_personal,
			'tag_branch'		=> $tag_branch,
			'tag_product'		=> $tag_product,
            'inn'			=> $result["requisites"],
            'address'			=> $info['UF_CRM_1707121975'],
            'director'			=> $info['UF_CRM_1707121931'],
			'activity'			=> $activity,
			'sync'					=> $sync,
			'flag_archive'	=> $flag_archive,
		);

		if(!empty($show_info)) {
			echo '<pre>';
			print_r($company_info);
			echo '</pre>';
		}

		if(!empty($options['show_result'])) {
			$text = '';
			$text .= '<br>' . 'Синхронизация прошла УСПЕШНО<br>';
			$text .= '<br>' . 'Компания: <strong>' . $company_info['title'] . '</strong>';
			$text .= '<br>' . 'Архивная: <strong>' . ($company_info['flag_archive'] == 1 ? 'ДА' : 'НЕТ') . '</strong>';
			$text .= '<br>' . 'Показывать в каталоге: <strong>' . ($company_info['sync'] == 1 ? 'ДА' : 'НЕТ') . '</strong>';
			echo $text;
		}

		$message = "\n\n\n------------------\nIMPORT INFO\n------------------\n";
		foreach($company_info as $key=>$value) {
			$message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
		}
		$log->write($message);

		$this->updateCompany($company_info);

	}

	public function updateCompanyNames($data = array()) {
		$b24id = '';
		$query = $this->db->query("SELECT DISTINCT b24id FROM " . DB_PREFIX . "company_names WHERE b24id = '" . (int)$data['b24id'] . "'");
		if($query->num_rows) {
			$b24id = $query->row['b24id'];
		}

		if($b24id) {
			$this->db->query("UPDATE " . DB_PREFIX . "company_names SET 
				title = '" . $this->db->escape($data['title']) . "', 
				alternate = '" . $this->db->escape($data['alternate']) . "', 
				city = '" . $this->db->escape($data['city']) . "', 
				phone = '" . $this->db->escape($data['phone']) . "', 
				web = '" . $this->db->escape($data['web']) . "', 
				inn = '" . $this->db->escape($data['inn']) . "', 
				director = '" . $this->db->escape($data['director']) . "', 
				address = '" . $this->db->escape($data['address']) . "', 
				activity = '" . $this->db->escape($data['activity']) . "', 
				archive = '" . (int)$data['flag_archive'] . "' 
				WHERE  
				b24id = '" . (int)$b24id . "'");
		}else if(!$data['flag_archive']){
			$this->db->query("INSERT INTO " . DB_PREFIX . "company_names SET 
				b24id = '" . (int)$data['b24id'] . "', 
				title = '" . $this->db->escape($data['title']) . "', 
				alternate = '" . $this->db->escape($data['alternate']) . "',
				city = '" . $this->db->escape($data['city']) . "', 
				phone = '" . $this->db->escape($data['phone']) . "', 
				inn = '" . $this->db->escape($data['inn']) . "', 
				director = '" . $this->db->escape($data['director']) . "', 
				address = '" . $this->db->escape($data['address']) . "', 
				web = '" . $this->db->escape($data['web']) . "', 
				activity = '" . $this->db->escape($data['activity']) . "',  
				archive = '" . (int)$data['flag_archive'] . "'");
		}
	}

	public function updateCompany($data = array()) {

		$this->load->model('tag/tag');

		$key_list = array(
			'b24id'					=> '',
			'title'					=> '',
			'alternate'			=> '',
			'logo'					=> '',
			'phone_1'				=> '',
			'phone_2'				=> '',
			'city'					=> '',
			'email'					=> '',
			'web'						=> '',
            'inn'						=> '',
            'address'						=> '',
            'director'						=> '',
			'description'		=> '',
			'activity'			=> '',
			'social'				=> array(),
			'type_id'				=> 0,
			'tag_id'				=> 0,
			'sync'					=> 0,
			'flag_archive'	=> 1,
		);
		foreach($key_list as $key=>$default) {
			if(!isset($data[$key])) {$data[$key] = $default;}
		} 

		if(!empty($data['tag_personal'])) {
			$tag_info = $this->model_tag_tag->getTagByName($data['tag_personal']);
			if($tag_info) {
				$data['tag_id'] = $tag_info['tag_id'];
			}else{
				$data['tag_id'] = $this->model_tag_tag->addTagByName($data['tag_personal']);
			}
		}

		if(!$data['title'] || !$data['b24id']) {return false;}

		$company_id = 0;
		$lang_id = $this->config->get('config_language_id');

		/* update company names */
		$names_data = array(
			'b24id'					=> $data['b24id'],
			'title'					=> $data['title'],
			'alternate'			=> $data['alternate'],
			'city'					=> $data['city'],
			'phone'					=> $data['phone_1'],
			'web'						=> $data['web'],
            'inn'						=> $data['inn'],
            'address'						=> $data['address'],
            'director'						=> $data['director'],
			'activity'			=> $data['activity'],
			'flag_archive'	=> $data['flag_archive'],
		);
		$this->updateCompanyNames($names_data);
		/* # update company names */

		// Синхронизация ДА -- Архивность НЕТ
		$status = !$data['flag_archive'] && !empty($data['sync']) ? 1 : 0;

		/* Поиск по ID */
		$query = $this->db->query("SELECT DISTINCT c.company_id, cd.language_id FROM " . DB_PREFIX . "company c LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) WHERE c.b24id = '" . (int)$data['b24id'] . "' AND c.b24id <> '' ");
		if($query->num_rows) {
			$company_id = $query->row['company_id'];
			$lang_id = $query->row['language_id'];
		}

		/* Поиск по названию */
		if(!$company_id && $status) {
			$query = $this->db->query("SELECT DISTINCT c.company_id, cd.language_id 
				FROM " . DB_PREFIX . "company c 
				LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) 
				WHERE 
				LCASE(cd.title) = '" . $this->db->escape(utf8_strtolower($data['title'])) . "'");

			if($query->num_rows) {
				$company_id = $query->row['company_id'];
				$lang_id = $query->row['language_id'];
				$this->db->query("UPDATE " . DB_PREFIX . "company SET b24id = '" . (int)$data['b24id'] . "' WHERE company_id = '" . (int)$company_id . "'");
			}
		}


		/* обновление статуса */
		if($company_id) {
			$this->db->query("UPDATE " . DB_PREFIX . "company SET 
				status = '" . (int)$status . "'
				WHERE company_id = '" . (int)$company_id . "'");
		}

		/* Синхронизация: ДА */
		if($status) {

			if($company_id) {
				/* UPDATE COMPANY */

				$this->db->query("UPDATE " . DB_PREFIX . "company SET 
					category_id = '" . (int)$data['type_id'] . "', 
					tag_id = '" . (int)$data['tag_id'] . "', 
					image = '" . $this->db->escape($data['logo']) . "', 
					phone_1 = '" . $this->db->escape($data['phone_1']) . "', 
					phone_2 = '" . $this->db->escape($data['phone_2']) . "', 
					site = '" . $this->db->escape($data['web']) . "', 
					email = '" . $this->db->escape($data['email']) . "', 
					activity = '" . $this->db->escape($data['activity']) . "',
					status = '" . (int)$status . "'
					WHERE company_id = '" . (int)$company_id . "'");

				$this->db->query("UPDATE " . DB_PREFIX . "company_description SET 
					title = '" . $this->db->escape($data['title']) . "', 
					alternate = '" . $this->db->escape($data['alternate']) . "', 
					description = '" . $this->db->escape($data['description']) . "' 
					WHERE  
					company_id = '" . (int)$company_id . "' 
					AND language_id = '" . (int)$lang_id . "'");

				/* social */
				$this->db->query("DELETE FROM " . DB_PREFIX . "company_social WHERE company_id = '" . (int)$company_id . "'");
				if (isset($data['social'])) {
					$index = 0;
					foreach ($data['social'] as $item) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "company_social SET company_id = '" . (int)$company_id . "', link = '" . $this->db->escape($item['link']) . "', type = '" . $this->db->escape($item['type']) . "', sort_order = '" . (int)$index . "'");
						$index++;
					}
				}

				/* tag product */
				$this->db->query("DELETE FROM " . DB_PREFIX . "company_to_tag WHERE company_id = '" . (int)$company_id . "'");
				if(!empty($data['tag_product'])) {
					foreach($data['tag_product'] as $tag_name) {
						$tag_info = $this->model_tag_tag->getTagByName($tag_name);
						if($tag_info) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_tag SET company_id = '" . (int)$company_id . "', tag_id = '" . (int)$tag_info['tag_id'] . "'");
						}else{
							$tag_id = $this->model_tag_tag->addTagByName($tag_name);
							$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_tag SET company_id = '" . (int)$company_id . "', tag_id = '" . (int)$tag_id . "'");
						}
					}
				}

				/* tag branch */
				$this->db->query("DELETE FROM " . DB_PREFIX . "company_to_branch WHERE company_id = '" . (int)$company_id . "'");
				if(!empty($data['tag_branch'])) {
					foreach($data['tag_branch'] as $branch_name) {
						$branch_info = $this->model_tag_tag->getTagByName($branch_name);
						if($branch_info) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_branch SET company_id = '" . (int)$company_id . "', branch_id = '" . (int)$branch_info['tag_id'] . "'");
						}else{
							$branch_id = $this->model_tag_tag->addTagByName($branch_name);
							$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_branch SET company_id = '" . (int)$company_id . "', branch_id = '" . (int)$branch_id . "'");
						}
					}
				}

				$query_keyword = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'company_id=" . (int)$company_id . "' LIMIT 1");
				if(!$query_keyword->num_rows) {
					$this->load->model('themeset/url');
					$keyword = $this->model_themeset_url->getUrl($data['title']);
					if (isset($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'company_id=" . (int)$company_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}

				/* # UPDATE COMPANY */
			}else{
				/* CREATE COMPANY */
				$this->db->query("INSERT INTO " . DB_PREFIX . "company SET 
					sort_order = '0', 
					image = '" . $this->db->escape($data['logo']) . "', 
					b24id = '" . (int)$data['b24id'] . "', 
					category_id = '" . (int)$data['type_id'] . "', 
					tag_id = '" . (int)$data['tag_id'] . "', 
					phone_1 = '" . $this->db->escape($data['phone_1']) . "', 
					phone_2 = '" . $this->db->escape($data['phone_2']) . "', 
					site = '" . $this->db->escape($data['web']) . "', 
					email = '" . $this->db->escape($data['email']) . "', 
					activity = '" . $this->db->escape($data['activity']) . "',
					status = '" . (int)$status . "'
					");
				$company_id = $this->db->getLastId();

				$this->db->query("INSERT INTO " . DB_PREFIX . "company_description SET 
					company_id = '" . (int)$company_id . "', 
					language_id = '" . (int)$lang_id . "', 
					title = '" . $this->db->escape($data['title']) . "', 
					preview = '', 
					description = '" . $this->db->escape($data['description']) . "', 
					meta_title = '', 
					meta_h1 = '', 
					meta_description = '', 
					meta_keyword = ''");

				/* social */
				if (isset($data['social'])) {
					$index = 0;
					foreach ($data['social'] as $item) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "company_social SET company_id = '" . (int)$company_id . "', link = '" . $this->db->escape($item['link']) . "', type = '" . $this->db->escape($item['type']) . "', sort_order = '" . (int)$index . "'");
						$index++;
					}
				}

				/* tag product */
				$this->db->query("DELETE FROM " . DB_PREFIX . "company_to_tag WHERE company_id = '" . (int)$company_id . "'");
				if(!empty($data['tag_product'])) {
					foreach($data['tag_product'] as $tag_name) {
						$tag_info = $this->model_tag_tag->getTagByName($tag_name);
						if($tag_info) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_tag SET company_id = '" . (int)$company_id . "', tag_id = '" . (int)$tag_info['tag_id'] . "'");
						}else{
							$tag_id = $this->model_tag_tag->addTagByName($tag_name);
							$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_tag SET company_id = '" . (int)$company_id . "', tag_id = '" . (int)$tag_id . "'");
						}
					}
				}

				/* tag branch */
				$this->db->query("DELETE FROM " . DB_PREFIX . "company_to_branch WHERE company_id = '" . (int)$company_id . "'");
				if(!empty($data['tag_branch'])) {
					foreach($data['tag_branch'] as $branch_name) {
						$branch_info = $this->model_tag_tag->getTagByName($branch_name);
						if($branch_info) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_branch SET company_id = '" . (int)$company_id . "', branch_id = '" . (int)$branch_info['tag_id'] . "'");
						}else{
							$branch_id = $this->model_tag_tag->addTagByName($branch_name);
							$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_branch SET company_id = '" . (int)$company_id . "', branch_id = '" . (int)$branch_id . "'");
						}
					}
				}

				$this->load->model('themeset/url');
				$keyword = $this->model_themeset_url->getUrl($data['title']);
				if (isset($keyword)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'company_id=" . (int)$company_id . "', keyword = '" . $this->db->escape($keyword) . "'");
				}
				/* # CREATE COMPANY */
			}

		}
		/* # Синхронизация: ДА */
		
		$this->cache->delete('seo_pro');

	}

	public function deleteCompany($company_id = 0) {
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_names 
			WHERE 
			b24id = '" . (int)$company_id . "'");

		$this->db->query("UPDATE " . DB_PREFIX . "company 
			SET status = '0' 
			WHERE 
			b24id = '" . (int)$company_id . "'");
		
	}

    public function getAllCompanies() {
        $sql = "SELECT c.b24id FROM " . DB_PREFIX . "company_names c ";

        $companies = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $companies[] = $result["b24id"];
        }

        return $companies;

    }

	private function getCompanyFromB24($company_id) {
		$company_data = array();
		$counter = 0;

		//$bitrixWebHook = $this->b24_hook;
        $client_id = $this->config->get('themeset_bitrix_company_client_id');
        $client_secret = $this->config->get('themeset_bitrix_company_client_secret');

        require_once(DIR_SYSTEM . 'library/crest/crest.php');

		while(empty($company_data) && $counter < 3) {

			if($counter) {
				sleep(5);
			}
			$counter++;
			
			$result = CRest::call(
				'crm.company.get', 
				[
					"id" 	=> $company_id,
				]
			);

			if(!empty($result['result']['ID'])) {
				$company_data = $result;

                $resultRequisite = CRest::call(
                    'crm.requisite.list',
                    [
                        "filter" => ["ENTITY_TYPE_ID" => 4, "ENTITY_ID" => $company_id],
                    ]
                );

                if (!empty($resultRequisite['result'])) {
                    $company_data['requisites'] = $resultRequisite['result'][0]['RQ_INN'];
                }
			}
		}

		return $company_data;
	}

	private function getCompanyFields($filter_data = array()) {

		$this->load->model('themeset/tag');

		$filter = array(
			'personal' => !empty($filter_data['personal']) ? $filter_data['personal'] : array(),
			'branch' => !empty($filter_data['branch']) ? $filter_data['branch'] : array(),
			'product' => !empty($filter_data['product']) ? $filter_data['product'] : array(),
		);

		$tags = array(
			'tag_personal'	=> $this->model_themeset_tag->getTagsFromList(95, $filter['personal']),
			'tag_branch'		=> $this->model_themeset_tag->getTagsFromList(91, $filter['branch']),
			'tag_product'		=> $this->model_themeset_tag->getTagsFromList(93, $filter['product']),
			'activity'			=> $this->model_themeset_tag->getActivity(),
		);


		return $tags;
	}

	private function getListElements($list_id = 0, $filter_data = array()) {

		$tags = array();

		$bitrixWebHook = $this->b24_hook;
		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$result = CRest::call(
			'lists.element.get',
			[
				"IBLOCK_TYPE_ID" 	=> "lists",
				"IBLOCK_ID"				=> $list_id,
				"start"						=> -1,
				"filter"					=> array("=ID"=>$filter_data)
			]
		);

		if(!empty($result['result'])) {
			foreach($result['result'] as $item) {
				$tags[$item['ID']] = $item['NAME'];
			}
		}

		return $tags;
	}

    private function saveCompanyLogo($link = '', $company_id = 0) {
		$b24_url = $this->config->get('themeset_bitrix_url');
        $url = 'https://' . $b24_url . $link;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $image = curl_exec($ch);
        curl_close($ch);

        $image_type = exif_imagetype($url);
        $extension = image_type_to_extension($image_type);

        $image_path = 'catalog/companies/' . $company_id . $extension;

        $savefile = fopen(DIR_IMAGE . $image_path, 'w');
        fwrite($savefile, $image);
        fclose($savefile);

        /* REMOVE OLD IMAGE */
        $dir = DIR_IMAGE . 'cache/catalog/companies/';
        $files = array();
        $path = array($dir . $company_id . '-*');
        while (count($path) != 0) {
            $next = array_shift($path);

            foreach (glob($next) as $file) {
                if (is_dir($file)) {
                    $path[] = $file . '/*';
                }
                $files[] = $file;
            }
        }
        rsort($files);
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        /* # REMOVE OLD IMAGE */

        return $image_path;

    }


//	private function saveCompanyLogo($link = '', $company_id = 0) {
//		$refresh_token = $this->config->get('themeset_bitrix_company_refresh_token');
//		$client_id = $this->config->get('themeset_bitrix_company_client_id');
//		$client_secret = $this->config->get('themeset_bitrix_company_client_secret');
//		$b24_url = $this->config->get('themeset_bitrix_url');
//
//		if(!$refresh_token || !$client_id || !$client_secret || !$b24_url) {return false;}
//
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ch, CURLOPT_POST, false);
//		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//		curl_setopt($ch, CURLOPT_URL,'https://oauth.bitrix.info/oauth/token/?grant_type=refresh_token&client_id='.$client_id.'&client_secret='.$client_secret.'&refresh_token=' . $refresh_token);
//		$result = json_decode(curl_exec($ch), true);
//
//		curl_close($ch);
//        var_dump($result);
//
//		if(empty($result['access_token']) || empty($result['refresh_token'])) {return false;}
//
//		$access_token = $result['access_token'];
//		$refresh_token = $result['refresh_token'];
//
//		$this->load->model('themeset/themeset');
//		$this->model_themeset_themeset->editSetting('themeset', array('themeset_bitrix_company_refresh_token'=>$refresh_token));
//
//		$url = 'https://' . $b24_url . $link . '&auth=' . $access_token;
//var_dump($url);
//die();
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_POST, 0);
//		curl_setopt($ch,CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		$image = curl_exec($ch);
//		curl_close($ch);
//
//		$image_type = exif_imagetype($url);
//		$extension = image_type_to_extension($image_type);
//
//		$image_path = 'catalog/companies/' . $company_id . $extension;
//
//		$savefile = fopen(DIR_IMAGE . $image_path, 'w');
//		fwrite($savefile, $image);
//		fclose($savefile);
//
//		/* REMOVE OLD IMAGE */
//		$dir = DIR_IMAGE . 'cache/catalog/companies/';
//		$files = array();
//		$path = array($dir . $company_id . '-*');
//		while (count($path) != 0) {
//			$next = array_shift($path);
//
//			foreach (glob($next) as $file) {
//				if (is_dir($file)) {
//					$path[] = $file . '/*';
//				}
//				$files[] = $file;
//			}
//		}
//		rsort($files);
//		foreach ($files as $file) {
//			if (is_file($file)) {
//				unlink($file);
//			}
//		}
//		/* # REMOVE OLD IMAGE */
//
//		return $image_path;
//
//	}


	private function alert($data = array()) {
		$message = "\n";

		foreach($data as $key=>$value) {
			$message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
		}


		/* MAIL ALERT */

		$title = 'Ошибка обновления компании';

		$email_subject = 	$title . ' - ' . $this->config->get('config_name');


		$mail = new Mail();
		$mail->protocol = $this->config->get('av_alert_mail_protocol');
		$mail->parameter = $this->config->get('av_alert_mail_parameter');
		$mail->smtp_hostname = $this->config->get('av_alert_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('av_alert_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('av_alert_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('av_alert_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('av_alert_mail_smtp_timeout');

		$mail->setTo('p.ziuziun@techin.by');
		$mail->setFrom($this->config->get('av_alert_mail_protocol') === 'smtp' ? $this->config->get('av_alert_mail_smtp_username') : $this->config->get('av_alert_email'));
		$mail->setSender('АВ Клуб | AV Club');
		$mail->setSubject($email_subject);

		$mail->setText($message);
		$mail->send();
	}

}