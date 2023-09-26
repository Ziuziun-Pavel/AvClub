<?php
class ControllerExtensionModuleMBVideo extends Controller {
	public function index($setting) {
		static $module = 0;

		$data = array();

		$data['title'] = $setting['title'];
		$data['you'] = $setting['you'];

		$this->load->model('themeset/themeset');

		if($setting['image'] && is_file(DIR_IMAGE . $setting['image'])) {
			$data['image'] = $this->model_themeset_themeset->resize_crop($setting['image']);
		}else{
			$data['image'] = '';
		}

		if($data['you'] && $data['image']) {
			return $this->load->view('extension/module/mb_video', $data);
		}
	}
}