<?php
class ControllerCompanyFilter extends Controller {
	
	public function index() {

		$this->load->model('company/company');

		$category_id = !empty($this->request->get['company_category_id']) ? $this->request->get['company_category_id'] : '';

		

		$data['links'] = array(
			array(
				'title'		=> 'Персоналии',
				'link'		=> $this->url->link('expert/list'),
				'active'	=> false
			),
			array(
				'title'		=> 'Компании',
				'link'		=> $this->url->link('company/company'),
				'active'	=> true
			),
		);

		// filter
		$data['branches'] = $this->model_company_company->getAllBranches();

		$filter_data = array(
			'filter_category'	=> $category_id
		);
		$data['types'] = $this->model_company_company->getTags($filter_data);

		$data['company_category_id'] = $category_id;



		return $this->load->view('company/company_filter', $data);

	}

}
