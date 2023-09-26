<?php
class ControllerExtensionModuleTgram extends Controller {
	public function index() {
		$status = true;

		$data['telegram'] = array(
			'link'	=> $this->config->get('tgram_link'),
			'text'	=> $this->config->get('tgram_text'),
		);

		return $this->load->view('extension/module/tgram', $data);
	}
}
