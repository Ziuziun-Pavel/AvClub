<?php

class ControllerRegisterCompany extends Controller
{

    private $company_activity = array(
        'Производитель',
        'Системная интеграция',
        'Поставка и дистрибуция AV-оборудования',
        'Установка и обслуживание',
        'Прокат, аренда аудиовизуальных систем',
        'Пользователь AV-решений',
        'Другое'
    );

    public function getCompanyActivity()
    {
        return $this->company_activity;
    }

    public function searchCompanies()
    {

        $this->load->model('register/register');
        $this->load->model('themeset/themeset');

        include "MyDadata.php";
        $MyDadata = new MyDadata('b204332cfe2b76ee801c230822401a1f9cd7f07b', '587e2cfc77138f458e3cfed1843dc6fdecb9e0b7');
        $MyDadata->init();

        $return = array();
        $data = array();
        $error = false;

        $company_name = !empty($this->request->post['company_name']) ? $this->request->post['company_name'] : '';
        $country = !empty($this->request->post['country']) ? $this->request->post['country'] : '';

        if (!$company_name && !$country) {
            $error = true;
        }

        if (!$error) {

            $data['search'] = $company_name;
            $data['country'] = $country;
            $data['companies'] = array();
            $this->session->data["register_event"]['company_country'] = $data['country'];
            $filter_data = array(
                'filter_name' => $company_name,
                'start' => 0,
                'limit' => 10
            );

            $dadata_results = [];
            $results = [];

            switch ($data['country'])
            {
                case 'Россия':
                    $dadata_results = $MyDadata->findRuCompany($company_name)['suggestions'];
                    if (!empty($dadata_results)) {
                        $data['dadata'] = true;
                        foreach ($dadata_results as $result) {
                            $data['companies'][] = array(
                                'title' => $result['data']['name']['short_with_opf'],
                                'manager' => $result['data']['management']['name'],
                                'inn' => $result['data']['inn'],
                                'address' => $result['data']['address']['value'],
                            );
                        }
                    }
                    break;
                case 'Беларусь':
                    $dadata_results = $MyDadata->findByCompany($company_name)['suggestions'];
                    if (!empty($dadata_results)) {
                        $data['dadata'] = true;
                        foreach ($dadata_results as $result) {
                            $data['companies'][] = array(
                                'title' => $result['data']['short_name_ru'],
                                'manager' => $result['data']['management']['name'],
                                'unp' => $result['data']['unp'],
                                'address' => $result['data']['address'],
                            );
                        }
                    }
                    break;
                case 'Казахстан':
                    $dadata_results = $MyDadata->findKzCompany($company_name)['suggestions'];
                    if (!empty($dadata_results)) {
                        $data['dadata'] = true;
                        foreach ($dadata_results as $result) {
                            $data['companies'][] = array(
                                'title' => $result['data']['name_ru'],
                                'manager' => $result['data']['fio'],
                                'bin' => $result['data']['bin'],
                                'address' => $result['data']['address_ru'],
                            );
                        }
                    }
                    break;
                default:
                    $results = $this->model_register_register->getCompanyNames($filter_data);
                    if (!empty($results)) {
                        $data['dadata'] = false;
                        foreach ($results as $result) {
                            $data['companies'][] = array(
                                'b24id' => !empty($result['b24id']) ? $result['b24id'] : 0,
                                'id' => !empty($result['b24id']) ? $result['b24id'] : 'new',
                                'title' => $result['name']
                            );
                        }
                    }
                    break;
            }

            if ($data['companies']) {
                $data['text_result'] = 'По запросу <strong>“' . $company_name . '”</strong> ' . $this->model_themeset_themeset->trueText(count($data['companies']), 'найдена', 'найдено', 'найдено') . ' ' . count($data['companies']) . ' ' . $this->model_themeset_themeset->trueText(count($data['companies']), 'компания', 'компании', 'компаний');
                $return['template'] = $this->load->view('register/_brand_results', $data);
            } else {

                $data['activity'] = $this->company_activity;
                $data['company_info'] = array(
                    'b24_company_old_id' => 0,
                    'b24_company_id' => 0,
                    'city' => '',
                    'web' => '',
                    'phone' => '',
                    'activity' => '',
                    'search' => $company_name
                );
                $return['template'] = $this->load->view('register/_brand_data', $data);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function changeSearch()
    {
        $this->load->model('company/company');

        $return = array();
        $data = array();

        $data['countries'] = $this->model_company_company->getListOfCountries();

        $data['brand_search'] = !empty($this->request->post['search']) ? $this->request->post['search'] : '';

        $return['template'] = $this->load->view('register/_brand_main', $data);


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function chooseCompany()
    {

        $this->load->model('register/register');

        $return = array();
        $data = array();

        $b24id = !empty($this->request->post['b24id']) ? $this->request->post['b24id'] : '';
        $company_name = !empty($this->request->post['company_name']) ? $this->request->post['company_name'] : '';
        $company_inn = !empty($this->request->post['company_inn']) ? $this->request->post['company_inn'] : '';
        $company_address = !empty($this->request->post['company_address']) ? $this->request->post['company_address'] : '';
        $site = substr(strstr($this->session->data["register_user"]["email"], '@'), 1);

        $this->session->data["register_event"]['company_inn'] = $company_inn;

        $data['company_info'] = array();

        $data['activity'] = $this->company_activity;

        $filter_data = array(
            'filter_start' => 0,
            'filter_limit' => 1
        );

        if ($b24id) {
            $filter_data['filter_b24id'] = $b24id;
        } else {
            $filter_data['filter_name'] = $company_name;
            $filter_data['filter_inn'] = $company_inn;
            $filter_data['filter_address'] = $company_address;
            $filter_data['filter_site'] = $site;
        }

        $results = $this->model_register_register->getCompanyNames($filter_data);

        if (!empty($results)) {

            $company_info = $results[0];

            $data['company_info'] = array(
                'b24_company_old_id' => $company_info['b24id'],
                'b24_company_id' => $company_info['b24id'],
                'title' => $company_info['name'],
                'city' => $company_info['city'],
                'web' => $company_info['web'],
                'phone' => $company_info['phone'],
                'activity' => $company_info['activity'],
                'search' => $company_info['name'],
            );

            $return['template'] = $this->load->view('register/_brand_data_noedit', $data);

        } else {
            $data['activity'] = $this->company_activity;
                $data['company_info'] = array(
                    'b24_company_old_id' => 0,
                    'b24_company_id' => 0,
                    'city' => '',
                    'web' => '',
                    'phone' => '',
                    'activity' => '',
                    'search' => $company_name
                );
            $return['template'] = $this->load->view('register/_brand_data', $data);

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function addNewCompany()
    {

        $this->load->model('register/register');
        $this->load->model('themeset/themeset');

        $return = array();
        $data = array();
        $error = false;

        $company_name = !empty($this->request->post['search']) ? $this->request->post['search'] : '';
        $company_second_choice = !empty($this->request->post['company_second_choice']) ? true : false;

        if (!$company_name) {
            $error = true;
        }

        if (!$error) {

            $data['search'] = $company_name;

            $data['activity'] = $this->company_activity;
            $data['company_info'] = array(
                'b24_company_old_id' => 0,
                'b24_company_id' => 0,
                'city' => '',
                'web' => '',
                'phone' => '',
                'activity' => '',

                'search' => $company_name
            );

            $user_data = $this->session->data["register_user"];

            if ($company_second_choice) {
                $data['company_info'] = array(
                    'b24_company_old_id' => 0,
                    'b24_company_id' => 0,
                    'city' => $user_data['city'],
                    'web' => $user_data['company_site'],
                    'phone' => $user_data['company_phone'],
                    'activity' => $user_data['company_activity'],
                    'search' => $company_name
                );
                $data['isCompanyChanged'] = true;
            }

            $return['template'] = $this->load->view('register/_brand_data', $data);

        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function companyProfile()
    {
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

        if (!$expert_id || !$b24id) {
            header("Location: " . $this->url->link('register/login'));
            exit();
        }

        $data['expert_id'] = $expert_id;
        $data['deal_id'] = $_GET["id"];

        $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);

        $data['contact_id'] = $b24id;

        if ($expert_info) {

            $data['alternate_count'] = $expert_info['alternate_count'];

            $contact_info = $this->model_register_register->getContactInfo($b24id);

            $data['placeholder'] = $this->model_themeset_image->crop('placeholder_empty.png', 220, 220);
            if ($expert_info['image'] && is_file(DIR_IMAGE . $expert_info['image'])) {
                $data['image'] = $this->model_themeset_image->original($expert_info['image'], 220, 220);
            } else {
                $data['image'] = $data['placeholder'];
            }

            $user_data = array(
                'name' => $contact_info['NAME'],
                'lastname' => $contact_info['LAST_NAME'],
                'exp' => $contact_info['POST'],
                'telephone' => !empty($contact_info['PHONE']) ? current($contact_info['PHONE']) : '',
                'email' => !empty($contact_info['EMAIL']) ? current($contact_info['EMAIL']) : '',
                'expertise' => $contact_info['UF_CRM_1686648613'],
                'useful' => $contact_info['UF_CRM_1686648651'],
                'regalia' => $contact_info['UF_CRM_1686648672'],
                'b24_company_id' => !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : 0,
                'b24_company_old_id' => !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : 0,
                'company' => '',
                'company_status' => !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : '',
                'company_phone' => '',
                'company_site' => '',
                'company_activity' => '',
                'city' => '',
            );

            $data['user'] = $user_data;

            $data['back'] = '/account/';

            $this->load->model('tag/tag');

            $results = $this->model_tag_tag->getTags();

            foreach ($results as $result) {
                $data['tags'][] = array(
                    'tag_id' => $this->model_tag_tag->getB24IdByTagDescriptions($result['title']),
                    'title' => $result['title'],
                );
            }

            $data['activities'] = $this->load->controller('register/company/getCompanyActivity');

            // BANNER
            $data['banner'] = array();
            $banner_info = $this->model_themeset_themeset->getBanner('content');
            if ($banner_info && $banner_info['image_pc'] && is_file(DIR_IMAGE . $banner_info['image_pc'])) {

                $data['banner'] = array(
                    'banner_id' => $banner_info['banner_id'],
                    'link' => $banner_info['link'],
                    'target' => $banner_info['target'],
                    'image' => $this->model_themeset_themeset->resize_crop($banner_info['image_pc'], 100, 100),
                );
            }

            // master
            $master_info = $this->config->get('av_master');
            $data['master_info'] = array(
                'title' => $master_info['master_title'],
                'description' => $master_info['master_description'],
                'link' => $master_info['master_link'],
                'button' => $master_info['master_button'],
            );
            $data['master_all'] = $this->url->link('master/master');
            $this->load->model('master/master');
            $data['master_list'] = array();

            $filter_data = array(
                'start' => 0,
                'limit' => 3
            );

            $results = $this->model_master_master->getMasters($filter_data);
            if ($results) {
                foreach ($results as $result) {
                    $data['master_list'][] = array(
                        'master_id' => $result['master_id'],
                        'href' => $this->url->link('master/master/info', 'master_id=' . $result['master_id']),
                        'title' => $result['title'],
                        'author' => $result['author'],
                        'exp' => $result['exp'],
                        'date' => $result['date'],
                        'time' => $result['time'],
                    );
                }
            }

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('register/company_profile_add', $data));


        } else {

            header('Location: ' . $this->url->link('register/login'));
        }
    }

    public function addCompanyProfile()
    {
        $return = array();
        $data = $this->request->post["data"];

        $data_company = [
            'company_name' => $data['company_name'],
            'company_site' => $data['company_site'],
            'company_city' => $data['company_city'],
            'company_phone' => $data['company_phone'],
            'company_email' => $data['company_email'],
            'company_activity' => $data['company_activity'],
            'company_description' => $data['company_description'],
            'company_initial_logo' => $data['company_initial_logo'],
            'company_inn' => (int)$data['company_inn'],
            'company_tag_product' => [(int)$data['company_tag_product'][0]],
            'company_tag_industry' => [(int)$data['company_tag_industry'][0]],
            'company_alternative_names' => $data['company_alternative_names'],
            'company_bank_name' => $data['company_bank_name'],
            'company_bik' => $data['company_bik'],
            'company_acc_num' => $data['company_acc_num'],
            'company_cor_acc_num' => $data['company_cor_acc_num'],
        ];

        $data_deal = [
            'dealType' => 'catalog',
            'company_id' => 0,
            'contact_id' => (int)$data['contact_id'],
            'currency' => $data['currency']
        ];

        session_write_close();

        $this->load->model('visitor/expert');
        $this->load->model('company/company');

        if (isset($this->request->post['expert_id'])) {
            $expert_id = (int)$this->request->post['expert_id'];
        } else {
            $expert_id = 0;
        }

        if ($data && $this->visitor->getId() && (int)$this->visitor->getId() == $expert_id) {
            $json = $this->model_company_company->addCompanyProfile($data_company, $data_deal);

            $return['message'] = $json["message"];
            $return['code'] = $json["code"];

            if ($return['code'] !== 200) {
                $return['error'] = true;
            }

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function uploadImage()
    {
        header('Content-Type: application/json');
        $data = $_REQUEST;

        $this->load->model('tool/image');

        $image_publikations_dir = DIR_IMAGE . 'catalog/companies/';

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
            $url = $protocol . $host . '/image/catalog/companies/' . $originalName;

            $response = [
                'url' => $url,
                'name' => $originalName,
            ];

            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Ошибка при загрузке файла.']);
        }
    }

}
