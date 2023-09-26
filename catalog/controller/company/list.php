<?php
class ControllerCompanyList extends Controller {
	private $limit = 31;

	public function index() {

		$this->load->model('company/company');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$json = false;

		// result
		$data['companies'] = array();

		$filter_data = array(
			'start'           => ($page - 1) * $this->limit,
			'limit'           => $this->limit
		);

		$company_total = $this->model_company_company->getTotalCompanies($filter_data);

		$results = $this->model_company_company->getCompanies($filter_data);
		
		foreach ($results as $result) {
			$image = $this->model_themeset_themeset->resize($result['image'], 168, 73);

			$data['companies'][] = array(
				'company_id' 	 	=> $result['company_id'],
				'thumb'     	  => $image,
				'title'       	=> $result['title'],
				'preview'       => $result['description'],
				'branches'      => $result['branches'],
				'href'        	=> $this->url->link('company/info', 'company_id=' . $result['company_id'])
			);
		}

		if($json) {
			$data['template'] = $this->load->view('company/company_list', $data);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}else{
			// $this->response->setOutput($this->load->view('company/company_list', $data));
			return $this->load->view('company/company_list', $data);
		}

	}

}
