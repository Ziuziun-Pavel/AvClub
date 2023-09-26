<?php
class ControllerCompanyCompany extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('company/company');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('company/company');

		$this->getList();
	}

	public function add() {
		$this->load->language('company/company');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('company/company');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_company_company->addCompany($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->response->redirect($this->url->link('company/company', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('company/company');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('company/company');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_company_company->editCompany($this->request->get['company_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('company/company', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('company/company');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('company/company');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $company_id) {
				$this->model_company_company->deleteCompany($company_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->response->redirect($this->url->link('company/company', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function setting() {
		
		$this->load->language('company/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_meta()) {
			$this->model_setting_setting->editSetting('av_company', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('company/company/setting', 'token=' . $this->session->data['token'], true));
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('av_company');
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


		$data['action'] = $this->url->link('company/company/setting', 'token=' . $this->session->data['token'], true);


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
			'href' => $this->url->link('company/company/setting', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);


		/* META */
		$data['title'] = $heading_title;

		$data['meta'] = array();

		if (isset($this->request->post['av_company'])) {
			$meta = $this->request->post['av_company'];
		} elseif (isset($setting_info['av_company'])) {
			$meta = $setting_info['av_company'];
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

		$this->response->setOutput($this->load->view('company/setting_meta', $data));
	}

	protected function getList() {

		$heading_title = $this->language->get('heading_title');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cd.title';
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

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

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
			'href' => $this->url->link('company/company', 'token=' . $this->session->data['token'] . $url, true)
		);



		$data['meta'] = $this->url->link('company/company/setting', 'token=' . $this->session->data['token'] . $url, true);
		$data['add'] = $this->url->link('company/company/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('company/company/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['companies'] = array();

		$filter_data = array(
			'filter_name'  => $filter_name,
			'filter_status'  => $filter_status,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);


		$company_total = $this->model_company_company->getTotalCompanies($filter_data);


		$results = $this->model_company_company->getCompanies($filter_data);

		foreach ($results as $result) {
			$data['companies'][] = array(
				'company_id'					=> $result['company_id'],
				'status'							=> $result['status'],
				'title'          			=> $result['title'],
				'update_link'					=> !empty($result['b24id']) ? HTTPS_CATALOG . 'index.php?route=themeset/companies/getCompanyById&id=' . $result['b24id'] . '&show_info=0&show_result=1': '',
				'view'           			=> HTTPS_CATALOG . 'index.php?route=company/info&company_id=' . $result['company_id'],
				'edit'          	 		=> $this->url->link('company/company/edit', 'token=' . $this->session->data['token'] . '&company_id=' . $result['company_id'] . $url, true)
			);

		}
		

		$data['heading_title'] = $heading_title;

		$lang_list = array(
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_enabled',
			'text_disabled',
			
			'entry_title',
			'entry_status',

			'column_title',
			'column_action',

			'button_add',
			'button_edit',
			'button_delete',
			'button_view',
			'button_filter',

		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$data['token'] = $this->session->data['token'];

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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('company/company', 'token=' . $this->session->data['token'] . '&sort=cd.title' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $company_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('company/company', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($company_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($company_total - $this->config->get('config_limit_admin'))) ? $company_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $company_total, ceil($company_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['filter_name'] = $filter_name;
		$data['filter_status'] = $filter_status;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('company/company_list', $data));
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

		$this->document->addScript('view/javascript/jquery-ui.sortable/jquery-ui.min.js');

		$heading_title = $this->language->get('heading_title');
		$data['heading_title'] = $heading_title;

		$data['text_form'] = !isset($this->request->get['company_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$lang_list = array(
			'text_default',
			'text_enabled',
			'text_disabled',
			'text_none',
			
			'entry_title',
			'entry_alternate',
			'entry_image',
			'entry_phone',
			'entry_site',
			'entry_email',
			'entry_social',
			'entry_category',
			'entry_social',
			'entry_preview',
			'entry_description',
			'entry_meta_title',
			'entry_meta_h1',
			'entry_meta_description',
			'entry_meta_keyword',
			'entry_keyword',
			'entry_status',
			'entry_sort_order',
			'entry_event',
			'entry_event_placeholder',
			'entry_brand',
			'entry_brand_placeholder',
			'entry_branch',
			'entry_branch_placeholder',
			'entry_tag',
			'entry_tag_main',
			'entry_tag_placeholder',
			'entry_gallery',
			
			'help_keyword',
			'help_sort_order',
			
			'button_save',
			'button_cancel',

			'tab_general',
			'tab_data',
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

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
			'href' => $this->url->link('company/company', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['company_id'])) {
			$data['action'] = $this->url->link('company/company/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('company/company/edit', 'token=' . $this->session->data['token'] . '&company_id=' . $this->request->get['company_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('company/company', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['company_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$company_info = $this->model_company_company->getCompany($this->request->get['company_id']);
		}



		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['config_language_id'] = $this->config->get('config_language_id'); // SEO URL Generator

		$data['lang'] = $this->language->get('lang');

		if (isset($this->request->post['company_description'])) {
			$data['company_description'] = $this->request->post['company_description'];
		} elseif (isset($this->request->get['company_id'])) {
			$data['company_description'] = $this->model_company_company->getCompanyDescriptions($this->request->get['company_id']);
		} else {
			$data['company_description'] = array();
		}

		$key_list = array(
			'keyword'			=> '',
			'image'				=> '',
			'category_id'		=> 0,
			'tag_id'			=> '',
			'phone_1'			=> '',
			'phone_2'			=> '',
			'site'				=> '',
			'email'				=> '',
			'status'			=> 1,
			'sort_order'	=> 0
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} else if (isset($company_info[$key])) {
				$data[$key] = $company_info[$key];
			} else {
				$data[$key] = $default;
			}
		}


		$data['main_tag'] = array();
		if($data['tag_id']) {
			$this->load->model('tag/tag');
			$tag_info = $this->model_tag_tag->getTagById($data['tag_id']);
			if($tag_info) {
				$data['main_tag'] = array(
					'tag_id'	=> $tag_info['tag_id'],
					'tag'			=> $tag_info['title']
				);
			}
		}


		// tags
		$data['tags_new'] = array();
		if(!empty($this->request->post['tag_new'])) {
			foreach($this->request->post['tag_new'] as $tag_name) {
				$data['tags_new'][] = array(
					'tag_id'	=> 0,
					'tag'			=> $tag_name
				);
			}
		}
		$data['tags'] = array();
		if (isset($this->request->post['tag']) ) {
			$tags = $this->request->post['tag'];
			if($tags) {
				$this->load->model('tag/tag');
				foreach($tags as $tag_id) {
					$tag_info = $this->model_tag_tag->getTagById($tag_id);
					if($tag_info) {
						$data['tags'][] = array(
							'tag_id'	=> $tag_id,
							'tag'			=> $tag_info['title']
						);
					}
				}
			}
		}elseif (!empty($company_info)){
			$data['tags'] = $this->model_company_company->getCompanyTags($this->request->get['company_id']);
		}

		// brands
		$data['brands_new'] = array();
		if(!empty($this->request->post['brand_new'])) {
			foreach($this->request->post['brand_new'] as $brand_name) {
				$data['brands_new'][] = array(
					'brand_id'	=> 0,
					'brand'			=> $brand_name
				);
			}
		}
		$data['brands'] = array();
		if (isset($this->request->post['brand']) ) {
			$brands = $this->request->post['brand'];
			if($brands) {
				$this->load->model('company/brand');
				foreach($brands as $brand_id) {
					$brand_info = $this->model_company_brand->getBrand($brand_id);
					if($brand_info) {
						$data['brands'][] = array(
							'brand_id'	=> $brand_id,
							'brand'			=> $brand_info['title']
						);
					}
				}
			}
		}elseif (!empty($company_info)){
			$data['brands'] = $this->model_company_company->getCompanyBrands($this->request->get['company_id']);
		}


		// branches
		$data['branches_new'] = array();
		if(!empty($this->request->post['branch_new'])) {
			foreach($this->request->post['branch_new'] as $branch_name) {
				$data['branches_new'][] = array(
					'branch_id'	=> 0,
					'branch'			=> $branch_name
				);
			}
		}
		$data['branches'] = array();
		if (isset($this->request->post['branch']) ) {
			$branches = $this->request->post['branch'];
			if($branches) {
				$this->load->model('company/branch');
				foreach($branches as $branch_id) {
					// $branch_info = $this->model_company_branch->getBranch($branch_id);
					$branch_info = $this->model_tag_tag->getTagById($tag_id);
					$this->load->model('tag/tag');
					if($branch_info) {
						$data['branches'][] = array(
							'branch_id'	=> $branch_id,
							'branch'		=> $branch_info['title']
						);
					}
				}
			}
		}elseif (!empty($company_info)){
			$data['branches'] = $this->model_company_company->getCompanyBranches($this->request->get['company_id']);
		}

		if(isset($this->request->get['company_id'])) {
			$data['social'] = $this->model_company_company->getCompanySocial($this->request->get['company_id']);
		}else{
			$data['social'] = array();
		}
		

		$data['social_type'] = array(
			'FACEBOOK'	=> 'Facebook',
			'TELEGRAM'	=> 'Telegram',
			'VK'				=> 'Вконтакте',
			'SKYPE'			=> 'Skype',
			'VIBER'			=> 'Viber',
			'INSTAGRAM'	=> 'Instagram',
			'BITRIX24'	=> 'Битрикс24.NETWORK',
			'OPENLINE'	=> 'Онлайн-чат',
			'IMOL'			=> 'Открытая линия',
			'ICQ'				=> 'ICQ',
			'MSN'				=> 'MSN/LIVE!',
			'JABBER'		=> 'Jabber',
			'OTHER'			=> 'Другой',
		);

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($company_info) && is_file(DIR_IMAGE . $company_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($company_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$this->load->model('company/category');
		$data['categories'] = $this->model_company_category->getCategories();

		// gallery
		$data['gallery'] = array();
		if (isset($this->request->post['gallery'])) {
			$gallery = $this->request->post['gallery'];
		}elseif(!empty($company_info)){
			$gallery = $this->model_company_company->getGallery($this->request->get['company_id']);
		}else{
			$gallery = array();
		}
		if($gallery) {

			foreach($gallery as $image) {
				if ($image && is_file(DIR_IMAGE . $image)) {
					$thumb = $this->model_tool_image->resize($image, 100, 100);
				} else {
					$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
				}

				$data['gallery'][] = array(
					'thumb'	=> $thumb,
					'image'	=> $image,
				);
			}

		}

	// events
		$data['events'] = array();
		$events = array();
		if (isset($this->request->post['event'])) {
			$events = $this->request->post['event'];
		}elseif (!empty($company_info)){
			$events = $this->model_company_company->getEventsByCompany($this->request->get['company_id']);
		}
		if($events) {
			$this->load->model('avevent/event');
			foreach($events as $event_id) {
				$event = $this->model_avevent_event->getEvent($event_id);
				if($event) {
					$data['events'][] = array(
						'event_id'	=> $event_id,
						'title'				=> $event['date'] . ' / ' . $event['type'] . ' / ' . $event['city'] . ' / ' . $event['title']
					);
				}
			}
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('company/company_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'company/company')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['company_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 1) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			/*if (utf8_strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}*/
		}

		if(empty($this->request->post['keyword'])){
			$a_data = array(
				'name'    => $this->request->post['company_description'][$this->config->get('config_language_id')]['title'],
				'essence' => 'company',
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

			if ($url_alias_info && isset($this->request->get['company_id']) && $url_alias_info['query'] != 'company_id=' . $this->request->get['company_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['company_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'company/company')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}


	protected function validate_meta() {
		if (!$this->user->hasPermission('modify', 'company/company')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['av_company']['bread'])) {
			$this->error['bread'] = $this->language->get('error_bread');
		}

		if (empty($this->request->post['av_company']['meta_h1'])) {
			$this->error['meta_h1'] = $this->language->get('error_meta_h1');
		}

		return !$this->error;
	}

	public function existCompany() {
		$json = array();
		$this->load->language('company/company');

		if (isset($this->request->get['filter_company'])) {
			$filter_company = $this->request->get['filter_company'];

			$this->load->model('company/company');

			$filter_data = array(
				'filter_company'  => $filter_company,
				'start'        => 0,
				'limit'        => 5
			);

			$company_info = $this->model_company_company->getCompanyByName($filter_company);

			if($company_info) {
				$json['exist'] = true;
				$json['company'] = array(
					'company_id'	=> $company_info['company_id'],
					'company'			=> $company_info['title'],
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

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];

			$this->load->model('company/company');

			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_company_company->getCompanies($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'company_id'      => $result['company_id'],
					'name'         => $result['title']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}