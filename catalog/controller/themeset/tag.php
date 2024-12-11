<?php
class ControllerThemesetTag extends Controller {

	private $b24_hook = 'https://avclub.bitrix24.ru/rest/677/hgv4fvnz8xdrqk2k/';
	private $activity_key = 'UF_CRM_1641805321009';


	public function getTagList() {
		$bitrixWebHook = $this->b24_hook;
		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$this->load->model('themeset/tag');

		$list_id = !empty($this->request->get['list_id']) ? $this->request->get['list_id'] : 0;
		$start = !empty($this->request->get['start']) ? $this->request->get['start'] : 0;

		if(!$list_id) {
			echo 'Укажите list_id';
			return;
		}

		$result = CRest::call(
			'lists.element.get',
			[
				"IBLOCK_TYPE_ID" 	=> "lists",
				"IBLOCK_ID"				=> $list_id,
				"start"						=> $start,
			]
		);

		echo '<pre>';
		print_r($result);
		echo '</pre>';

		foreach($result['result'] as $tag_item) {
			$tag_info = array(
				'group_id'	=> $tag_item['IBLOCK_ID'],
				'tag_id'		=> $tag_item['ID'],
				'tag'				=> $tag_item['NAME'],
			);
			$this->model_themeset_tag->updateTag($tag_info);
		} 
	}

	public function updateTag() {
		header('Content-Type: application/json');
		$data = json_decode(file_get_contents('php://input'), true);

		$this->load->model('themeset/tag');
		$this->load->model('themeset/themeset');

		if(!empty($data['groupId']) && !empty($data['tagId']) && !empty($data['tagName'])) {
			$tag_info = array(
				'group_id'	=> $data['groupId'],
				'tag_id'		=> $data['tagId'],
				'tag'				=> $data['tagName'],
			);
			$this->model_themeset_tag->updateTag($tag_info);
		}else{
			$message = array(
				'alert'	=> 'ошибка обновления тега списка',
				'date'	=> $data
			);
			$this->model_themeset_themeset->alert($message);
		}


	}

	public function updateActivity() {

		$this->load->model('themeset/tag');

		$post = $this->request->post;

		if(!empty($post['event']) && $post['event'] === 'ONCRMCOMPANYUSERFIELDSETENUMVALUES') {
			$field_id = $post['data']['FIELDS']['FIELD_NAME'];
			if($field_id === $this->activity_key) {

				$bitrixWebHook = $this->b24_hook;
				require_once(DIR_SYSTEM . 'library/crest/crest.php');

				$result = CRest::call(
					'crm.company.fields',
					[]
				);

				$activity_list = array();
				if(!empty($result['result'][$this->activity_key]['items'])) {
					foreach($result['result'][$this->activity_key]['items'] as $key=>$value) {
						$activity_list[] = array(
							'tag_id'	=> $value['ID'],
							'tag'			=> $value['VALUE']
						);	
					}
					$this->model_themeset_tag->updateActivity($activity_list);
				}


			}
		}

	}

	

}
