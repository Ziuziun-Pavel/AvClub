<?php
class ControllerExpertList extends Controller {
	private $limit = 20;

	public function test() {

		$this->load->model('visitor/expert');

		$filter_data = array(
			'start'           => 0,
			'limit'           => 10000
		);

		// $filter_data['filter_name'] = 'Ильницкий';
		// $filter_data['sort'] = 'modified';
		// $filter_data['order'] = 'ASC';
		// $filter_data['order'] = 'DESC';


		$expert_total = $this->model_visitor_expert->getTotalExperts($filter_data);

		$results = $this->model_visitor_expert->getExperts($filter_data);

		echo '<pre>';
		print_r($filter_data);
		print_r($results);
		echo '</pre>';

	}


	public function index() {

		$data['heading_title'] = 'Поставщики аудиовизуальных решений';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => 'Главная',
			'href' => $this->url->link('common/home')
		);

		/* ТАК НУЖНО! */
		$data['breadcrumbs'][] = array(
			'text' => 'Главная',
			'href' => $this->url->link('common/home')
		);

		$data['links'] = array(
			array(
				'title'		=> 'Персоналии',
				'link'		=> $this->url->link('expert/list'),
				'active'	=> true
			),
			array(
				'title'		=> 'Компании',
				'link'		=> $this->url->link('company/company'),
				'active'	=> false
			),
		);

		$data['expert_list'] = $this->load->controller('expert/list/list');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('expert/expert_list', $data));

	}
	public function list() {

		$this->load->model('visitor/expert');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');
		$this->load->model('themeset/image');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$json = !empty($this->request->get['json']) ? true : false;

		// result
		$data['experts'] = array();

		$filter_data = array(
			'start'           => ($page - 1) * $this->limit,
			'limit'           => $this->limit
		);

		if(!empty($this->request->get['filter_name'])) {
			$filter_data['filter_name'] = $this->request->get['filter_name'];
		}

		if(!empty($this->request->get['filter_company'])) {
			$filter_data['filter_company'] = $this->request->get['filter_company'];
		}

		if(!empty($this->request->get['filter_tag'])) {
			$filter_data['filter_tag'] = $this->request->get['filter_tag'];
		}

		if(!empty($this->request->get['filter_branch'])) {
			$filter_data['filter_branch'] = $this->request->get['filter_branch'];
		}

		if(!empty($this->request->get['sort'])) {
			$sort = explode('.', $this->request->get['sort']);
			$filter_data['sort'] = $sort[0];

			if(!empty($sort[1]) && $sort[1] === 'desc') {
				$filter_data['order'] = 'DESC';
			}else{
				$filter_data['order'] = 'ASC';
			}
		}


		$expert_total = $this->model_visitor_expert->getTotalExperts($filter_data);

		$results = $this->model_visitor_expert->getExperts($filter_data);
		
		foreach ($results as $result) {

			if($result['image'] && is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_themeset_image->crop($result['image'], 160, 160);
			}else{
				$image = $this->model_themeset_image->crop('user_no_avatar.png', 160, 160);
			}
		

			$data['experts'][] = array(
				'expert_id' 	 	=> $result['expert_id'],
				'thumb'     	  => $image,
				'name'       		=> $result['name'],
				'exp'       		=> $result['exp'],
				'href'        	=> $this->url->link('expert/expert', 'expert_id=' . $result['expert_id'])
			);
		}

		$pagination = new Pagination();
		$pagination->total = $expert_total;
		$pagination->page = $page;
		$pagination->limit = $this->limit;
		$pagination->url = $this->url->link('expert/list', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['filter'] = $filter_data;

        if($json) {
			$data['template'] = $this->load->view('expert/expert_list_content', $data);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}else{
			return $this->load->view('expert/expert_list_content', $data);
		}

	}

	public function autocomplete() {
		$json = array();

		$results = array();

		$this->load->model('visitor/expert');

		$filter_data = array(
			'start'        			=> 0,
			'limit'       			=> 5
		);

		if(isset($this->request->get['filter_name'])) {

			$filter_data['filter_name'] = $this->request->get['filter_name'];
			$results = $this->model_visitor_expert->getExperts($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'text' 	 				=> $result['name']
				);
			}

		}else if(isset($this->request->get['filter_company'])) {

			$this->load->model('company/company');
			$filter_data['filter_name'] = $this->request->get['filter_company'];
			$results = $this->model_company_company->getCompanies($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'text' 	 				=> $result['title']
				);
			}

		}else if(isset($this->request->get['filter_tag'])) {

			$filter_data['filter_tag'] = $this->request->get['filter_tag'];
			$results = $this->model_visitor_expert->getAllTags($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'text' 	 				=> $result['title']
				);
			}

		}else if(isset($this->request->get['filter_branch'])) {

			$filter_data['filter_branch'] = $this->request->get['filter_branch'];
			$results = $this->model_visitor_expert->getAllBranches($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'text' 	 				=> $result['title']
				);
			}

		}
	

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}
