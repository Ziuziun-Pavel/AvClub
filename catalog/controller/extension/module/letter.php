<?php
class ControllerExtensionModuleLetter extends Controller {
	public function index() {
		
		$data['title'] = $this->config->get('letter_title');
		$data['text'] = $this->config->get('letter_text');

		return $this->load->view('extension/module/letter', $data);
	}
}
