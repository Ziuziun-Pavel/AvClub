<?php
class ControllerThemesetExpert extends Controller {

	private $b24_hook = 'https://avclub.bitrix24.ru/rest/669/2yt2mpuav23aqllx/';


	public function index() {
		// НЕ УДАЛЯТЬ!
		// используется для локального приложения

		return true;
	}

	public function install() {
		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$result = CRest::installApp();
		return true;
	}


	public function getContactsByPhone() {

		if(isset($this->request->get['phone'])) {
			$phone = $this->request->get['phone'];
		}

		$bitrixWebHook = $this->b24_hook;
		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$result = CRest::call(
			'crm.contact.list',
			[
				"order"			=> ["DATE_CREATE"=>"DESC"],
				"filter"		=> ["PHONE"=>"+" . preg_replace('/[^0-9]/', '', $phone)],
				"select"		=> ["ID"]
			]
		);

		echo '<pre>';
		print_r($result);
		echo '</pre>';

	}

	public function getContacts() {

		$bitrixWebHook = $this->b24_hook;

		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$result = CRest::call(
			'crm.contact.list',
			[
				"order" 	=> array(
					"DATE_CREATE"=>"ASC"
				),
				"filter"		=> ["UF_CRM_1676312951" => 1],
				"select"	=> array('ID', 'NAME', 'LAST_NAME', 'TYPE_ID', 'SOURCE_ID'),
				"limit" => 10,
				"start"	=> !empty($this->request->get['start']) ? $this->request->get['start'] : 0
			]
		);

		$contact_list = array();

		foreach($result['result'] as $contact) {
			$link = $this->url->link('themeset/expert/getContactById', 'contact_id=' . $contact['ID']);
			echo '<br><a href="' . $link . '">' . $link . '<a>';
		}

		echo '<pre>';
		print_r($result);
		echo '</pre>';

	}

	public function getContactById($contact_id = 0) {


		$this->load->model('themeset/expert');

		if(isset($this->request->get['contact_id'])) {
			$contact_id = $this->request->get['contact_id'];
		}

		$show_info = isset($this->request->get['show_info']) && $this->request->get['show_info'] == 0 ? false : true;

		$options = array(
			'show_result' => !empty($this->request->get['show_result']) ? true : false
		);

		$this->model_themeset_expert->getContactInfo($contact_id, $show_info, false, $options);

	}

	public function updateExpert() {

		$log = new Log('user_change-' . date('Y-m-d') . '.log');
		$message = "\n------------------\n";
		$message .= !empty($this->request->post) ? json_encode($this->request->post) : 'POST Empty';
		$log->write($message);

		$this->load->model('themeset/expert');

		if(!empty($this->request->post['data']['FIELDS']['ID'])) {
			$expert_id = $this->request->post['data']['FIELDS']['ID'];
		}else{
			$expert_id = 0;
		}

		$event = !empty($this->request->post['event']) ? $this->request->post['event'] : '';

		if($expert_id) {

			switch (true) {

				case mb_strtoupper($event) === 'ONCRMCONTACTDELETE':
				$this->model_themeset_expert->deleteContact($expert_id);
				break;

				default:
				$this->model_themeset_expert->getContactInfo($expert_id);

			}


		}


	}


	public function updateEventExp() {
		$this->load->model('themeset/expert');
		$this->model_themeset_expert->updateEventExp();
	}


	/* РАЗДЕЛЕНИЕ НА ИМЯ / ФАМИЛИЮ */
	/*
	public function updateFirstLastName() {
		$query_exp = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor v");

		foreach($query_exp->rows as $row) {
			$name = explode(' ', $row['name'], 2);
			$firstname = $name[0];
			$lastname = !empty($name[1]) ? $name[1] : '';

			$this->db->query("UPDATE " . DB_PREFIX . "visitor SET
				firstname = '" . $this->db->escape($firstname) . "',
				lastname = '" . $this->db->escape($lastname) . "'
				WHERE
				visitor_id = '" . (int)$row['visitor_id'] . "'
				");
		}
	}
	*/

}
