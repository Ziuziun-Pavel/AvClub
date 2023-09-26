<?php
class ControllerExtensionModuleMBBannside extends Controller {
	public function index($setting) {
		static $module = 0;

		$data = array();

		$this->load->model('themeset/themeset');

		$banner_info = $this->model_themeset_themeset->getBanner('content');
		if($banner_info) {
			$data['href'] = $banner_info['link'];
			$data['target'] = $banner_info['target'];
			$data['banner_id'] = $banner_info['banner_id'];

			if($banner_info['image_pc'] && is_file(DIR_IMAGE . $banner_info['image_pc'])) {
				$data['image_pc'] = $this->model_themeset_themeset->resize_crop($banner_info['image_pc']);
			}else{
				$data['image_pc'] = '';
			}


			if($data['image_pc']) {
				return $this->load->view('extension/module/mb_bannside', $data);
			}

		}
		
	}
}