<?php
class ModelThemesetExpert extends Model {

	private $b24_list = 'https://avclub.bitrix24.ru/rest/669/2yt2mpuav23aqllx/';

	// UF_CRM_1678367582 = 1 - архивный контакт

	public function deleteContact($contact_id = 0) {
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_alternate 
			WHERE 
			b24id = '" . (int)$contact_id . "'");

		$this->db->query("UPDATE " . DB_PREFIX . "visitor 
			SET 
			b24id = '0' 
			WHERE 
			b24id = '" . (int)$contact_id . "'");
		
	}

	public function getContactInfo($contact_id = 0, $show_info = false, $create_new = false, $options = array()) {

		$log_file = 'user_info-' . date('Y-m-d') . '.log';
		$log = new Log($log_file);
		$message = "\n\n\n\n\n";
		$message .= json_encode(array($contact_id, $show_info, $create_new, $options), JSON_UNESCAPED_UNICODE);

		$this->load->model('register/register');
		$this->load->model('themeset/tag');

		/* ОБЯЗАТЕЛЬНО ОСТАВИТЬ! */
		$bitrixWebHook = $this->b24_list;
		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$expert_info = array();


		if(!empty($options['user_data'])) {
			$result = $options['user_data'];
			$info = $options['user_data'];
		}else{

			$result = $this->model_register_register->getContactInfo($contact_id);
			$info = $result;

			if(empty($info['ID'])) {
				$result = CRest::call(
					'crm.contact.get',
					[
						"id" 	=> $contact_id,
					]
				);

				$info = $result['result'];
			}

		}

		$message .= "------------------\nRESULT\n------------------\n";
		if(!empty($info)) {
			foreach($info as $key=>$value) {
				$message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
			}
		}else{
			$message .= json_encode($info, JSON_UNESCAPED_UNICODE);
		}
		$log->write($message);

		if(empty($info['ID'])) {
			$alert_data = array(
				'contact_id'	=> $contact_id,
				'show_info'		=> $show_info,
				'create_new'	=> $create_new,
				'options'			=> $options,
				'result'			=> $result,
			);
			$this->alertExpertUpdate($alert_data);

			if(!empty($options['show_result'])) {
				$text = '';
				$text .= '<br>' . 'Синхронизация <strong>НЕ УДАЛАСЬ</strong><br>';
				$text .= '<br>' . 'contact_id: <strong>' . $contact_id . '</strong>';
				$text .= '<br>' . 'show_info: <strong>' . $show_info . '</strong>';
				$text .= '<br>' . 'create_new: <strong>' . $create_new . '</strong>';
				$text .= '<br>' . 'options: <strong>' . implode(' / ', $options) . '</strong>';
				echo $text;

				echo '<pre>';
				print_r($result);
				echo '</pre>';
			}

			return;
		}

		if($show_info) {
			echo '<pre>';
			print_r($result);
			echo '</pre>';
		}

		$this->updateExpertConnection($info);		

		$expert_info['b24_expert_id'] = $contact_id;
		$expert_info['expert'] = !empty($info['UF_CRM_1676312951']) ? 1 : 0;
		$expert_info['status'] = empty($info['UF_CRM_1678367582']) ? 1 : 0;

		$this->updateExpertStatus($expert_info);

		if($expert_info['expert'] || $expert_info['status'] || !empty($create_new)) {

			$expert_info['name'] = trim($info['NAME']) . ' ' . trim($info['LAST_NAME']);
			$expert_info['firstname'] = trim($info['NAME']);
			$expert_info['lastname'] = trim($info['LAST_NAME']);
			$expert_info['post'] = $info['POST'];
			$expert_info['b24_company_id'] = !empty($info['COMPANY_ID']) ? $info['COMPANY_ID'] : 0;
			$expert_info['field_expertise'] = !empty($info['UF_CRM_1686648613']) ? $info['UF_CRM_1686648613'] : '';
			$expert_info['field_useful'] = !empty($info['UF_CRM_1686648651']) ? $info['UF_CRM_1686648651'] : '';
			$expert_info['field_regalia'] = !empty($info['UF_CRM_1686648672']) ? $info['UF_CRM_1686648672'] : '';


			$expert_info['emails'] = array();
			if(!empty($info['EMAIL'])) {

				/* стандартный запрос через битрикс */
				if(!empty($info['EMAIL'][0]['VALUE_TYPE'])) {
					foreach($info['EMAIL'] as $item) {
						if($item['VALUE_TYPE'] === 'WORK') {
							$expert_info['emails'][] = $item['VALUE'];
						}
					}
				}else{
					/* запрос через прослойку */
					foreach($info['EMAIL'] as $item) {
						$expert_info['emails'][] = $item;
					}
				}

			}

			$photo = '';
			if(!empty($info['PHOTO']['downloadUrl'])) {
				$photo = $this->saveExpertPhoto($info['PHOTO']['downloadUrl'], $contact_id);
			}
			$expert_info['photo'] = $photo;

			/* tags */
			$filter_tag = array(
				'expert'	=> !empty($info['UF_CRM_1676473306']) ? $info['UF_CRM_1676473306'] : array(),
				'branch'	=> !empty($info['UF_CRM_1676473363']) ? $info['UF_CRM_1676473363'] : array(),
			);

			$tags = array(
				'expert'	=> $this->model_themeset_tag->getTagsFromList(93, $filter_tag['expert']),
				'branch'	=> $this->model_themeset_tag->getTagsFromList(91, $filter_tag['branch'])
			);

			$tag_expert = array();
			$tag_branch = array();

			if(!empty($info['UF_CRM_1676473306'])) {
				foreach($info['UF_CRM_1676473306'] as $key) {
					if(!empty($tags['expert'][$key])) {
						$tag_expert[] = $tags['expert'][$key];
					}
				}
			}
			if(!empty($info['UF_CRM_1676473363'])) {
				foreach($info['UF_CRM_1676473363'] as $key) {
					if(!empty($tags['branch'][$key])) {
						$tag_branch[] = $tags['branch'][$key];
					}
				}
			}
			$expert_info['tag_expert'] = $tag_expert;
			$expert_info['tag_branch'] = $tag_branch;
			/* # tags */

			$this->updateExpert($expert_info);
		}

		if($show_info) {
			echo '<pre>';
			print_r($expert_info);
			echo '</pre>';
		}

		$message = "\n\n\n------------------\nIMPORT INFO\n------------------\n";
		foreach($expert_info as $key=>$value) {
			$message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
		}
		$log->write($message);

		if(!empty($options['show_result'])) {
			$text = '';
			$text .= '<br>' . 'Синхронизация прошла УСПЕШНО<br>';
			$text .= '<br>' . 'Контакт: <strong>' . $expert_info['name'] . '</strong>';
			$text .= '<br>' . 'Эксперт: <strong>' . ($expert_info['expert'] == 1 ? 'ДА' : 'НЕТ') . '</strong>';
			$text .= '<br>' . 'Архивный: <strong>' . ($expert_info['status'] == 0 ? 'ДА' : 'НЕТ') . '</strong>';
			echo $text;
		}


	}

	private function getExpertTags($list_id = 0) {

		$tags = array();

		$bitrixWebHook = $this->b24_list;
		// require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$result = CRest::call(
			'lists.element.get',
			[
				"IBLOCK_TYPE_ID" 	=> "lists",
				"IBLOCK_ID"				=> $list_id
			]
		);

		if(!empty($result['result'])) {
			foreach($result['result'] as $item) {
				$tags[$item['ID']] = $item['NAME'];
			}
		}

		return $tags;
	}

	public function updateEventExp() {
		$authors = array();
		$query_authors = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor");
		foreach($query_authors->rows as $author) {

			$query_exp = $this->db->query("SELECT * 
				FROM " . DB_PREFIX . "visitor_exp ve
				WHERE 
				ve.visitor_id = '" . (int)$author['visitor_id'] . "' 
				ORDER BY 
				ve.exp_id ASC 
				LIMIT 0,1
				");
			if($query_exp->num_rows) {
				$authors[$author['visitor_id']] = array(
					'visitor_id'	=> $author['visitor_id'],
					'exp_id'			=> $query_exp->row['exp_id']
				);
			}
		}


		$sql_event = "SELECT e.event_id FROM " . DB_PREFIX . "avevent e 
		LEFT JOIN " . DB_PREFIX . "avevent_author ea ON (e.event_id = ea.event_id)
		WHERE
		ea.author_exp = '0' ";

		$query_event = $this->db->query($sql_event);

		if($query_event->num_rows) {
			foreach($query_event->rows as $event) {

				$sql_ea = "SELECT * FROM " . DB_PREFIX . "avevent_author ea 
				WHERE 
				ea.event_id = '" . (int)$event['event_id'] . "'  
				AND ea.author_exp = '0' ";

				$query_ea = $this->db->query($sql_ea);
				foreach($query_ea->rows as $item) {
					if(!empty($authors[$item['author_id']])) {
						$this->db->query("UPDATE " . DB_PREFIX . "avevent_author SET 
							author_exp = '" . (int)$authors[$item['author_id']]['exp_id'] . "' 
							WHERE 
							author_id = '" . (int)$item['author_id'] . "' 
							AND event_id = '" . (int)$item['event_id'] . "' ");
					}
				}

			}
		}
	}

	public function updateExpertStatus($data = array()) {

		// Поиск эксперта по ID Б24
		$expert_id = 0;
		$query_expert = $this->db->query("SELECT DISTINCT v.visitor_id, v.expert FROM " . DB_PREFIX . "visitor v 
			WHERE 
			v.b24id = '" . (int)$data['b24_expert_id'] . "' 
			AND v.b24id <> ''  
			AND v.b24id <> '0' ");

		if($query_expert->num_rows) {
			$expert_id = $query_expert->row['visitor_id'];

			/*********** 
			Проверяем на установление статуса ЭКСПЕРТ = ДА
			если он устанавливается, то обновляем дату 
			***********/
			if($query_expert->row['expert'] == 0 && $data['expert'] == 1) {
				$update_date = true;
			}else{
				$update_date = false;
			}
		}

		if($expert_id) {
			$sql = "UPDATE " . DB_PREFIX . "visitor SET 
			expert = '" . (!empty($data['expert']) ? (int)$data['expert'] : 0) . "'"; 
			if(!empty($update_date)) {
				$sql .= ", date_modified = NOW()";
			}
			$sql .= "	WHERE visitor_id = '" . (int)$expert_id . "'";
			$this->db->query($sql);
		}
	}


	public function updateExpert($data = array()) {

		$this->load->model('tag/tag');

		$key_list = array(
			'b24_expert_id'			=> '',
			'name'							=> '',
			'firstname'					=> '',
			'lastname'					=> '',
			'post'							=> '',
			'b24_company_id'		=> '',
			'emails'						=> array(),
			'photo'							=> '',
			'expert'						=> 0,
			'status'						=> 1,
			'tag_expert'				=> array(),
			'tag_branch'				=> array(),
			'field_expertise'		=> '',
			'field_useful'			=> '',
			'field_regalia'			=> '',
		);
		foreach($key_list as $key=>$default) {
			if(!isset($data[$key])) {$data[$key] = $default;}
		} 

		$email = !empty($data['emails']) ? $data['emails'][0] : '';


		if(!$data['name'] || !$data['b24_expert_id']) {return false;}

		
		$lang_id = $this->config->get('config_language_id');

		// Поиск эксперта по ID Б24
		$expert_id = 0;
		$query_expert = $this->db->query("SELECT DISTINCT v.visitor_id FROM " . DB_PREFIX . "visitor v 
			WHERE v.b24id = '" . (int)$data['b24_expert_id'] . "' AND v.b24id <> ''  AND v.b24id <> '0' ");
		if($query_expert->num_rows) {
			$expert_id = $query_expert->row['visitor_id'];
		}

		// Поиск привязанной компании по ID
		$company_id = 0;
		if(!empty($data['b24_company_id'])) {
			$query_company = $this->db->query("SELECT DISTINCT c.company_id, cd.language_id 
				FROM " . DB_PREFIX . "company c 
				LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) 
				WHERE 
				c.b24id = '" . (int)$data['b24_company_id'] . "' 
				AND c.b24id <> '' 
				");
			if($query_company->num_rows) {
				$company_id = $query_company->row['company_id'];
				$lang_id = $query_company->row['language_id'];
			}
		}
		$data['company_id'] = $company_id;

		if(!$expert_id) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "visitor SET 
				name = '" . $this->db->escape($data['name']) . "', 
				firstname = '" . $this->db->escape($data['firstname']) . "', 
				lastname = '" . $this->db->escape($data['lastname']) . "', 
				exp = '', 
				expert = '" . (!empty($data['expert']) ? (int)$data['expert'] : 0) . "', 
				b24id = '" . (!empty($data['b24_expert_id']) ? (int)$data['b24_expert_id'] : 0) . "', 
				date_added = NOW(), 
				date_modified = NOW(), 
				emails = '', 
				email = '', 
				image = '', 
				field_expertise = '" . $this->db->escape($data['field_expertise']) . "', 
				field_useful = '" . $this->db->escape($data['field_useful']) . "', 
				field_regalia = '" . $this->db->escape($data['field_regalia']) . "', 
				company_id = '" . (int)$company_id . "', 
				salt = '" . $this->db->escape($salt = token(9)) . "', 
				password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1(token(9))))) . "', 
				status = '" . (int)$data['status'] . "'");

			$expert_id = $this->db->getLastId();
		}
		

		if($expert_id) {

			/* UPDATE EXPERT */
			$this->db->query("UPDATE " . DB_PREFIX . "visitor SET 
				name = '" . $this->db->escape($data['name']) . "', 
				firstname = '" . $this->db->escape($data['firstname']) . "', 
				lastname = '" . $this->db->escape($data['lastname']) . "', 
				image = '" . $this->db->escape($data['photo']) . "', 
				field_expertise = '" . $this->db->escape($data['field_expertise']) . "', 
				field_useful = '" . $this->db->escape($data['field_useful']) . "', 
				field_regalia = '" . $this->db->escape($data['field_regalia']) . "', 
				company_id = '" . (int)$company_id . "', 
				expert = '" . (!empty($data['expert']) ? (int)$data['expert'] : 0) . "', 
				email = '" . $this->db->escape($email) . "',
				emails = '" . (!empty($data['emails']) ? $this->db->escape(implode(',',$data['emails'])) : '') . "', 
				status = '" . (int)$data['status'] . "' 
				WHERE 
				visitor_id = '" . (int)$expert_id . "' ");


			// Должность
			$query_exp = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor_exp ve 
				WHERE 
				ve.visitor_id = '" . (int)$expert_id . "' 
				ORDER BY 
				ve.exp_id DESC");
			$new_exp_id = 0;
			foreach($query_exp->rows as $row) {
				if($row['exp'] === htmlspecialchars($data['post'])) {
					$new_exp_id = $row['exp_id'];
					break;
				}
			}
			$this->db->query("UPDATE " . DB_PREFIX . "visitor_exp SET main = '0' WHERE visitor_id = '" . (int)$expert_id . "'");
			if($new_exp_id) {
				$this->db->query("UPDATE " . DB_PREFIX . "visitor_exp SET main = '1' 
					WHERE 
					visitor_id = '" . (int)$expert_id . "' 
					AND exp_id = '" . (int)$new_exp_id . "'");
			}else{
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_exp 
					SET 
					visitor_id = '" . (int)$expert_id . "', 
					main = '1', 
					exp = '" . $this->db->escape(htmlspecialchars($data['post'])) . "' 
					");
			}
			// # Должность

			/* экспертность */
			$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_tag_expert WHERE visitor_id = '" . (int)$expert_id . "'");
			if(!empty($data['tag_expert'])) {
				foreach($data['tag_expert'] as $tag_name) {
					$tag_info = $this->model_tag_tag->getTagByName($tag_name);
					if($tag_info) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_expert SET visitor_id = '" . (int)$expert_id . "', tag_id = '" . (int)$tag_info['tag_id'] . "'");
					}else{
						$tag_id = $this->model_tag_tag->addTagByName($tag_name);
						$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_expert SET visitor_id = '" . (int)$expert_id . "', tag_id = '" . (int)$tag_id . "'");
					}
				}
			}

			/* отрасль */
			$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_tag_branch WHERE visitor_id = '" . (int)$expert_id . "'");
			if(!empty($data['tag_branch'])) {
				foreach($data['tag_branch'] as $tag_name) {
					$tag_info = $this->model_tag_tag->getTagByName($tag_name);
					if($tag_info) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_branch SET visitor_id = '" . (int)$expert_id . "', tag_id = '" . (int)$tag_info['tag_id'] . "'");
					}else{
						$tag_id = $this->model_tag_tag->addTagByName($tag_name);
						$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_branch SET visitor_id = '" . (int)$expert_id . "', tag_id = '" . (int)$tag_id . "'");
					}
				}
			}


			$query_keyword = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'expert_id=" . (int)$expert_id . "' LIMIT 1");
			if(!$query_keyword->num_rows) {
				$this->load->model('themeset/url');
				$keyword = $this->model_themeset_url->getUrl(str_replace(" ", "-", $data['name']));
				if (isset($keyword)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'expert_id=" . (int)$expert_id . "', keyword = '" . $this->db->escape($keyword) . "'");
				}
			}
			
			/* # UPDATE EXPERT */
		}

		
		$this->cache->delete('seo_pro');

	}


	public function saveExpertPhoto($link = '', $expert_id = 0, $dir_path = 'catalog/experts/') {
		$refresh_token = $this->config->get('themeset_bitrix_company_refresh_token');
		$client_id = $this->config->get('themeset_bitrix_company_client_id');
		$client_secret = $this->config->get('themeset_bitrix_company_client_secret');
		$b24_url = $this->config->get('themeset_bitrix_url');

		if(!$refresh_token || !$client_id || !$client_secret || !$b24_url) {return false;}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL,'https://oauth.bitrix.info/oauth/token/?grant_type=refresh_token&client_id='.$client_id.'&client_secret='.$client_secret.'&refresh_token=' . $refresh_token);
		$result = json_decode(curl_exec($ch), true);

		curl_close($ch);

		if(empty($result['access_token']) || empty($result['refresh_token'])) {return false;}

		$access_token = $result['access_token'];
		$refresh_token = $result['refresh_token'];
		
		$this->load->model('themeset/themeset');
		$this->model_themeset_themeset->editSetting('themeset', array('themeset_bitrix_company_refresh_token'=>$refresh_token));

		$url = 'https://' . $b24_url . $link . '&auth=' . $access_token;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$image = curl_exec($ch);
		curl_close($ch);

		$image_type = exif_imagetype($url);
		$extension = image_type_to_extension($image_type);

		$image_path = $dir_path . $expert_id . $extension;

		$savefile = fopen(DIR_IMAGE . $image_path, 'w');
		fwrite($savefile, $image);
		fclose($savefile);

		/* REMOVE OLD IMAGE */
		$dir = DIR_IMAGE . 'cache/' . $dir_path;
		$files = array();
		$path = array($dir . $expert_id . '-*');
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

	/*
	Обновляем связь id Б24 с контактами на сайте, если контакт не архивный
	*/
	private function updateExpertConnection($info = array()) {

		$log = new Log('expert_connection-' . date('Y-m-d') . '.log');

		$message = "\n------------------\n";
		if(is_array($info) && $info) {
			foreach($info as $key=>$value) {
				$message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
			}
		}else if($info) {
			$message .= $info;
		}
		$log->write($message);

		if(!isset($info['UF_CRM_1678367582']) || (int)$info['UF_CRM_1678367582'] == 1) {
			return;
		}

		$log->write("update connection - " . $info['ID']);

		$expert_id = 0;
		$b24id = $info['ID'];

		$query_expert = $this->db->query("SELECT DISTINCT 
			v.visitor_id 
			FROM " . DB_PREFIX . "visitor v 
			WHERE 
			v.b24id = '" . (int)$b24id . "' 
			AND v.b24id <> '' 
			AND v.b24id <> '0' ");

		if($query_expert->num_rows) {
			$expert_id = $query_expert->row['visitor_id'];
		}

		/* 
		Если не нашли синхронизируемый контакт, 
		ищем среди альтернативных id и обновляем
		 */
		if(!$expert_id) {
			$query_alternate = $this->db->query("SELECT DISTINCT 
				va.visitor_id 
				FROM " . DB_PREFIX . "visitor_alternate va 
				WHERE 
				va.b24id = '" . (int)$b24id . "' 
				AND va.b24id <> '' 
				AND va.b24id <> '0' ");

			if($query_alternate->num_rows) {
				$expert_id = $query_alternate->row['visitor_id'];

				$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_alternate 
					WHERE 
					b24id = '" . (int)$b24id . "'");

				$this->db->query("UPDATE " . DB_PREFIX . "visitor 
					SET 
					b24id = '" . (int)$b24id . "' 
					WHERE 
					visitor_id = '" . (int)$expert_id . "'");

			}
		}
		/* # Поиск по альтернативным id */
		

	}


	/*
	Сообщение об ошибке
	*/
	private function alertExpertUpdate($data = array()) {
		$log = new Log('expert_update_fail.log');
		$message = "\n------------------\n";
		if(is_array($data) && $data) {
			foreach($data as $key=>$value) {
				$message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
			}
		}else if($data) {
			$message .= $data;
		}
		$log->write($message);

		/* MAIL ALERT */
		$emails = array(
			'kolikun@yandex.ru'
		);

		$title = 'Ошибка обновления контакта';

		$email_subject = 	$title . ' - ' . $this->config->get('config_name');


		$mail = new Mail();
		$mail->protocol = $this->config->get('av_alert_mail_protocol');
		$mail->parameter = $this->config->get('av_alert_mail_parameter');
		$mail->smtp_hostname = $this->config->get('av_alert_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('av_alert_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('av_alert_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('av_alert_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('av_alert_mail_smtp_timeout');

		$mail->setTo('bu.babasik@gmail.com');
		$mail->setFrom($this->config->get('av_alert_mail_protocol') === 'smtp' ? $this->config->get('av_alert_mail_smtp_username') : $this->config->get('av_alert_email'));
		$mail->setSender('АВ Клуб | AV Club');
		$mail->setSubject($email_subject);

		$mail->setText($message);
		$mail->send();

		// Send to additional alert emails
		foreach ($emails as $email_item) {
			if ($email_item && preg_match($this->config->get('config_mail_regexp'), $email_item)) {
				$mail->setTo($email_item);
				$mail->send();
			}
		}
	}


	private function mb_strcasecmp($str1, $str2, $encoding = null) {
		if (null === $encoding) {
			$encoding = mb_internal_encoding();
		}
		return strcmp(mb_strtolower($str1, $encoding), mb_strtolower($str2, $encoding));
	}

	private function utf8_strrev($str){
		preg_match_all('/./us', $str, $matches);
		return join('', array_reverse($matches[0]));
	}



}