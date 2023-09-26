<?php
class ControllerThemesetAlert extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('themeset/alert');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('av_alert', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('av_alert');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}		

		$heading_title = $this->language->get('heading_title');

		$data['heading_title'] = $heading_title;


		$data['action'] = $this->url->link('themeset/alert', 'token=' . $this->session->data['token'], true);

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
			'href' => $this->url->link('themeset/alert', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);

		$lang_list = array(
			'entry_mail_protocol',
			'entry_mail_parameter',
			'entry_mail_smtp_hostname',
			'entry_mail_smtp_username',
			'entry_mail_smtp_password',
			'entry_mail_smtp_port',
			'entry_mail_smtp_timeout',

			'help_mail_protocol',
			'help_mail_parameter',
			'help_mail_smtp_hostname',

			'text_mail',
			'text_smtp',

		);

		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}

		$key_list = array(
			'av_alert_mail_protocol',
			'av_alert_mail_parameter',
			'av_alert_mail_smtp_hostname',
			'av_alert_mail_smtp_username',
			'av_alert_mail_smtp_password',
			'av_alert_mail_smtp_port',
			'av_alert_mail_smtp_timeout',
		);
		foreach($key_list as $key){
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} else {
				$data[$key] = $this->config->get($key);
			}
		}
		
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('themeset/alert', $data));

	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'themeset/alert')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error) {
			$this->error['warning'] = $this->language->get('error_values');
		}

		return !$this->error;
	}

}
