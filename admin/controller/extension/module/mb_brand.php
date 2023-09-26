<?php
class ControllerExtensionModuleMBbrand extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/mb_brand');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('view/javascript/jquery-ui.sortable/jquery-ui.min.js');

		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('mb_brand', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		// language
		$lang_list = array(
			'heading_title',

			'text_edit',
			'text_enabled',
			'text_disabled',

			'entry_name',
			'entry_title',
			'entry_company',
			'entry_company_placeholder',
			'entry_status',

			'confirm_remove',

			'help_sort',

			'button_save',
			'button_cancel',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}

		// errors
		$error_list = array(
			'warning',
			'name',
		);
		foreach($error_list as $key) {
			if (isset($this->error[$key])) {
				$data['error_' . $key] = $this->error[$key];
			} else {
				$data['error_' . $key] = '';
			}
		}

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/mb_brand', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/mb_brand', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/mb_brand', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/mb_brand', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		$key_list = array(
			'name',
			'title',
			'status',
		);

		foreach($key_list as $key) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($module_info)) {
				$data[$key] = $module_info[$key];
			} else {
				$data[$key] = '';
			}
		}

		// company
		$data['companies'] = array();
		
		if (isset($this->request->post['company'])) {
			$companies = $this->request->post['company'];
		}elseif (!empty($module_info['company'])){
			$companies = $module_info['company'];
		}else{
			$companies = array();
		}

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



		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/mb_brand', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/mb_brand')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		return !$this->error;
	}
}