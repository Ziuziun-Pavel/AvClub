<?php
class ControllerTagTag extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('tag/tag');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tag/tag');

		$this->getList();
	}

	public function add() {
		$this->load->language('tag/tag');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tag/tag');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_tag_tag->addTag($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->response->redirect($this->url->link('tag/tag', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('tag/tag');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tag/tag');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_tag_tag->editTag($this->request->get['tag_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('tag/tag', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('tag/tag');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tag/tag');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $tag_id) {
				$this->model_tag_tag->deleteTag($tag_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->response->redirect($this->url->link('tag/tag', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function setting() {
		
		$this->load->language('tag/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_meta()) {
			$this->model_setting_setting->editSetting('av_tag', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('tag/tag/setting', 'token=' . $this->session->data['token'], true));
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('av_tag');
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


		$data['action'] = $this->url->link('tag/tag/setting', 'token=' . $this->session->data['token'], true);


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
			'href' => $this->url->link('tag/tag/setting', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);


		/* META */
		$data['title'] = $heading_title;

		$data['meta'] = array();

		if (isset($this->request->post['av_tag'])) {
			$meta = $this->request->post['av_tag'];
		} elseif (isset($setting_info['av_tag'])) {
			$meta = $setting_info['av_tag'];
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

		$this->response->setOutput($this->load->view('tag/setting_meta', $data));
	}

	protected function getList() {

		$heading_title = $this->language->get('heading_title');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'td.title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('tag/tag', 'token=' . $this->session->data['token'] . $url, true)
		);



		$data['meta'] = $this->url->link('tag/tag/setting', 'token=' . $this->session->data['token'] . $url, true);
		$data['add'] = $this->url->link('tag/tag/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('tag/tag/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['tags'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);


		$tag_total = $this->model_tag_tag->getTotalTags($filter_data);


		$results = $this->model_tag_tag->getTags($filter_data);

		foreach ($results as $result) {
			$data['tags'][] = array(
				'tag_id'					=> $result['tag_id'],
				'title'          		=> $result['title'],
				'edit'          	 	=> $this->url->link('tag/tag/edit', 'token=' . $this->session->data['token'] . '&tag_id=' . $result['tag_id'] . $url, true)
			);

		}

		

		$data['heading_title'] = $heading_title;


		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('tag/tag', 'token=' . $this->session->data['token'] . '&sort=td.title' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $tag_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tag/tag', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($tag_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($tag_total - $this->config->get('config_limit_admin'))) ? $tag_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $tag_total, ceil($tag_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tag/tag_list', $data));
	}

	protected function getForm() {


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

		$heading_title = $this->language->get('heading_title');
		$data['heading_title'] = $heading_title;

		$data['text_form'] = !isset($this->request->get['tag_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$lang_list = array(
			'text_default',
			'text_enabled',
			'text_disabled',
			
			'entry_title',
			'entry_description',
			'entry_meta_title',
			'entry_meta_h1',
			'entry_meta_description',
			'entry_meta_keyword',
			'entry_keyword',
			'entry_store',
			'entry_status',
			'entry_layout',
			
			'help_keyword',
			
			'button_save',
			'button_cancel',

			'tab_general',
			'tab_data',
			'tab_design',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$error_list = array(
			'warning',
			'title',
			'description',
			'meta_title',
			'keyword',
		);
		foreach($error_list as $key) {
			if (isset($this->error[$key])) {
				$data['error_' . $key] = $this->error[$key];
			} else {
				$data['error_' . $key] = '';
			}
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('tag/tag', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['tag_id'])) {
			$data['action'] = $this->url->link('tag/tag/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('tag/tag/edit', 'token=' . $this->session->data['token'] . '&tag_id=' . $this->request->get['tag_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('tag/tag', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['tag_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tag_info = $this->model_tag_tag->getTag($this->request->get['tag_id']);
		}



		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['config_language_id'] = $this->config->get('config_language_id'); // SEO URL Generator

		$data['lang'] = $this->language->get('lang');

		if (isset($this->request->post['tag_description'])) {
			$data['tag_description'] = $this->request->post['tag_description'];
		} elseif (isset($this->request->get['tag_id'])) {
			$data['tag_description'] = $this->model_tag_tag->getTagDescriptions($this->request->get['tag_id']);
		} else {
			$data['tag_description'] = array();
		}

		$key_list = array(
			'keyword'			=> '',
			'status'			=> true
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($tag_info)) {
				$data[$key] = $tag_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		if (isset($this->request->post['tag_layout'])) {
			$data['tag_layout'] = $this->request->post['tag_layout'];
		} elseif (isset($this->request->get['tag_id'])) {
			$data['tag_layout'] = $this->model_tag_tag->getTagLayouts($this->request->get['tag_id']);
		} else {
			$data['tag_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tag/tag_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'tag/tag')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['tag_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 1) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			/*if (utf8_strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}*/
		}

		if(empty($this->request->post['keyword'])){
			$a_data = array(
				'name'    => $this->request->post['tag_description'][$this->config->get('config_language_id')]['title'],
				'essence' => 'tag',
			);

			$this->request->post['keyword'] = $this->load->controller('extension/module/seo_url_generator/getSeoUrl', $a_data); 
		} else {
			$this->load->model('extension/module/seo_url_generator');
			$this->request->post['keyword'] = $this->model_extension_module_seo_url_generator->translit($this->request->post['keyword']);
		}
		// SEO URL Generator . end

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['tag_id']) && $url_alias_info['query'] != 'tag_id=' . $this->request->get['tag_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['tag_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'tag/tag')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}


	protected function validate_meta() {
		if (!$this->user->hasPermission('modify', 'tag/tag')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['av_tag']['bread'])) {
			$this->error['bread'] = $this->language->get('error_bread');
		}

		if (empty($this->request->post['av_tag']['meta_h1'])) {
			$this->error['meta_h1'] = $this->language->get('error_meta_h1');
		}

		return !$this->error;
	}

	public function existTag() {
		$json = array();
		$this->load->language('tag/tag');

		if (isset($this->request->get['filter_item'])) {
			$filter_item = $this->request->get['filter_item'];

			$this->load->model('tag/tag');

			$filter_data = array(
				'filter_item'  => $filter_item,
				'start'        => 0,
				'limit'        => 5
			);

			$tag_info = $this->model_tag_tag->getTagByName($filter_item);

			if($tag_info) {
				$json['exist'] = true;
				$json['tag'] = array(
					'tag_id'	=> $tag_info['tag_id'],
					'tag'			=> $tag_info['title'],
				);
			}else{
				$json['exist'] = false;
			}

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_tag'])) {
			$filter_tag = $this->request->get['filter_tag'];

			$this->load->model('tag/tag');

			$filter_data = array(
				'filter_tag'  => $filter_tag,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_tag_tag->getTags($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'tag_id'       => $result['tag_id'],
					'tag'         => $result['title']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}