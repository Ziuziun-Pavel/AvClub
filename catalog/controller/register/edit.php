<?php
class ControllerRegisterEdit extends Controller {

	private $url_send_mail = "http://clients.techin.by/avclub/site/api/v1/contact/{id}/addComment";

	public function index() {
		$this->load->language('expert/expert');

		$this->load->model('company/company');
		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');
		$this->load->model('themeset/image');
		$this->load->model('themeset/expert');
		$this->load->model('register/register');

		$this->load->model('visitor/expert');

		$expert_id = $this->visitor->getId();
		$b24id = $this->visitor->getB24id();

		if(!$expert_id || !$b24id) {
			header("Location: " . $this->url->link('register/login'));
			exit();
		}


		$data['expert_id'] = $expert_id; 

		$expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);

		if($expert_info) {

			$data['alternate_count'] = $expert_info['alternate_count'];
			
			// $this->model_themeset_expert->getContactInfo($b24id);
			$contact_info = $this->model_register_register->getContactInfo($b24id);


			$data['placeholder'] = $this->model_themeset_image->crop('placeholder_empty.png', 220, 220); 
			if($expert_info['image'] && is_file(DIR_IMAGE . $expert_info['image'])) {
				$data['image'] = $this->model_themeset_image->original($expert_info['image'], 220, 220);
			}else{
				$data['image'] = $data['placeholder'];
			}

			$user_data = array(
				'name'						=> $contact_info['NAME'],
				'lastname'				=> $contact_info['LAST_NAME'],
				'exp'							=> $contact_info['POST'],
				'telephone'				=> !empty($contact_info['PHONE']) ? current($contact_info['PHONE']) : '',
				'email'						=> !empty($contact_info['EMAIL']) ? current($contact_info['EMAIL']) : '',
				'expertise'				=> $contact_info['UF_CRM_1686648613'],
				'useful'					=> $contact_info['UF_CRM_1686648651'],
				'regalia'					=> $contact_info['UF_CRM_1686648672'],
				'b24_company_id'	=> !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : 0,
				'b24_company_old_id'	=> !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : 0,
				'company'					=> '',
				'company_status'	=> !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : '',
				'company_phone'		=> '',
				'company_site'		=> '',
				'company_activity'=> '',
				'city'						=> '',
			);


			if(!empty($user_data['b24_company_id'])) {

				$filter_data = array(
					'filter_b24id'	=> $user_data['b24_company_id'],
					'filter_start'	=> 0,
					'filter_limit'	=> 1
				);
				$results_companies = $this->model_register_register->getCompanyNames($filter_data);

				if(!empty($results_companies)) {
					$company_info = $results_companies[0];

					$user_data['company'] = $company_info['name'];

					$user_data['city'] = $company_info['city'];
					$user_data['company_phone'] = $company_info['phone'];
					$user_data['company_site'] = $company_info['web'];
					$user_data['company_activity'] = $company_info['activity'];

				}else{
					$user_data['b24_company_id'] = 0;
					$user_data['b24_company_old_id'] = 0;
				}
			}

			$data['user'] = $user_data;

			$data_company = array();
			$data_company['activity'] = $this->load->controller('register/company/getCompanyActivity');
			$data_company['company_info'] = array(
				'b24_company_old_id'	=> $user_data['b24_company_old_id'],
				'b24_company_id'			=> $user_data['b24_company_id'],
				'title'								=> $user_data['company'],
				'city'								=> $user_data['city'],
				'web'									=> $user_data['company_site'],
				'phone'								=> $user_data['company_phone'],
				'activity'						=> $user_data['company_activity'],

				'search'							=> $user_data['company'],
			);

			if($user_data['company']) {
				$data['company_template'] = $this->load->view('register/_brand_data', $data_company);
			}else{
				$data['company_template'] = $this->load->view('register/_brand_main', $data_company);
			}

			// $data['back'] = $this->url->link('expert/expert', 'expert_id=' . $expert_id);
			$data['back'] = $this->url->link('register/account');


			$data['company'] = array();
			if($expert_info['company_id']) {
				$company_info = $this->model_company_company->getCompany($expert_info['company_id']);
				if($company_info) {
					if($company_info['image'] && is_file(DIR_IMAGE . $company_info['image'])) {
						$image = $this->model_tool_image->resize($company_info['image'], 220, 85);
					}else{
						$image = '';
					}
					$data['company'] = array(
						'image'	=> $image,
						'title'	=> $company_info['title'],
						'href'	=> $this->url->link('company/info', 'company_id=' . $expert_info['company_id'])
					);
				}
			}



			// BANNER
			$data['banner'] = array();
			$banner_info = $this->model_themeset_themeset->getBanner('content');
			if($banner_info && $banner_info['image_pc'] && is_file(DIR_IMAGE . $banner_info['image_pc'])) {

				$data['banner'] = array(
					'banner_id'		=> $banner_info['banner_id'],
					'link'		=> $banner_info['link'],
					'target'	=> $banner_info['target'],
					'image'		=> $this->model_themeset_themeset->resize_crop($banner_info['image_pc'], 100, 100),
				);
			}

			// master
			$master_info = $this->config->get('av_master');
			$data['master_info'] = array(
				'title'					=> $master_info['master_title'],
				'description'		=> $master_info['master_description'],
				'link'					=> $master_info['master_link'],
				'button'				=> $master_info['master_button'],
			);
			$data['master_all'] = $this->url->link('master/master');
			$this->load->model('master/master');
			$data['master_list'] = array();

			$filter_data = array(
				'start'	=> 0,
				'limit'	=> 3
			);

			$results = $this->model_master_master->getMasters($filter_data);
			if($results) {
				foreach($results as $result) {
					$data['master_list'][] = array(
						'master_id'		=> $result['master_id'],
						'href'        => $result['link'],
						'title'				=> $result['title'],
						'author'			=> $result['author'],
						'exp'					=> $result['exp'],
						'date'				=> $result['date'],
						'time'				=> $result['time'],
					);
				}
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('register/edit', $data));


		}else {
			
			header('Location: ' . $this->url->link('register/login'));
		}


	}

	public function saveData() {

		$post = $this->request->post;

		$this->load->model('register/register');
		$this->load->model('themeset/expert');
		$this->load->model('themeset/image');

		$return = array();
		$data = array();
		$error = false;


		$expert_id = $this->visitor->getId();

		$b24id = $this->model_register_register->getB24Id($expert_id);

		if(!$b24id) {
			$error = true;

			$return['redirect'] = $this->url->link('register/login');
		}

		// все ок, ошибок нет
		if(!$error) {


			$user_data = array(
				'user_id'							=> $b24id,
				'old_user_id'					=> $b24id,
				'name'								=> isset($post['name']) ? $post['name'] : '',
				'lastname'						=> isset($post['lastname']) ? $post['lastname'] : '',
				'post'								=> isset($post['post']) ? $post['post'] : '',
				'b24_company_id'			=> isset($post['b24_company_id']) ? $post['b24_company_id'] : 0,
				'b24_company_old_id'			=> isset($post['b24_company_old_id']) ? $post['b24_company_old_id'] : 0,
				'company'							=> isset($post['company']) ? $post['company'] : '',
				'company_status'			=> isset($post['company_status']) ? $post['company_status'] : '',
				'company_phone'				=> isset($post['company_phone']) ? $post['company_phone'] : '',
				'company_site'				=> isset($post['company_site']) ? $post['company_site'] : '',
				'company_activity'		=> isset($post['company_activity']) ? $post['company_activity'] : array(),
				'city'								=> isset($post['city']) ? $post['city'] : '',
				'email'								=> isset($post['email']) ? $post['email'] : '',
				'phone'								=> isset($post['telephone']) ? $post['telephone'] : '',
				'expertise'						=> isset($post['expertise']) ? $post['expertise'] : '',
				'useful'							=> isset($post['useful']) ? $post['useful'] : '',
				'regalia'							=> isset($post['regalia']) ? $post['regalia'] : '',
			);

			if(!empty($post['photo']) && $post['photo'] !== 'delete') {
				$savefile = fopen(DIR_IMAGE . 'catalog/experts/tmp-contact.png', 'w');
				$image = explode(',', $post['photo']);

				fwrite($savefile, base64_decode($image[1]));
				fclose($savefile);

				$image_link = $this->model_themeset_image->original('catalog/experts/tmp-contact.png');

				$user_data['photo'] = $image_link;
			}else if($post['photo'] === 'delete') {
				$user_data['photo'] = '';
			}

			
			// $return_contact = $this->model_register_register->updateContact($user_data);
			$return_contact = $this->model_register_register->createContact($user_data);

			if(!empty($return_contact['code']) && $return_contact['code'] == 200) {
				$contact_id = $return_contact['id'];
				$this->model_register_register->addAlternateId($b24id, $contact_id);

				// $this->model_register_register->updateExpertID($b24id, $contact_id);

				// $this->model_themeset_expert->getContactInfo($contact_id, false, true);

				// $expert_id = $this->model_register_register->getExpertId($b24id);

				$return['redirect'] = $this->url->link('register/account');
				$this->session->data['edit_success'] = true;
				
			}else{

				$return['contact'] = $return_contact;
				$return['error'] = true;
				$return['error_text'] = 'Произошла ошибка. Попробуйте повторить попытку позже';

			}

			

		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($return));
	}
}
