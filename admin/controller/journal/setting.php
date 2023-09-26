<?php
class ControllerJournalSetting extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('journal/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_meta()) {
			$this->model_setting_setting->editSetting('journal_meta', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('journal_meta');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		//CKEditor
		if ($this->config->get('config_editor_default')) {
			$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
			$this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
		} else {
			$this->document->addScript('view/javascript/summernote/summernote.js');
			$this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
			$this->document->addScript('view/javascript/summernote/opencart.js');
			$this->document->addStyle('view/javascript/summernote/summernote.css');
		}

		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

		
		$heading_title =  $this->language->get('heading_title');

		$data['heading_title'] = $heading_title;

		$lang_list = array(
			'entry_description',
			'entry_meta_h1',
			'entry_meta_title',
			'entry_meta_description',
			'entry_meta_keyword',
			'button_save',
			'button_cancel',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$data['action'] = $this->url->link('journal/setting', 'token=' . $this->session->data['token'], true);

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
			'href' => $this->url->link('journal/setting', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);


		/* META */
		$data['meta_list'] = array(
			'journal_meta_news'		=> array('title'=>'Новости', 				'meta'=>array()),
			'journal_meta_opinion'	=> array('title'=>'Мнения', 			'meta'=>array()),
			'journal_meta_case'		=> array('title'=>'Кейсы', 					'meta'=>array()),
			'journal_meta_article'	=> array('title'=>'Статьи', 			'meta'=>array()),
			'journal_meta_video'		=> array('title'=>'Видео', 				'meta'=>array()),
			'journal_meta_special'	=> array('title'=>'Спецпроекты', 	'meta'=>array()),
			'journal_meta_tag'			=> array('title'=>'Теги', 				'meta'=>array())
		);

		foreach($data['meta_list'] as $key=>$item) {
			if (isset($this->request->post[$key])) {
				$meta = $this->request->post[$key];
			} elseif (isset($setting_info[$key])) {
				$meta = $setting_info[$key];
			} else {
				$meta = array(
					'description'				=>	'',
					'meta_title'				=>	'',
					'meta_h1'						=>	'',
					'meta_description'	=>	'',
					'meta_keyword'			=>	''
				);
			}

			$data['meta_list'][$key]['meta'] = $meta;
		}
		/* # META */

		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('journal/setting_meta', $data));

	}

	public function news() {
		$this->getSetting('news');
	}

	public function opinion() {
		$this->getSetting('opinion');
	}

	public function case() {
		$this->getSetting('case');
	}

	public function article() {
		$this->getSetting('article');
	}

	public function video() {
		$this->getSetting('video');
	}

	public function special() {
		$this->getSetting('special');
	}

	protected function getSetting($type = 'article') {
		
		$this->load->language('journal/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_meta($type)) {
			$this->model_setting_setting->editSetting('av_meta_' . $type, $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$type = !empty($this->request->post['type']) ? $this->request->post['type'] : 'article';

			$this->response->redirect($this->url->link('journal/setting/' . $type, 'token=' . $this->session->data['token'], true));
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('av_meta_' . $type);
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		//CKEditor
		if ($this->config->get('config_editor_default')) {
			$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
			$this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
		} else {
			$this->document->addScript('view/javascript/summernote/summernote.js');
			$this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
			$this->document->addScript('view/javascript/summernote/opencart.js');
			$this->document->addStyle('view/javascript/summernote/summernote.css');
		}

		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

		
		$heading_title =  $this->language->get('heading_title_' . $type);

		$data['heading_title'] = $heading_title;

		$lang_list = array(
			'entry_description',
			'entry_bread',
			'entry_meta_h1',
			'entry_meta_title',
			'entry_meta_description',
			'entry_meta_keyword',
			'button_save',
			'button_cancel',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$data['action'] = $this->url->link('journal/setting/' . $type, 'token=' . $this->session->data['token'], true);


		$error_list = array(
			'warning',
			'bread',
			'meta_h1',
		);
		foreach($error_list as $key) {
			if (isset($this->error[$key])) {
				$data['error_' . $key] = $this->error[$key];
			} else {
				$data['error_' . $key] = '';
			}
		}

		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('journal/setting/' . $type, 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);


		/* META */
		$data['type'] = $type;
		$data['title'] = $heading_title;

		$data['meta'] = array();

		if (isset($this->request->post['av_meta_' . $type])) {
			$meta = $this->request->post['av_meta_' . $type];
		} elseif (isset($setting_info['av_meta_' . $type])) {
			$meta = $setting_info['av_meta_' . $type];
		} else {
			$meta = array(
				'description'				=>	'',
				'bread'							=>	'',
				'meta_title'				=>	'',
				'meta_h1'						=>	'',
				'meta_description'	=>	'',
				'meta_keyword'			=>	''
			);
		}

		$data['meta'] = $meta;
		/* # META */

		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('journal/setting_meta', $data));
	}

	public function banner() {
		$this->load->language('journal/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('journal_banner', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('journal_banner');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}		

		$heading_title = $this->language->get('heading_title_banner');

		$data['heading_title'] = $heading_title;


		$data['action'] = $this->url->link('journal/setting/banner', 'token=' . $this->session->data['token'], true);

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
			'href' => $this->url->link('journal/setting/banner', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);

		$lang_list = array(
			'entry_link',
			'entry_image',
			'button_save',
			'button_cancel',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$key_list = array(
			'journal_banner_image'	=> '',
			'journal_banner_link'		=> ''
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($setting_info)) {
				$data[$key] = $setting_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['journal_banner_image']) && is_file(DIR_IMAGE . $this->request->post['journal_banner_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['journal_banner_image'], 100, 100);
		} elseif (!empty($setting_info) && is_file(DIR_IMAGE . $setting_info['journal_banner_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($setting_info['journal_banner_image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('journal/setting_banner', $data));

	}

	public function telegram() {
		$this->load->language('journal/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('journal_telegram', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('journal_telegram');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}		

		$heading_title = $this->language->get('heading_title_telegram');

		$data['heading_title'] = $heading_title;


		$data['action'] = $this->url->link('journal/setting/telegram', 'token=' . $this->session->data['token'], true);

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
			'href' => $this->url->link('journal/setting/banner', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);

		$lang_list = array(
			'entry_link',
			'entry_text',
			'entry_status',
			'button_save',
			'button_cancel',
			'text_enabled',
			'text_disabled',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$key_list = array(
			'journal_telegram_status'	=> 1,
			'journal_telegram_link'		=> '',
			'journal_telegram_text'		=> ''
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($setting_info)) {
				$data[$key] = $setting_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('journal/setting_telegram', $data));

	}



	protected function validate() {
		if (!$this->user->hasPermission('modify', 'journal/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	protected function validate_meta($type = 'article') {
		if (!$this->user->hasPermission('modify', 'journal/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['av_meta_' . $type]['bread'])) {
			$this->error['bread'] = $this->language->get('error_bread');
		}

		if (empty($this->request->post['av_meta_' . $type]['meta_h1'])) {
			$this->error['meta_h1'] = $this->language->get('error_meta_h1');
		}

		return !$this->error;
	}


}