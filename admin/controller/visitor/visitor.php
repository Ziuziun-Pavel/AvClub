<?php
class ControllerVisitorVisitor extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('visitor/visitor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('visitor/visitor');

		$this->getList();
	}

	public function add() {
		$this->load->language('visitor/visitor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('visitor/visitor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_visitor_visitor->addVisitor($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

//			if (isset($this->request->get['filter_email'])) {
//				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
//			}


			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_expert'])) {
				$url .= '&filter_expert=' . $this->request->get['filter_expert'];
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

			$this->response->redirect($this->url->link('visitor/visitor', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('visitor/visitor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('visitor/visitor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_visitor_visitor->editVisitor($this->request->get['visitor_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

//			if (isset($this->request->get['filter_email'])) {
//				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
//			}


			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_expert'])) {
				$url .= '&filter_expert=' . $this->request->get['filter_expert'];
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

			$this->response->redirect($this->url->link('visitor/visitor', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('visitor/visitor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('visitor/visitor');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $visitor_id) {
				$this->model_visitor_visitor->deleteVisitor($visitor_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}


			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_expert'])) {
				$url .= '&filter_expert=' . $this->request->get['filter_expert'];
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

			$this->response->redirect($this->url->link('visitor/visitor', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

//		if (isset($this->request->get['filter_email'])) {
//			$filter_email = $this->request->get['filter_email'];
//		} else {
//			$filter_email = null;
//		}


		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_expert'])) {
			$filter_expert = $this->request->get['filter_expert'] === '*' ? null : $this->request->get['filter_expert'];
		} else {
			$filter_expert = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

//		if (isset($this->request->get['filter_email'])) {
//			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
//		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_expert'])) {
			$url .= '&filter_expert=' . $this->request->get['filter_expert'];
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('visitor/visitor', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('visitor/visitor/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('visitor/visitor/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['visitors'] = array();

		$filter_data = array(
			'filter_name'              => $filter_name,
//			'filter_email'             => $filter_email,
			'filter_status'            => $filter_status,
			'filter_expert'            => $filter_expert,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);

		$visitor_total = $this->model_visitor_visitor->getTotalVisitors($filter_data);
		$results = $this->model_visitor_visitor->getVisitors($filter_data);

		foreach ($results as $result) {

			$data['visitors'][] = array(
				'visitor_id'		=> $result['visitor_id'],
				'name'					=> $result['name'],
				'exp'						=> $result['exp'],
				'expert'				=> $result['expert'],
//				'email'					=> $result['email'],
				'b24id'					=> $result['b24id'],
				'update_link'		=> $result['b24id'] ? HTTPS_CATALOG . 'index.php?route=themeset/expert/getContactById&contact_id=' . $result['b24id'] . '&show_info=0&show_result=1' : '',
				'view'					=> $result['b24id'] && !empty($result['expert']) ? HTTPS_CATALOG . 'index.php?route=expert/expert&expert_id=' . $result['visitor_id'] : '',
				'status'				=> $result['status'],
				'edit'					=> $this->url->link('visitor/visitor/edit', 'token=' . $this->session->data['token'] . '&visitor_id=' . $result['visitor_id'] . $url, true)
			);
		}


		$lang_list = array(
			'heading_title',

			'text_list',
			'text_enabled',
			'text_disabled',
			'text_yes',
			'text_no',
			'text_default',
			'text_no_results',
			'text_confirm',

			'column_name',
			'column_email',
			'column_exp',
			'column_status',
			'column_action',

			'entry_name',
			'entry_email',
			'entry_status',

			'button_add',
			'button_edit',
			'button_delete',
			'button_filter',
		);

		foreach($lang_list as $item) {
			$data[$item] = $this->language->get($item);
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

//		if (isset($this->request->get['filter_email'])) {
//			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
//		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_expert'])) {
			$url .= '&filter_expert=' . $this->request->get['filter_expert'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('visitor/visitor', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
//		$data['sort_email'] = $this->url->link('visitor/visitor', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, true);
		$data['sort_status'] = $this->url->link('visitor/visitor', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, true);
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

//		if (isset($this->request->get['filter_email'])) {
//			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
//		}


		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_expert'])) {
			$url .= '&filter_expert=' . $this->request->get['filter_expert'];
		}

		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $visitor_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('visitor/visitor', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($visitor_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($visitor_total - $this->config->get('config_limit_admin'))) ? $visitor_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $visitor_total, ceil($visitor_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
//		$data['filter_email'] = $filter_email;
		$data['filter_status'] = $filter_status;
		$data['filter_expert'] = $filter_expert;


		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('visitor/visitor_list', $data));
	}

	protected function getForm() {

		$lang_list = array(
			'heading_title',

			'text_enabled',
			'text_disabled',
			'text_yes',
			'text_no',
			'text_select',
			'text_none',
			'text_loading',
			'text_exp_add',
			
			'confirm_exp_remove',

			'tab_general',
			'tab_fields',

			'entry_name',
			'entry_firstname',
			'entry_lastname',
			'entry_exp',
			'entry_email',
			'entry_password',
			'entry_confirm',
			'entry_status',
			'entry_image',
			'entry_default',
			'entry_comment',
			'entry_description',
			'entry_event',
			'entry_event_placeholder',
			'entry_company',
			'entry_company_placeholder',
			'entry_tag',
			'entry_tag_placeholder',
			'entry_expert',
			'entry_b24id',
//			'entry_emails',
			'entry_keyword',
			'entry_field_expertise',
			'entry_field_useful',
			'entry_field_regalia',

			'button_save',
			'button_cancel',
			'button_remove',
			'button_upload',

			'help_keyword',

			'tab_general',
		);

		foreach($lang_list as $item) {
			$data[$item] = $this->language->get($item);
		}


		$data['text_form'] = !isset($this->request->get['visitor_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['visitor_id'])) {
			$data['visitor_id'] = $this->request->get['visitor_id'];
		} else {
			$data['visitor_id'] = 0;
		}


		$error_list = array(
			'warning',
			'firstname',
			'lastname',
//			'email',
			'password',
			'confirm',
			'keyword',
		);

		foreach($error_list as $item) {
			if (isset($this->error[$item])) {
				$data['error_' . $item] = $this->error[$item];
			} else {
				$data['error_' . $item] = '';
			}
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

//		if (isset($this->request->get['filter_email'])) {
//			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
//		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_expert'])) {
			$url .= '&filter_expert=' . $this->request->get['filter_expert'];
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('visitor/visitor', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['visitor_id'])) {
			$data['action'] = $this->url->link('visitor/visitor/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('visitor/visitor/edit', 'token=' . $this->session->data['token'] . '&visitor_id=' . $this->request->get['visitor_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('visitor/visitor', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['visitor_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$visitor_info = $this->model_visitor_visitor->getVisitor($this->request->get['visitor_id']);
		}

		$key_list = array(
			'firstname'					=> '',
			'lastname'					=> '',
			'image'							=> '',
//			'email'							=> '',
//			'emails'						=> '',
			'exp'								=> '',
			'expert'						=> 0,
			'b24id'							=> 0,
			'status'						=> true,
			'company_id'				=> '',
			'keyword'						=> '',
			'field_expertise'		=> '',
			'field_useful'			=> '',
			'field_regalia'			=> '',
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($visitor_info)) {
				$data[$key] = $visitor_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		$data['config_language_id'] = $this->config->get('config_language_id'); // SEO URL Generator
		$data['lang'] = $this->language->get('lang');


		// company
		$data['company'] = array();
		if($data['company_id']) {
			$this->load->model('company/company');
			$company_info = $this->model_company_company->getCompany($data['company_id']);
			if($company_info) {
				$data['company'] = array(
					'company_id'	=> $company_info['company_id'],
					'title'			=> $company_info['title']
				);
			}
		}

		// tags expert
		$data['tags_expert_new'] = array();
		if(!empty($this->request->post['tag_new'])) {
			foreach($this->request->post['tag_new'] as $tag_name) {
				$data['tags_expert_new'][] = array(
					'tag_id'	=> 0,
					'tag'			=> $tag_name
				);
			}
		}
		$data['tags_expert'] = array();
		if (isset($this->request->post['tag']) ) {
			$tags = $this->request->post['tag'];
			if($tags) {
				$this->load->model('tag/tag');
				foreach($tags as $tag_id) {
					$tag_info = $this->model_tag_tag->getTagById($tag_id);
					if($tag_info) {
						$data['tags_expert'][] = array(
							'tag_id'	=> $tag_id,
							'tag'			=> $tag_info['title']
						);
					}
				}
			}
		}elseif (!empty($visitor_info)){
			$data['tags_expert'] = $this->model_visitor_visitor->getTagsExpert($this->request->get['visitor_id']);
		}

		// tags branch
		$data['tags_branch_new'] = array();
		if(!empty($this->request->post['tag_new'])) {
			foreach($this->request->post['tag_new'] as $tag_name) {
				$data['tags_branch_new'][] = array(
					'tag_id'	=> 0,
					'tag'			=> $tag_name
				);
			}
		}
		$data['tags_branch'] = array();
		if (isset($this->request->post['tag']) ) {
			$tags = $this->request->post['tag'];
			if($tags) {
				$this->load->model('tag/tag');
				foreach($tags as $tag_id) {
					$tag_info = $this->model_tag_tag->getTagById($tag_id);
					if($tag_info) {
						$data['tags_branch'][] = array(
							'tag_id'	=> $tag_id,
							'tag'			=> $tag_info['title']
						);
					}
				}
			}
		}elseif (!empty($visitor_info)){
			$data['tags_branch'] = $this->model_visitor_visitor->getTagsBranch($this->request->get['visitor_id']);
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($visitor_info) && is_file(DIR_IMAGE . $visitor_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($visitor_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}

		// events
		$data['events'] = array();
		$events = array();
		if (isset($this->request->post['event'])) {
			$events = $this->request->post['event'];
		}elseif (!empty($visitor_info)){
			$events = $this->model_visitor_visitor->getEventsByVisitor($this->request->get['visitor_id']);
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

		// exp list
		$data['exp_list'] = array();
		$exp_list = array();
		if (isset($this->request->post['exp_list'])) {
			$exp_list = $this->request->post['exp_list'];
		}elseif (!empty($visitor_info)){
			$exp_list = $this->model_visitor_visitor->getExpByVisitor($this->request->get['visitor_id']);
		}
		$data['exp_list'] = $exp_list;

		$data['exp_add'] = isset($this->request->post['exp_add']) ? $this->request->post['exp_add'] : array();
		


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('visitor/visitor_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'visitor/visitor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 64)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 64)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

//		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match($this->config->get('config_mail_regexp'), $this->request->post['email'])) {
//			$this->error['email'] = $this->language->get('error_email');
//		}

		//$visitor_info = $this->model_visitor_visitor->getVisitorByEmail($this->request->post['email']);

        $visitor_info = $this->model_visitor_visitor->getVisitorByB24Id($this->request->post['b24id']);

		if (!isset($this->request->get['visitor_id'])) {
			if ($visitor_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($visitor_info && ($this->request->get['visitor_id'] != $visitor_info['visitor_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}

		if ($this->request->post['password'] || (!isset($this->request->get['visitor_id']))) {
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}

		if(empty($this->request->post['keyword'])){
			$a_data = array(
				'name'    => $this->request->post['name'],
				'essence' => 'expert',
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

			if ($url_alias_info && isset($this->request->get['visitor_id']) && $url_alias_info['query'] != 'expert_id=' . $this->request->get['visitor_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['visitor_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}


		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'visitor/visitor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function existVisitor() {
		$json = array();
		$this->load->language('visitor/visitor');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];

			$this->load->model('visitor/visitor');

			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => 5
			);

			$visitor_info = $this->model_visitor_visitor->getVisitorByName($filter_name);

			if($visitor_info) {
				$json['exist'] = true;
				$json['visitor'] = array(
					'visitor_id'	=> $visitor_info['visitor_id'],
					'visitor'			=> $visitor_info['name'],
					'exp'			=> $visitor_info['exp'],
				);
			}else{
				$json['exist'] = false;
			}

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function deleteExp() {
		$json = array();

		$this->load->language('visitor/visitor');
		$this->load->model('visitor/visitor');

		$exp_id = !empty((int)$this->request->get['exp_id']) ? (int)$this->request->get['exp_id'] : 0;
		$visitor_id = !empty((int)$this->request->get['visitor_id']) ? (int)$this->request->get['visitor_id'] : 0;

		if($exp_id && $visitor_id && $this->validateDeleteExp()) {
			$this->model_visitor_visitor->deleteExp($visitor_id, $exp_id);
			$json['success'] = true;
		}else{
			$json['error'] = $this->language->get('error_delete_exp');
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateDeleteExp() {
		if (!$this->user->hasPermission('modify', 'visitor/visitor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_email'])) {
				$filter_email = $this->request->get['filter_email'];
			} else {
				$filter_email = '';
			}

			$this->load->model('visitor/visitor');

			$filter_data = array(
				'filter_name'  		=> $filter_name,
				'filter_email' 		=> $filter_email,
				'filter_status'		=> 1,
				'start'        		=> 0,
				'limit'        		=> 5
			);

			$results = $this->model_visitor_visitor->getVisitors($filter_data);

			foreach ($results as $result) {
				
				$exp_list = $this->model_visitor_visitor->getExpByVisitor($result['visitor_id']);

				$json[] = array(
					'visitor_id'      => $result['visitor_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'name'         		=> $result['name'],
					'email'           => $result['email'],
					'exp'         		=> $result['exp'],
					'exp_list'				=> $exp_list
				);


			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}
