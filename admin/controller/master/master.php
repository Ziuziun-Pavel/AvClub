<?php
class ControllerMasterMaster extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('master/master');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('master/master');

		$this->getList();
	}

	public function add() {
		$this->load->language('master/master');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('master/master');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_master_master->addMaster($this->request->post);

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


			$this->response->redirect($this->url->link('master/master', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('master/master');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('master/master');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_master_master->editMaster($this->request->get['master_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('master/master', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('master/master');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('master/master');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $master_id) {
				$this->model_master_master->deleteMaster($master_id);
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


			$this->response->redirect($this->url->link('master/master', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function setting() {
		
		$this->load->language('master/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_meta()) {
			$this->model_setting_setting->editSetting('av_master', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('master/master/setting', 'token=' . $this->session->data['token'], true));
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('av_master');
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
			'entry_title',
			'entry_description',
			'entry_bread',
			'entry_meta_h1',
			'entry_meta_title',
			'entry_meta_description',
			'entry_meta_keyword',
			'entry_link',
			'entry_button',

			'tab_meta',
			'tab_master',

			'button_save',
			'button_cancel',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$data['action'] = $this->url->link('master/master/setting', 'token=' . $this->session->data['token'], true);


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
			'href' => $this->url->link('master/master/setting', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);


		/* META */
		$data['title'] = $heading_title;

		$data['meta'] = array();

		if (isset($this->request->post['av_master'])) {
			$meta = $this->request->post['av_master'];
		} elseif (isset($setting_info['av_master'])) {
			$meta = $setting_info['av_master'];
		} else {
			$meta = array(
				'master_title'				=>	'',
				'master_description'				=>	'',
				'master_button'				=>	'',
				'master_link'				=>	'',
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

		$this->response->setOutput($this->load->view('master/setting_meta', $data));
	}

	protected function getList() {

		$heading_title = $this->language->get('heading_title');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'm.date_available';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
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
			'href' => $this->url->link('master/master', 'token=' . $this->session->data['token'] . $url, true)
		);



		$data['setting'] = $this->url->link('master/master/setting', 'token=' . $this->session->data['token'] . $url, true);
		$data['add'] = $this->url->link('master/master/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('master/master/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['masters'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);


		$master_total = $this->model_master_master->getTotalMasters($filter_data);


		$results = $this->model_master_master->getMasters($filter_data);

		foreach ($results as $result) {
			$data['masters'][] = array(
				'master_id'					=> $result['master_id'],
				'title'          		=> $result['title'],
				'status'          		=> $result['status'],
				'finished'          => strtotime($result['date_available']) < time() ? true : false,
				'date_available'		=> $result['date_available'],
				'edit'          	 	=> $this->url->link('master/master/edit', 'token=' . $this->session->data['token'] . '&master_id=' . $result['master_id'] . $url, true)
			);

		}


		$data['heading_title'] = $heading_title;

		$lang_list = array(
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_enabled',
			'text_disabled',

			'column_date_available',
			'column_title',
			'column_status',
			'column_action',

			'button_add',
			'button_edit',
			'button_view'
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}
		

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

		$data['sort_title'] = $this->url->link('master/master', 'token=' . $this->session->data['token'] . '&sort=md.title' . $url, true);
		$data['sort_date_available'] = $this->url->link('master/master', 'token=' . $this->session->data['token'] . '&sort=m.date_available' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $master_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('master/master', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($master_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($master_total - $this->config->get('config_limit_admin'))) ? $master_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $master_total, ceil($master_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('master/master_list', $data));
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

		$data['text_form'] = !isset($this->request->get['master_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$lang_list = array(
			'text_default',
			'text_enabled',
			'text_disabled',
			
			'entry_image',
			'entry_type',
			'entry_logo',
			'entry_title',
			'entry_description',
			'entry_preview',
			'entry_meta_title',
			'entry_meta_h1',
			'entry_meta_description',
			'entry_meta_keyword',
			'entry_keyword',
			'entry_store',
			'entry_status',
			'entry_layout',
			'entry_date_available',
			'entry_author',
			'entry_author_placeholder',
			'entry_experts',
			'entry_experts_placeholder',
			'entry_link',
			'entry_company',
			'entry_company_placeholder',
            'entry_tag',
            'entry_tag_placeholder',
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
            'link',
			'author',
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
			'href' => $this->url->link('master/master', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['master_id'])) {
			$data['action'] = $this->url->link('master/master/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('master/master/edit', 'token=' . $this->session->data['token'] . '&master_id=' . $this->request->get['master_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('master/master', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['master_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$master_info = $this->model_master_master->getMaster($this->request->get['master_id']);
		}

		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['config_language_id'] = $this->config->get('config_language_id'); // SEO URL Generator

		$data['lang'] = $this->language->get('lang');

		if (isset($this->request->post['master_description'])) {
			$data['master_description'] = $this->request->post['master_description'];
		} elseif (isset($this->request->get['master_id'])) {
			$data['master_description'] = $this->model_master_master->getMasterDescriptions($this->request->get['master_id']);
		} else {
			$data['master_description'] = array();
		}

		$key_list = array(
            'keyword'				=> '',
            'link'				=> '',
			'type'				=> 'master',
			'status'			=> true,
			'image'				=> '',
			'logo'				=> '',
			'author_id'		=> '',
			'author_exp'		=> '',
			'company_id'	=> '',
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($master_info)) {
				$data[$key] = $master_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		/*$data['company'] = array();
		if($data['company_id']) {
			$this->load->model('company/company');
			$company_info = $this->model_company_company->getCompany($data['company_id']);
			if($company_info) {
				$data['company'] = array(
					'company_id'	=> $company_info['company_id'],
					'title'			=> $company_info['title']
				);
			}
		}*/

        if (isset($this->request->post['tags'])) {
            $data['tags'] = $this->request->post['tags'];
        } elseif (isset($this->request->get['master_id'])) {
            $data['tags'] = $this->model_master_master->getMasterTags($this->request->get['master_id']);
        } else {
            $data['tags'] = array();
        }


		// company
		$data['companies'] = array();
		if (isset($this->request->post['company'])) {
			$companies = $this->request->post['company'];
			if($companies) {
				$this->load->model('company/company');
				foreach($companies as $company_id) {
					$company_info = $this->model_company_company->getCompany($company_id);
					if($company_info) {
						$data['companies'][] = array(
							'company_id'	=> $company_id,
							'title'				=> $company_info['title']
						);
					}
				}
			}
		}elseif (!empty($master_info)){
			$data['companies'] = $this->model_master_master->getCompaniesByMaster($this->request->get['master_id']);
		}

		$data['author'] = array();
		$this->load->model('visitor/visitor');

		if($data['author_id']) {
			$author_info = $this->model_visitor_visitor->getVisitor($data['author_id']);
			if($author_info) {
				$data['author'] = array(
					'author_id'	=> $author_info['visitor_id'],
					'name'			=> $author_info['name'],
					'exp'				=> $author_info['exp'],
					'exp_list'	=> $this->model_visitor_visitor->getExpByVisitor($data['author_id'])
				);
			}
		}

		$data['experts'] = array();
		if(!empty($this->request->post['experts'])) {
			$experts = $this->request->post['experts'];
		}else if(isset($master_info)) {
			$experts = $this->model_master_master->getMasterExperts($this->request->get['master_id']);
		}else{
			$experts = array();
		}

		foreach($experts as $expert) {
			$author_info = $this->model_visitor_visitor->getVisitor($expert['author_id']);
			$exp_list = $this->model_visitor_visitor->getExpByVisitor($expert['author_id']);
			$exp_default = !empty($exp_list) ? $exp_list[0]['exp_id'] : 0;

			if(isset($expert['exp_id'])) {
				$exp_id = $expert['exp_id'];
			}else if(isset($expert['author_exp'])) {
				$exp_id = $expert['author_exp'];
			}else{
				$exp_id = $exp_default;
			}

			if($author_info) {
				$data['experts'][] = array(
					'author_id'	=> $author_info['visitor_id'],
					'name'			=> $author_info['name'],
					'exp_id'		=> $exp_id,
					'exp_list'	=> $exp_list

				);
			}
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($master_info) && is_file(DIR_IMAGE . $master_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($master_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['logo']) && is_file(DIR_IMAGE . $this->request->post['logo'])) {
			$data['thumb_logo'] = $this->model_tool_image->resize($this->request->post['logo'], 100, 100);
		} elseif (!empty($master_info) && is_file(DIR_IMAGE . $master_info['logo'])) {
			$data['thumb_logo'] = $this->model_tool_image->resize($master_info['logo'], 100, 100);
		} else {
			$data['thumb_logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['date_available'])) {
			$data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($master_info)) {
			$data['date_available'] = ($master_info['date_available'] != '0000-00-00') ? $master_info['date_available'] : '';
		} else {
			$data['date_available'] = date('Y-m-d H:i:s');
		}


		if (isset($this->request->post['master_layout'])) {
			$data['master_layout'] = $this->request->post['master_layout'];
		} elseif (isset($this->request->get['master_id'])) {
			$data['master_layout'] = $this->model_master_master->getMasterLayouts($this->request->get['master_id']);
		} else {
			$data['master_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('master/master_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'master/master')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['master_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}

		if (utf8_strlen($this->request->post['link']) < 3) {
			$this->error['link'] = $this->language->get('error_link');
		}

        if(empty($this->request->post['keyword'])){
            $a_data = array(
                'name'    => $this->request->post['event_description'][$this->config->get('config_language_id')]['title'],
                'essence' => 'event',
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

            if ($url_alias_info && isset($this->request->get['master_id']) && $url_alias_info['query'] != 'master_id=' . $this->request->get['master_id']) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }

            if ($url_alias_info && !isset($this->request->get['master_id'])) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }
        }
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'master/master')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validate_meta() {
		if (!$this->user->hasPermission('modify', 'master/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['av_master']['bread'])) {
			$this->error['bread'] = $this->language->get('error_bread');
		}

		if (empty($this->request->post['av_master']['meta_h1'])) {
			$this->error['meta_h1'] = $this->language->get('error_meta_h1');
		}

		return !$this->error;
	}

}