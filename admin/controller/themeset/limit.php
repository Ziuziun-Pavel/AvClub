<?php
class ControllerThemesetLimit extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('themeset/limit');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('av_limit', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('av_limit');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}		

		$heading_title = $this->language->get('heading_title');

		$data['heading_title'] = $heading_title;


		$data['action'] = $this->url->link('themeset/limit', 'token=' . $this->session->data['token'], true);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('themeset/limit', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);


		/* LIMIT */
		$data['limit_list'] = array(
			'av_limit_news'			=> array('title'=>'Новости', 			'default'=>23, 'value'=>'', 'error'=>''),
			'av_limit_opinion'		=> array('title'=>'Мнения', 			'default'=>22, 'value'=>'', 'error'=>''),
			'av_limit_case'			=> array('title'=>'Кейсы', 				'default'=>13, 'value'=>'', 'error'=>''),
			'av_limit_article'		=> array('title'=>'Статьи', 			'default'=>23, 'value'=>'', 'error'=>''),
			'av_limit_video'			=> array('title'=>'Видео', 				'default'=>23, 'value'=>'', 'error'=>''),
			'av_limit_special'		=> array('title'=>'Спецпроекты', 	'default'=>23, 'value'=>'', 'error'=>''),
			'av_limit_master'		=> array('title'=>'Онлайн события', 	'default'=>23, 'value'=>'', 'error'=>''),
			'av_limit_tag'		=> array('title'=>'Тег', 	'default'=>22, 'value'=>'', 'error'=>''),
			'av_limit_search'		=> array('title'=>'Поиск', 	'default'=>24, 'value'=>'', 'error'=>''),
		);

		foreach($data['limit_list'] as $key=>$item) {
			if (isset($this->request->post[$key])) {
				$limit = $this->request->post[$key];
			} elseif (isset($setting_info[$key])) {
				$limit = $setting_info[$key];
			} else {
				$limit = $item['default'];
			}

			$data['limit_list'][$key]['value'] = $limit;
			$data['limit_list'][$key]['error'] = isset($this->error[$key]) ? $this->error[$key] : '';
		}
		/* # LIMIT */
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('themeset/limit', $data));

	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'themeset/limit')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$limit_list = array(
			'av_limit_news',
			'av_limit_opinion',
			'av_limit_case',
			'av_limit_article',
			'av_limit_video',
			'av_limit_special'
		);

		foreach($limit_list as $key) {
			if (!$this->request->post[$key]) {
				$this->error[$key] = $this->language->get('error_limit');
			}
		}

		if ($this->error) {
			$this->error['warning'] = $this->language->get('error_values');
		}

		return !$this->error;
	}

}
