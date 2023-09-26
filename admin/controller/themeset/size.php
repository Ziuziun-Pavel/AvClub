<?php
class ControllerThemesetSize extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('themeset/size');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('av_size', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('av_size');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}		

		$heading_title = $this->language->get('heading_title');

		$data['heading_title'] = $heading_title;


		$data['action'] = $this->url->link('themeset/size', 'token=' . $this->session->data['token'], true);

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
			'href' => $this->url->link('themeset/size', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);

		/* SIZE */
		$data['size_list'] = array(
			'av_size_news'			=> array('title'=>'Новости', 			'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
			'av_size_opinion'	=> array('title'=>'Мнения', 			'width'=>'','height'=>'','w'=>220,'h'=>220, 'error'=>''),
			'av_size_case'			=> array('title'=>'Кейсы', 				'width'=>'','height'=>'','w'=>730,'h'=>472, 'error'=>''),
			'av_size_article'	=> array('title'=>'Статьи', 			'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
			'av_size_video'		=> array('title'=>'Видео', 				'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
			'av_size_special'	=> array('title'=>'Спецпроекты', 	'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
			'av_size_master'	=> array('title'=>'Онлайн события', 	'width'=>'','height'=>'','w'=>300,'h'=>300, 'error'=>''),
		);

		foreach($data['size_list'] as $key=>$item) {
			if (isset($this->request->post[$key . '_width'])) {
				$width = $this->request->post[$key . '_width'];
			} elseif (isset($setting_info[$key . '_width'])) {
				$width = $setting_info[$key . '_width'];
			} else {
				$width = $item['w'];
			}
			if (isset($this->request->post[$key . '_height'])) {
				$height = $this->request->post[$key . '_height'];
			} elseif (isset($setting_info[$key . '_height'])) {
				$height = $setting_info[$key . '_height'];
			} else {
				$height = $item['h'];
			}

			$data['size_list'][$key]['width'] = $width;
			$data['size_list'][$key]['height'] = $height;

			$data['size_list'][$key]['error'] = isset($this->error[$key]) ? $this->error[$key] : '';
		}
		/* # SIZE */
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('themeset/size', $data));

	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'themeset/size')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$size_list = array(
			'av_size_news',
			'av_size_opinion',
			'av_size_case',
			'av_size_article',
			'av_size_video',
			'av_size_special',
			'av_size_master',
		);
		foreach($size_list as $key) {
			if (!$this->request->post[$key . '_width'] || !$this->request->post[$key . '_height']) {
				$this->error[$key] = $this->language->get('error_size');
			}
		}

		if ($this->error) {
			$this->error['warning'] = $this->language->get('error_values');
		}

		return !$this->error;
	}


}
