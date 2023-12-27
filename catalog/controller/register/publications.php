<?php

class ControllerRegisterPublications extends Controller
{
    public function index()
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

        $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);

        $data['contact_id'] = $expert_info["b24id"];

        if ($expert_info) {

            $data['back'] = $this->url->link('register/account');

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

            $this->response->setOutput($this->load->view('register/publication_list', $data));


        } else {

            header('Location: ' . $this->url->link('register/login'));
        }


    }

    public function getPubApplications()
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

            $pub_applications = $this->model_register_register->getPubApplications($data['contact_id']);

            foreach ($pub_applications as $app) {
                $dateTime = new DateTime($app['date']);

                $data["pub_applications"][] = [
                    'title' => $app['title'],
                    'link' => $app['link'],
                    'date' => $dateTime->format('d.m.Y'),
                    'message' => $app['message'],
                    'reports' => $app['reports'],
                    'status' => $app['status']
                ];
            }

            $return['template'] = $this->load->view('register/pub_applications', $data);

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function getPaidPublications()
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

            foreach ($paid_publications as $publication) {
                $data["paid_publications"][] = [
                    "id" => $publication['id'],
                    "title" => $publication['title'],
                    "link" => 'publication?' . 'id=' . $publication['id']
                ];
            }

            $return['template'] = $this->load->view('register/paid_publications', $data);

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }
}
