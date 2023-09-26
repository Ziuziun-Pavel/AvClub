<?php
class ControllerExtensionModuleMBBannhome extends Controller {
	public function index($setting) {
		static $module = 0;

		$data = array();

		$this->load->model('themeset/themeset');

		$data['template'] = $setting['template'];

		$banner_info = $this->model_themeset_themeset->getBanner('stretch');
		if($banner_info) {
			$data['href'] = $banner_info['link'];
			$data['target'] = $banner_info['target'];
			$data['banner_id'] = $banner_info['banner_id'];

			if($setting['template'] === 'mobile') {
				if($banner_info['image_mob'] && is_file(DIR_IMAGE . $banner_info['image_mob'])) {
					$data['image'] = $this->model_themeset_themeset->resize_crop($banner_info['image_mob']);
				}else{
					$data['image'] = '';
				}
			}else{
				if($banner_info['image_pc'] && is_file(DIR_IMAGE . $banner_info['image_pc'])) {
					$data['image'] = $this->model_themeset_themeset->resize_crop($banner_info['image_pc']);
				}else{
					$data['image'] = '';
				}
			}



			if($data['image']) {
				return $this->load->view('extension/module/mb_bannhome', $data);
			}

		}

	}
}