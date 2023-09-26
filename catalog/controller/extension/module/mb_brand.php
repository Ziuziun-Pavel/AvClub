<?php
class ControllerExtensionModuleMBbrand extends Controller {
	public function index($setting) {
		static $module = 0;

		$data = array();

		$data['title'] = $setting['title'];

		$this->load->model('company/company');

		$brand_list = $setting['company'];
		if(!empty($brand_list)) {
			foreach($brand_list as $brand_id) {
				$brand_info = $this->model_company_company->getCompany($brand_id);
				if($brand_info) {
					$data['companies'][] = array(
						'company_id'	=> $brand_info['company_id'],
						'title'				=> $brand_info['title'],
						'image'				=> $this->model_tool_image->resize($brand_info['image'], 214, 100),
						'href'				=> $this->url->link('company/info', 'company_id=' . $brand_info['company_id']),
					);
				}
				
			}
		}

		if($data['companies']) {
			return $this->load->view('extension/module/mb_brand', $data);
		}
	}
}