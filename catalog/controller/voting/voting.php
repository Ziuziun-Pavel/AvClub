<?php
class ControllerVotingVoting extends Controller {
    public function index() {
        $this->load->model('voting/voting');
        $this->load->model('journal/journal');
        $this->load->model('tool/image');
        $this->load->model('themeset/themeset');
        $this->load->model('visitor/expert');

        if(!$this->visitor->isLogged()) {
            header("Location: " . $this->url->link('register/account'));
            exit();
        } else {
            $expert_id = $this->visitor->getId();

            $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);
            $data['contact_id'] = $expert_info['b24id'];
        }

        $data['expert_id'] = $expert_id;

        if (isset($this->request->get['quiz_id'])) {
            $quiz_id = $this->request->get['quiz_id'];
        } else {
            $quiz_id = null;
        }
        $data['quiz_id'] = $quiz_id;

        $results = $this->model_voting_voting->getNominees($data['contact_id'], $quiz_id);

        if (isset($this->request->get['title'])) {
            $title = $this->request->get['title'];
        } else {
            $title = $results['quiz']['title'];
        }
        $data['title'] = $title;

        $this->document->setTitle('Премия ' . $title);
        $this->document->setDescription('Премия ' . $title);
        $this->document->setKeywords('Премия ' . $title);

        $data['heading_title'] = 'Премия ' . $title;

        // Сортировка: сначала по grade (в убывающем порядке), потом по name (в алфавитном порядке)
        usort($results["nominee"], function($a, $b) {
            // Сравнение по grade (пустое значение считается 0)
            $gradeA = isset($a['grade']) ? (int)$a['grade'] : 0;
            $gradeB = isset($b['grade']) ? (int)$b['grade'] : 0;

            if ($gradeA !== $gradeB) {
                // Сортировка по grade по убыванию
                return $gradeA - $gradeB;
            }

            // Если grade одинаковые, сравниваем по name (по возрастанию)
            return strcmp($a['name'], $b['name']);
        });



        // nominees
        $data['nominees'] = array();
        $data['group_filter'] = array();

        $size_width = $this->config->get('av_size_master_width');
        $size_height = $this->config->get('av_size_master_height');

        if (!$quiz_id) {
            $quiz_id = $results['quiz']['quiz_id'];
        }

        foreach ($results["nominee"] as $result) {
            if (!in_array($result['group'], $data['group_filter'])) {
                $data['group_filter'][] = $result['group'];
            }

            if ($result['image']) {
                $image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height);
            } else {
                $image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
            }

            $data['nominees'][] = array(
                'nominee_id' 	 	=> $result['id'],
                'image'     	  => $image,
                'name'       	=> $result['name'],
                'max_grade'       => $result['max_grade'],
                'grade'       		=> isset($result['grade']) ? current($result['grade']) : null,
                //'href'        	=> $this->url->link('voting/voting/info', 'nominee_id=' . $result['id'])
                'href'        	=>  $result['link'],
                'group'        => $result['group']
            );
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

        // telegram
        $data['telegram'] = array(
            'status'	=> $this->config->get('journal_telegram_status'),
            'link'	=> $this->config->get('journal_telegram_link'),
            'text'	=> $this->config->get('journal_telegram_text'),
        );


        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('voting/voting_list', $data));

    }

    public function info() {
        $this->load->language('master/master');
        $this->load->model('voting/voting');
        $this->load->model('journal/journal');
        $this->load->model('tool/image');
        $this->load->model('themeset/themeset');
        $this->load->model('visitor/expert');

        $_SESSION['back_url'] = $_SERVER['REQUEST_URI'];
        if(!$this->visitor->isLogged()) {
            header('Location: ' . $this->url->link('register/account'));
            exit();
        } else {
            $expert_id = $this->visitor->getId();

            $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);
            $data['contact_id'] = $expert_info['b24id'];
        }

        $data['expert_id'] = $expert_id;

        if (isset($this->request->get['nominee_id'])) {
            $nominee_id = $this->request->get['nominee_id'];
        } else {
            $nominee_id = null;
        }
        $data['nominee_id'] = $nominee_id;

        $results = $this->model_voting_voting->getNominees($data['contact_id'], null, $nominee_id);

        if (isset($this->request->get['quiz_id'])) {
            $quiz_id = $this->request->get['quiz_id'];
        } else {
            $quiz_id = $results['quiz']['quiz_id'];
        }

        $data['quiz_id'] = $nominee_id;

        if (isset($this->request->get['title'])) {
            $title = $this->request->get['title'];
        } else {
            $title = $results['quiz']['title'];
        }

        $data['title'] = $title;

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => 'Премия ' . $title,
            'href' => $this->url->link('voting/voting', '&quiz_id=' . $quiz_id)
        );

        // nominee
        $data['nominee'] = array();

        $nominee_info = current($results["nominee"]);

        if (!empty($nominee_info)) {
            $nominee_info['grade'] = current($nominee_info['grade']);

            $data['nominee'] = $nominee_info;

            $this->document->setTitle($nominee_info['name']);
            $this->document->setDescription($nominee_info['name']);
            $this->document->setKeywords($nominee_info['name']);

            $data['heading_title'] = $nominee_info['name'];
            $data['link'] = $nominee_info['link'];

            if ($nominee_info['journal_info']['image'] && is_file(DIR_IMAGE . $nominee_info['journal_info']['image'])) {
                $data['image'] = $this->model_themeset_themeset->resize_crop($nominee_info['journal_info']['image']);
                $this->document->setOgImage($data['image']);
            } else {
                $data['image'] = '';
            }

            //echo '<pre>';
            //print_r($nominee_info);

            if ($nominee_info['journal_info']['image'] && ($nominee_info['journal_info']['image_show'] || ($nominee_info['journal_info']['video'] && $nominee_info['journal_info']['type'] === 'video'))) {
                $data['thumb'] = $this->model_themeset_themeset->resize_crop($nominee_info['journal_info']['image']);
                $this->document->setOgImage($data['thumb']);
            } else {
                $data['thumb'] = '';
            }

            $data['description'] = html_entity_decode($nominee_info['journal_info']['description'], ENT_QUOTES, 'UTF-8');

            $month_list = array(
                1 => 'января',
                2 => 'февраля',
                3 => 'марта',
                4 => 'апреля',
                5 => 'мая',
                6 => 'июня',
                7 => 'июля',
                8 => 'августа',
                9 => 'сентября',
                10 => 'октября',
                11 => 'ноября',
                12 => 'декабря'
            );

            $time = strtotime($nominee_info['date_end']);

            $data['date'] = date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('voting/voting_info', $data));
        } else {
            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['header'] = $this->load->controller('common/header');
            $data['footer'] = $this->load->controller('common/footer');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

    public function addGrade()
    {
        $this->load->model('visitor/expert');

        $return = array();
        $data = $_REQUEST;

        session_write_close();

        $this->load->model('voting/voting');

        $nominee_id = !empty($data['nominee_id']) ? $data['nominee_id'] : 0;
        $quiz_id = !empty($data['quiz_id']) ? $data['quiz_id'] : 0;
        $grade = !empty($data['grade']) ? $data['grade'] : 0;
        $comment = !empty($data['comment']) ? $data['comment'] : '';

        $expert_info = $this->model_visitor_expert->getExpert($data['contact_id'], 0, false);
        $contact_id = $expert_info['b24id'];

        $response = $this->model_voting_voting->addGradeToQuiz($contact_id, $nominee_id, $quiz_id, $grade, $comment);

        if ($response) {
            $response['success'] = true;
        } else {
            $response['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');

        $this->response->setOutput(json_encode($response));

    }
}
