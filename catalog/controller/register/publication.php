<?php
class ControllerRegisterPublication extends Controller {
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
        $data['deal_id'] = $_GET["id"];

        $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);

		if($expert_info) {

			$data['alternate_count'] = $expert_info['alternate_count'];

            $data['paidPublicationId'] = $this->getPaidPublications();

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

            $data_company['countries'] = $this->model_company_company->getListOfCountries();

			$data['company_template'] = $this->load->view('register/_brand_main', $data_company);

			// $data['back'] = $this->url->link('expert/expert', 'expert_id=' . $expert_id);
			$data['back'] = '/account/';

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
						'href'        => $this->url->link('master/master/info', 'master_id=' . $result['master_id']),
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

			$this->response->setOutput($this->load->view('register/publication', $data));


		}else {
			
			header('Location: ' . $this->url->link('register/login'));
		}


	}

    public function uploadImage()
    {
        header('Content-Type: application/json');
        $data = $_REQUEST;

        $this->load->model('tool/image');

        $image_publikations_dir = DIR_IMAGE . 'catalog/publikacii/uploads/';

        if (!file_exists($image_publikations_dir)) {
            mkdir($image_publikations_dir, 0755, true);
        }

        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $tempName = $_FILES['file']['tmp_name'];
            $originalName = basename($_FILES['file']['name']);

            $image_path = $image_publikations_dir . $originalName;

            move_uploaded_file($tempName, $image_path);

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'];
            $url = $protocol . $host . '/image/catalog/publikacii/uploads/' . $originalName;

            $response = [
                'url' => $url,
                'name' => $originalName,
            ];

            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Ошибка при загрузке файла.']);
        }
    }

    private function getPaidPublications()
    {
        $return = array();
        $data = array();

        session_write_close();

        $this->load->language('expert/expert');

        $this->load->model('company/company');
        $this->load->model('tool/image');
        $this->load->model('themeset/themeset');
        $this->load->model('themeset/expert');
        $this->load->model('register/register');

        $this->load->model('visitor/expert');

        $expert_id = $this->visitor->getId();
        $b24id = $this->visitor->getB24id();

        $data['expert_id'] = $expert_id;

        $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);

        $data['contact_id'] = $expert_info["b24id"];

        if ($expert_info) {

            $paid_publications = $this->model_register_register->getPaidPublications($data['contact_id']);

//            foreach ($paid_publications as $publication) {
//                $data["paid_publications"][] = [
//                    "id" => $publication['id'],
//                    "title" => $publication['title'],
//                    "link" => 'publication?' . 'id=' . $publication['id']
//                ];
//            }
            $return = (int)$paid_publications[0]['id'];
        } else {
            $return = null;
        }

        return $return;

    }

}
