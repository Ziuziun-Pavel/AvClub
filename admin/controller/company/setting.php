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

	public function limit() {
		$this->load->language('journal/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_limit()) {
			$this->model_setting_setting->editSetting('journal_limit', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('journal_limit');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}		

		$heading_title = $this->language->get('heading_title_limit');

		$data['heading_title'] = $heading_title;


		$data['action'] = $this->url->link('journal/setting/limit', 'token=' . $this->session->data['token'], true);

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
			'href' => $this->url->link('journal/setting/limit', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);


		/* LIMIT */
		$data['limit_list'] = array(
			'journal_limit_news'			=> array('title'=>'Новости', 			'default'=>23, 'value'=>'', 'error'=>''),
			'journal_limit_opinion'		=> array('title'=>'Мнения', 			'default'=>22, 'value'=>'', 'error'=>''),
			'journal_limit_case'			=> array('title'=>'Кейсы', 				'default'=>13, 'value'=>'', 'error'=>''),
			'journal_limit_article'		=> array('title'=>'Статьи', 			'default'=>23, 'value'=>'', 'error'=>''),
			'journal_limit_video'			=> array('title'=>'Видео', 				'default'=>23, 'value'=>'', 'error'=>''),
			'journal_limit_special'		=> array('title'=>'Спецпроекты', 	'default'=>23, 'value'=>'', 'error'=>''),
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

		$this->response->setOutput($this->load->view('journal/setting_limit', $data));

	}

	public function size() {
		$this->load->language('journal/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_size()) {
			$this->model_setting_setting->editSetting('journal_size', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('journal_size');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}		

		$heading_title = $this->language->get('heading_title_size');

		$data['heading_title'] = $heading_title;


		$data['action'] = $this->url->link('journal/setting/size', 'token=' . $this->session->data['token'], true);

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
			'href' => $this->url->link('journal/setting/size', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);

		/* SIZE */
		$data['size_list'] = array(
			'journal_size_news'			=> array('title'=>'Новости', 			'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
			'journal_size_opinion'	=> array('title'=>'Мнения', 			'width'=>'','height'=>'','w'=>220,'h'=>220, 'error'=>''),
			'journal_size_case'			=> array('title'=>'Кейсы', 				'width'=>'','height'=>'','w'=>730,'h'=>472, 'error'=>''),
			'journal_size_article'	=> array('title'=>'Статьи', 			'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
			'journal_size_video'		=> array('title'=>'Видео', 				'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
			'journal_size_special'	=> array('title'=>'Спецпроекты', 	'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
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

		$this->response->setOutput($this->load->view('journal/setting_size', $data));

	}
	public function setting() {
		$this->load->language('journal/journal');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('journal', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('journal');
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

		

		$data['heading_title'] = $this->language->get('heading_title_setting');

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


		$data['action'] = $this->url->link('journal/journal/setting', 'token=' . $this->session->data['token'], true);

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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('journal/journal/setting', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);


		/* META */
		$data['meta_list'] = array(
			'journal_news_meta'		=> array('title'=>'Новости', 				'meta'=>array()),
			'journal_opinion_meta'	=> array('title'=>'Мнения', 			'meta'=>array()),
			'journal_case_meta'		=> array('title'=>'Кейсы', 					'meta'=>array()),
			'journal_article_meta'	=> array('title'=>'Статьи', 			'meta'=>array()),
			'journal_video_meta'		=> array('title'=>'Видео', 				'meta'=>array()),
			'journal_special_meta'	=> array('title'=>'Спецпроекты', 	'meta'=>array()),
			'journal_tag_meta'			=> array('title'=>'Теги', 				'meta'=>array())
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

		/* LIMIT */
		$data['limit_list'] = array(
			'journal_news_limit'			=> array('title'=>'Новости', 			'default'=>23, 'value'=>'', 'error'=>''),
			'journal_opinion_limit'		=> array('title'=>'Мнения', 			'default'=>22, 'value'=>'', 'error'=>''),
			'journal_case_limit'			=> array('title'=>'Кейсы', 				'default'=>13, 'value'=>'', 'error'=>''),
			'journal_article_limit'		=> array('title'=>'Статьи', 			'default'=>23, 'value'=>'', 'error'=>''),
			'journal_video_limit'			=> array('title'=>'Видео', 				'default'=>23, 'value'=>'', 'error'=>''),
			'journal_special_limit'		=> array('title'=>'Спецпроекты', 	'default'=>23, 'value'=>'', 'error'=>''),
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


		/* SIZE */
		$data['size_list'] = array(
			'journal_news_size'			=> array('title'=>'Новости', 			'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
			'journal_opinion_size'	=> array('title'=>'Мнения', 			'width'=>'','height'=>'','w'=>220,'h'=>220, 'error'=>''),
			'journal_case_size'			=> array('title'=>'Кейсы', 				'width'=>'','height'=>'','w'=>730,'h'=>472, 'error'=>''),
			'journal_article_size'	=> array('title'=>'Статьи', 			'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
			'journal_video_size'		=> array('title'=>'Видео', 				'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
			'journal_special_size'	=> array('title'=>'Спецпроекты', 	'width'=>'','height'=>'','w'=>730,'h'=>495, 'error'=>''),
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

		$this->response->setOutput($this->load->view('journal/setting', $data));

	}


	protected function validate() {
		if (!$this->user->hasPermission('modify', 'journal/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	protected function validate_meta() {
		if (!$this->user->hasPermission('modify', 'journal/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validate_limit() {
		if (!$this->user->hasPermission('modify', 'journal/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$limit_list = array(
			'journal_limit_news',
			'journal_limit_opinion',
			'journal_limit_case',
			'journal_limit_article',
			'journal_limit_video',
			'journal_limit_special'
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
	protected function validate_size() {
		if (!$this->user->hasPermission('modify', 'journal/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$size_list = array(
			'journal_size_news',
			'journal_size_opinion',
			'journal_size_case',
			'journal_size_article',
			'journal_size_video',
			'journal_size_special'
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