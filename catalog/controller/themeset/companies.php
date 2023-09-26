<?php
class ControllerThemesetCompanies extends Controller {

	private $error = array();

	private $b24_hook = 'https://avclub.bitrix24.ru/rest/669/2yt2mpuav23aqllx/';



	public function index() {
		// НЕ УДАЛЯТЬ!
		// используется для локального приложениея

		return true;	
	}

	public function getCompanies() {

		$update_names = !empty($this->request->get['update_names']) ? true : false;

		$bitrixWebHook = $this->b24_hook;

		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		/* FIELDS */
		$activity = array();
		$result = CRest::call(
			'crm.company.fields',
			[]
		);
		if(!empty($result['result']['UF_CRM_1641805321009']['items'])) {
			foreach($result['result']['UF_CRM_1641805321009']['items'] as $key=>$value) {
				$activity[$value['ID']] = $value['VALUE'];
			}
		}
		/* # FIELDS */


		$result = CRest::call(
			'crm.company.list',
			[
				"order" 	=> array(
					"DATE_CREATE"=>"ASC"
				),
				'filter'	=> array(
					// "=ID" => array(45007),
					'UF_CRM_1687952516' => 0, // Архивная: нет 
					// 'UF_CRM_1639493528' => 3045, // Синхронизация: да 
				),
				"select"	=> array(
					'ID', 
					'TITLE', 
					'PHONE', 
					'WEB', 
					'UF_CRM_1674121599', 
					'UF_CRM_1687952516',
					'UF_CRM_1641805321009',
					'UF_CRM_1644845604412',
				),
				"start"		=> !empty($this->request->get['start']) ? (int)$this->request->get['start'] : 0
			]
		);

		echo '<pre>';
        var_dump('1');
		print_r($result);
		echo '</pre>';

		foreach($result['result'] as $item) {
			echo '<a href="https://www.avclub.pro/index.php?route=themeset/companies/getCompanyById&id=' . $item['ID'] . '" target="_blank">https://www.avclub.pro/index.php?route=themeset/companies/getCompanyById&id=' . $item['ID'] . '</a><br>';
		}

		if(!empty($update_names)) {
			$this->load->model('themeset/companies');
			foreach($result['result'] as $item) {

				$flag_archive = isset($item['UF_CRM_1687952516']) && $item['UF_CRM_1687952516'] == 0 ? 0 : 1;

				$alternate = array($item['TITLE']);
				if(!empty($item['UF_CRM_1674121599']) && is_array($item['UF_CRM_1674121599'])) {
					foreach($item['UF_CRM_1674121599'] as $al_item) {
						$alternate[] = $al_item;
					}
				}

				$city = '';
				if(!empty($item['UF_CRM_1644845604412'])) {
					$city_arr = explode("|", $item['UF_CRM_1644845604412']);

					if(!empty($city_arr[0])) {
						$city = trim($city_arr[0]);
					}
				}

				$names_data = array(
					'b24id'					=> $item['ID'],
					'title'					=> $item['TITLE'],
					'alternate'			=> implode(" ", $alternate),
					'city'					=> $city,
					'phone'					=> !empty($item['PHONE'][0]['VALUE']) ? $item['PHONE'][0]['VALUE'] : '',
					'web'						=> !empty($item['WEB'][0]['VALUE']) ? $item['WEB'][0]['VALUE'] : '',
					'activity'			=> !empty($item['UF_CRM_1641805321009']) && !empty($activity[$item['UF_CRM_1641805321009']]) ? $activity[$item['UF_CRM_1641805321009']] : $item['UF_CRM_1641805321009'],
					'flag_archive'	=> $flag_archive,
				);
				echo '<pre>';
				print_r($names_data);
				echo '</pre>';
				$this->model_themeset_companies->updateCompanyNames($names_data);
			}
		}

	}
	public function getLeadFields() {

		$bitrixWebHook = $this->b24_hook;
		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$result = CRest::call(
			'crm.lead.fields',
			[]
		);

		echo '<pre>';
		print_r($result);
		echo '</pre>';
	}
	public function getLeadList() {

		$bitrixWebHook = $this->b24_hook;
		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$result = CRest::call(
			'crm.lead.list',
			[
				'order'	=>  [ "DATE_CREATE" => "DESC" ],
				'filter' => [
					'>DATE_CREATE' => '2022-03-31T00:00:00',
					'<DATE_CREATE' => '2024-10-31T23:59:59'
				],
				'select' => [ "*", "UF_*" ]
			]
		);

		echo '<pre>';
		print_r($result);
		echo '</pre>';
	}



	public function getCompanyById($company_id = 0) {

		if(isset($this->request->get['id'])) {
			$company_id = $this->request->get['id'];
		}

		$show_info = isset($this->request->get['show_info']) && $this->request->get['show_info'] == 0 ? false : true;

		$options = array(
			'show_result' => !empty($this->request->get['show_result']) ? true : false
		);

		$this->load->model('themeset/companies');
		$this->model_themeset_companies->getCompanyInfo($company_id, $show_info, $options);

	}

	public function updateCompany() {

		$log = new Log('company_change-' . date('Y-m-d') . '.log');
		$message = "\n------------------\n";
		$message .= !empty($this->request->post) ? json_encode($this->request->post) : 'POST Empty';
		$log->write($message);

		$company_id = !empty($this->request->post['data']['FIELDS']['ID']) ? $this->request->post['data']['FIELDS']['ID'] : 0;
		$event = !empty($this->request->post['event']) ? $this->request->post['event'] : '';
		
		if($company_id) {
			$this->load->model('themeset/companies');

			switch (true) {

				case mb_strtoupper($event) === 'ONCRMCOMPANYDELETE':
				$this->model_themeset_companies->deleteCompany($company_id);
				break;

				default:
				$this->model_themeset_companies->getCompanyInfo($company_id);

			}

			
		}

	}

	public function refreshToken() {
		$refresh_token = $this->config->get('themeset_bitrix_company_refresh_token');
		$client_id = $this->config->get('themeset_bitrix_company_client_id');
		$client_secret = $this->config->get('themeset_bitrix_company_client_secret');

		if(!$refresh_token || !$client_id || !$client_secret) {return false;}

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
	}



	public function install() {
		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$result = CRest::installApp();
		return true;
	}



}
