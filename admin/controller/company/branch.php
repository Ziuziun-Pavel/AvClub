<?php
class ControllerCompanyBranch extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('company/branch');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('company/branch');

		$this->getList();
	}

	public function add() {
		$this->load->language('company/branch');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('company/branch');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_company_branch->addBranch($this->request->post);

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


			$this->response->redirect($this->url->link('company/branch', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('company/branch');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('company/branch');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_company_branch->editBranch($this->request->get['branch_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('company/branch', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('company/branch');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('company/branch');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $branch_id) {
				$this->model_company_branch->deleteBranch($branch_id);
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


			$this->response->redirect($this->url->link('company/branch', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {

		$heading_title = $this->language->get('heading_title');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c.title';
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
			'href' => $this->url->link('company/branch', 'token=' . $this->session->data['token'] . $url, true)
		);



		$data['meta'] = $this->url->link('company/branch/setting', 'token=' . $this->session->data['token'] . $url, true);
		$data['add'] = $this->url->link('company/branch/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('company/branch/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['branches'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);


		$branch_total = $this->model_company_branch->getTotalBranches($filter_data);


		$results = $this->model_company_branch->getBranches($filter_data);

		foreach ($results as $result) {
			$data['branches'][] = array(
				'branch_id'					=> $result['branch_id'],
				'title'          		=> $result['title'],
				'edit'          	 	=> $this->url->link('company/branch/edit', 'token=' . $this->session->data['token'] . '&branch_id=' . $result['branch_id'] . $url, true)
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

		$data['sort_title'] = $this->url->link('company/branch', 'token=' . $this->session->data['token'] . '&sort=c.title' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $branch_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('company/branch', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($branch_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($branch_total - $this->config->get('config_limit_admin'))) ? $branch_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $branch_total, ceil($branch_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('company/branch_list', $data));
	}

	protected function getForm() {

		$heading_title = $this->language->get('heading_title');
		$data['heading_title'] = $heading_title;

		$data['text_form'] = !isset($this->request->get['branch_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$lang_list = array(
			'text_default',
			'text_enabled',
			'text_disabled',
			
			'entry_title',
			'entry_status',
			
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
			'title'
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
			'href' => $this->url->link('company/branch', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['branch_id'])) {
			$data['action'] = $this->url->link('company/branch/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('company/branch/edit', 'token=' . $this->session->data['token'] . '&branch_id=' . $this->request->get['branch_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('company/branch', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['branch_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$branch_info = $this->model_company_branch->getBranch($this->request->get['branch_id']);
		}



		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

	
		$key_list = array(
			'title'			=> ''
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($branch_info)) {
				$data[$key] = $branch_info[$key];
			} else {
				$data[$key] = $default;
			}
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('company/branch_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'company/branch')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['title']) < 2) || (utf8_strlen($this->request->post['title']) > 64)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'company/branch')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}


	public function existBranch() {
		$json = array();
		$this->load->language('company/branch');

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];

			$this->load->model('company/branch');

			$filter_data = array(
				'filter_title'  => $filter_title,
				'start'        => 0,
				'limit'        => 5
			);

			$branch_info = $this->model_company_branch->getBranchByName($filter_title);

			if($branch_info) {
				$json['exist'] = true;
				$json['branch'] = array(
					'branch_id'	=> $branch_info['branch_id'],
					'branch'			=> $branch_info['title'],
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

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];

			$this->load->model('company/branch');

			$filter_data = array(
				'filter_title'  => $filter_title,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_company_branch->getBranches($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'branch_id'       => $result['branch_id'],
					'branch'         => $result['title']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


}