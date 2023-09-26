<?php
class ControllerRegisterCompany extends Controller {

	private $company_activity = array(
		'Производитель',
		'Системная интеграция',
		'Поставка и дистрибуция AV-оборудования',
		'Установка и обслуживание',
		'Прокат, аренда аудиовизуальных систем',
		'Пользователь AV-решений',
		'Другое'
	);

	public function getCompanyActivity() {
		return $this->company_activity;
	}
	
	public function searchCompanies() {

		$this->load->model('register/register');
		$this->load->model('themeset/themeset');

		$return = array();
		$data = array();
		$error = false;

		$company_name = !empty($this->request->post['company_name']) ? $this->request->post['company_name'] : '';

		if(!$company_name) {
			$error = true;
		}

		if(!$error) {
			
			$data['search'] = $company_name;
			$data['companies'] = array();

			$filter_data = array(
				'filter_name'  			=> $company_name,
				'start'        			=> 0,
				'limit'       			=> 10
			);

			$results = $this->model_register_register->getCompanyNames($filter_data);
			foreach ($results as $result) {
				$data['companies'][] = array(
					'b24id'  				=> !empty($result['b24id']) ? $result['b24id'] : 0,
					'id'  					=> !empty($result['b24id']) ? $result['b24id'] : 'new',
					'title'    			=> $result['name']
				);
			}

			if($data['companies']) {
				$data['text_result'] = 'По запросу <strong>“' . $company_name . '”</strong> ' . $this->model_themeset_themeset->trueText(count($data['companies']), 'найдена', 'найдено', 'найдено') . ' ' . count($data['companies']) . ' ' . $this->model_themeset_themeset->trueText(count($data['companies']), 'компания', 'компании', 'компаний');
				$return['template'] = $this->load->view('register/_brand_results', $data);
			}else{


				$data['activity'] = $this->company_activity;
				$data['company_info']	= array(
					'b24_company_old_id'	=> 0,
					'b24_company_id'			=> 0,
					'city'								=> '',
					'web'									=> '',
					'phone'								=> '',
					'activity'						=> '',

					'search'							=> $company_name
				);
				$return['template'] = $this->load->view('register/_brand_data', $data);
			}
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($return));
	}

	public function changeSearch() {

		$return = array();
		$data = array();

		$data['brand_search'] = !empty($this->request->post['search']) ? $this->request->post['search'] : '';

		$return['template'] = $this->load->view('register/_brand_main', $data);


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($return));
	}

	public function chooseCompany() {

		$this->load->model('register/register');

		$return = array();
		$data = array();

		$b24id = !empty($this->request->post['b24id']) ? $this->request->post['b24id'] : '';

		$data['company_info'] = array();

		$data['activity'] = $this->company_activity;

		$filter_data = array(
			'filter_b24id'	=> $b24id,
			'filter_start'	=> 0,
			'filter_limit'	=> 1
		);
		$results = $this->model_register_register->getCompanyNames($filter_data);

		if(!empty($results)) {

			$company_info = $results[0];

			$data['company_info'] = array(
				'b24_company_old_id'	=> $company_info['b24id'],
				'b24_company_id'			=> $company_info['b24id'],
				'title'								=> $company_info['name'],
				'city'								=> $company_info['city'],
				'web'									=> $company_info['web'],
				'phone'								=> $company_info['phone'],
				'activity'						=> $company_info['activity'],

				'search'							=> $company_info['name'],
			);

			$return['template'] = $this->load->view('register/_brand_data', $data);
		}



		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($return));
	}

	public function addNewCompany() {

		$this->load->model('register/register');
		$this->load->model('themeset/themeset');

		$return = array();
		$data = array();
		$error = false;

		$company_name = !empty($this->request->post['search']) ? $this->request->post['search'] : '';

		if(!$company_name) {
			$error = true;
		}

		if(!$error) {
			
			$data['search'] = $company_name;
			
			$data['activity'] = $this->company_activity;
			$data['company_info']	= array(
				'b24_company_old_id'	=> 0,
				'b24_company_id'			=> 0,
				'city'								=> '',
				'web'									=> '',
				'phone'								=> '',
				'activity'						=> '',

				'search'							=> $company_name
			);
			$return['template'] = $this->load->view('register/_brand_data', $data);

		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($return));
	}






}
