<?php
class ControllerPresentPresent extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('present/present');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('present/present');

		$this->getList();
	}

	public function add() {
		$this->load->language('present/present');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('present/present');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_present_present->addPresent($this->request->post);

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


			$this->response->redirect($this->url->link('present/present', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('present/present');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('present/present');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_present_present->editPresent($this->request->get['present_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('present/present', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('present/present');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('present/present');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $present_id) {
				$this->model_present_present->deletePresent($present_id);
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


			$this->response->redirect($this->url->link('present/present', 'token=' . $this->session->data['token'] . $url, true));
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
			'href' => $this->url->link('present/present', 'token=' . $this->session->data['token'] . $url, true)
		);



		$data['meta'] = $this->url->link('present/present/setting', 'token=' . $this->session->data['token'] . $url, true);
		$data['add'] = $this->url->link('present/present/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('present/present/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['presents'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);


		$present_total = $this->model_present_present->getTotalPresents($filter_data);


		$results = $this->model_present_present->getPresents($filter_data);

		foreach ($results as $result) {
			$data['presents'][] = array(
				'present_id'					=> $result['present_id'],
				'title'          		=> $result['title'],
				'status'          		=> $result['status'],
				'edit'          	 	=> $this->url->link('present/present/edit', 'token=' . $this->session->data['token'] . '&present_id=' . $result['present_id'] . $url, true)
			);

		}

		

		$data['heading_title'] = $heading_title;


		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_status'] = $this->language->get('column_status');

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

		$data['sort_title'] = $this->url->link('present/present', 'token=' . $this->session->data['token'] . '&sort=c.title' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $present_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('present/present', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($present_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($present_total - $this->config->get('config_limit_admin'))) ? $present_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $present_total, ceil($present_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('present/present_list', $data));
	}

	protected function getForm() {

		$heading_title = $this->language->get('heading_title');
		$data['heading_title'] = $heading_title;

		$data['text_form'] = !isset($this->request->get['present_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$lang_list = array(
			'text_default',
			'text_enabled',
			'text_disabled',
			
			'entry_title',
			'entry_href',
			'entry_image',
			'entry_status',
			'entry_event',
			'entry_event_placeholder',
			
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
			'href' => $this->url->link('present/present', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['present_id'])) {
			$data['action'] = $this->url->link('present/present/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('present/present/edit', 'token=' . $this->session->data['token'] . '&present_id=' . $this->request->get['present_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('present/present', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['present_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$present_info = $this->model_present_present->getPresent($this->request->get['present_id']);
		}



		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

	
		$key_list = array(
			'title'			=> '',
			'image'			=> '',
			'href'			=> '',
			'status'			=> true
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($present_info)) {
				$data[$key] = $present_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($present_info) && is_file(DIR_IMAGE . $present_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($present_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		// events
		$data['events'] = array();
		$events = array();
		if (isset($this->request->post['event'])) {
			$events = $this->request->post['event'];
		}elseif (!empty($present_info)){
			$events = $this->model_present_present->getEventsByPresent($this->request->get['present_id']);
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

		$this->response->setOutput($this->load->view('present/present_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'present/present')) {
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
		if (!$this->user->hasPermission('modify', 'present/present')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function existPresent() {
		$json = array();
		$this->load->language('present/present');

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];

			$this->load->model('present/present');

			$filter_data = array(
				'filter_title'  => $filter_title,
				'start'        => 0,
				'limit'        => 5
			);

			$present_info = $this->model_present_present->getPresentByName($filter_title);

			if($present_info) {
				$json['exist'] = true;
				$json['present'] = array(
					'present_id'	=> $present_info['present_id'],
					'present'			=> $present_info['title'],
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

			$this->load->model('present/present');

			$filter_data = array(
				'filter_title'  => $filter_title,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_present_present->getPresents($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'present_id'      => $result['present_id'],
					'name'         => $result['title']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


}