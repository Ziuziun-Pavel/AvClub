<?php
class ControllerJournalStatus extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('journal/journal');

		$this->getForm();
	}

	public function save() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('journal/journal');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_journal_journal->saveJournal($this->request->get['journal_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_master_old'])) {
				$url .= '&filter_master_old=' . $this->request->get['filter_master_old'];
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

			$type = !empty($this->request->post['type']) ? $this->request->post['type'] : 'article';

			$this->response->redirect($this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	protected function getForm() {
		$this->load->language('journal/journal');

		$type = !empty($this->request->get['type']) ? $this->request->get['type'] : 'article';

		$journal_id = !empty($this->request->get['journal_id']) ? $this->request->get['journal_id'] : 0;

		$heading_title = $this->language->get('heading_title');

		$data['text_form'] = $this->language->get('entry_status');


		$lang_list = array(
			'text_enabled',
			'text_disabled',
			'button_save',
			'button_cancel',

			'heading_title',
			
			'entry_status',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$error_list = array(
			'warning'	=> ''
		);

		foreach($error_list as $key=>$default) {
			if (isset($this->error[$key])) {
				$data['error_' . $key] = $this->error[$key];
			} else {
				$data['error_' . $key] = $default;
			}
		}


		if (isset($this->request->get['journal_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$journal_info = $this->model_journal_journal->getJournal($this->request->get['journal_id']);
			$type = $journal_info['type'];
		}


		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_master_old'])) {
			$url .= '&filter_master_old=' . $this->request->get['filter_master_old'];
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

		$url .= '&type=' . $type; 

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['action'] = $this->url->link('journal/status/save', 'token=' . $this->session->data['token'] . '&journal_id=' . $this->request->get['journal_id'] . $url, true);

		$data['cancel'] = $this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . $url, true);


		$data['type'] = $type;


		$data['token'] = $this->session->data['token'];
		

		$key_list = array(
			'status'			=> 0
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (isset($journal_info[$key])) {
				$data[$key] = $journal_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('journal/status_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'journal/status')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

}