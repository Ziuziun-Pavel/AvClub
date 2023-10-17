<?php

class ControllerExpertExpert extends Controller
{

    private $url_send_mail = "http://clients.techin.by/avclub/site/api/v1/contact/{id}/addComment";

    public function index()
    {
        $this->load->language('expert/expert');

        $this->load->model('visitor/visitor');
        $this->load->model('visitor/expert');
        $this->load->model('company/company');

        $this->load->model('tool/image');
        $this->load->model('themeset/themeset');
        $this->load->model('themeset/image');
        $this->load->model('register/register');

        $data['edit_success'] = !empty($this->session->data['edit_success']) ? $this->session->data['edit_success'] : false;
        unset($this->session->data['edit_success']);


        if (isset($this->request->get['expert_id'])) {
            $expert_id = (int)$this->request->get['expert_id'];
        } else if ($this->request->get['route'] === 'register/account') {
            $expert_id = $this->visitor->getId();
        } else {
            $expert_id = 0;
        }

        $data['expert_id'] = $expert_id;

        $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => 'Эксперты',
            'href' => $this->url->link('expert/list')
        );


        $status_logged = $this->visitor->isLogged() && $expert_id == $this->visitor->getId() ? true : false;

        if ($expert_info && (($status_logged && $this->request->get['route'] === 'register/account') || !empty($expert_info['expert']))) {

            $data['registers'] = array();
            $data['logged'] = $status_logged;
            $data['event_list'] = $status_logged;
            $data['alternate_count'] = $expert_info['alternate_count'];

            $data['edit_info'] = $status_logged ? $this->url->link('register/edit') : '';

            $data['breadcrumbs'][] = array(
                'text' => $expert_info['name'],
                'href' => $this->url->link('expert/expert', 'expert_id=' . $expert_id)
            );


            if ($this->request->get['route'] === 'register/account') {
                /* account page */
                $this->document->setTitle('Личный кабинет АВ Клуба');
                /* # account page */
            } else {
                /* expert page */
                $this->document->setTitle($expert_info['name'] . ' | Эксперт АВ Клуба');

                if ($expert_info['exp']) {
                    $this->document->setDescription($expert_info['exp']);
                }
                /* # expert page */
            }


            if ($expert_info['image'] && is_file(DIR_IMAGE . $expert_info['image'])) {
                $data['image'] = $this->model_tool_image->resize($expert_info['image'], 220, 220);
            } else {
                $data['image'] = $this->model_themeset_image->crop('user_no_avatar.png', 220, 220);
            }

            $data['name'] = $expert_info['name'];

            $data['exp'] = $expert_info['exp'];

            $data['is_email'] = false;
            $emails = explode(',', $expert_info['emails']);
            foreach ($emails as $email) {
                if ($email && preg_match($this->config->get('config_mail_regexp'), $email)) {
                    $data['is_email'] = true;
                    break;
                }
            }

            $data['company'] = array();
            if ($expert_info['company_id']) {
                $company_info = $this->model_company_company->getCompany($expert_info['company_id']);
                if ($company_info) {
                    if ($company_info['image'] && is_file(DIR_IMAGE . $company_info['image'])) {
                        $image = $this->model_tool_image->resize($company_info['image'], 220, 85);
                    } else {
                        $image = '';
                    }
                    $data['company'] = array(
                        'image' => $image,
                        'title' => $company_info['title'],
                        'href' => $this->url->link('company/info', 'company_id=' . $expert_info['company_id'])
                    );
                }
            }

            $data['tags'] = $this->model_visitor_expert->getTagsByExpert($expert_id);
            $data['branches'] = $this->model_visitor_expert->getBranchesByExpert($expert_id);


            /* bio */
            $data['bio'] = array();
            $bio_keys = array(
                'field_useful' => 'В чем могу быть полезен',
                'field_regalia' => 'Заслуги и регалии',
            );
            foreach ($bio_keys as $key => $title) {
                if (!empty($expert_info[$key])) {
                    $data['bio'][] = array(
                        'title' => $title,
                        'text' => $expert_info[$key]
                    );
                }
            }

            $data['tabs'] = array();

            $active_tab = true;
            $content_key = $show_key = 'all';

            if (!empty($this->request->get['tab'])) {
                switch (true) {

                    case in_array($this->request->get['tab'], array('online')):
                        $show_tab = 'master';
                        break;

                    default:
                        $show_tab = $this->request->get['tab'];
                }
            } else {
                $show_tab = '';
            }

            $tabs = $this->model_visitor_expert->getTabsExpert($expert_id);


            if ($tabs) {

                if ($show_tab) {
                    foreach ($tabs as $key => $tab) {
                        if ($key === $show_tab) {
                            $content_key = $show_key = $key;
                        }
                    }
                }

                foreach ($tabs as $key => $tab) {
                    $tab_key = $key;
                    $children = array();

                    switch ($key) {
                        case 'all':
                            $title = 'все';
                            break;
                        case 'news':
                            $title = 'новости';
                            break;
                        case 'article':
                            $title = 'статьи';
                            break;
                        case 'opinion':
                            $title = 'мнения';
                            break;
                        case 'video':
                            $title = 'видео';
                            break;
                        case 'case':
                            $title = 'кейсы';
                            break;
                        case 'online':
                        case 'master':
                            $title = 'онлайн-события';
                            break;
                        case 'avevent':
                        case 'event':
                            $title = 'мероприятия';
                            break;
                        default:
                            $title = '';
                            break;
                    }

                    if (in_array($key, array('master', 'online')) && is_array($tab)) {
                        $active_child = true;

                        if (!$content_key) {
                            $content_key = $show_key = $key;
                        }
                        foreach ($tab as $type) {
                            switch ($type) {
                                case 'master_all':
                                    $child_title = 'Ближайшие';
                                    break;
                                case 'master_master':
                                    $child_title = 'Мастер-классы';
                                    break;
                                case 'master_meetup':
                                    $child_title = 'Митапы';
                                    break;
                                case 'master_old':
                                    $child_title = 'Прошедшие';
                                    if (empty($tab['master'])) {
                                        // $tab_key = 'master_old';
                                    }
                                    break;
                                default:
                                    $child_title = '';
                                    break;
                            }
                            $children[] = array(
                                'key' => $type,
                                'title' => $child_title,
                                'active' => ($active_child ? 'active' : ''),
                                'isactive' => ($active_child ? true : false),
                            );
                            if ($active_child && $show_tab === $key) {
                                $show_key = $type;
                            }
                            $active_child = false;

                        }
                    }

                    if (in_array($key, array('evevent', 'event')) && is_array($tab)) {
                        $active_child = true;

                        if (!$content_key) {
                            $content_key = $show_key = $key;
                        }
                        foreach ($tab as $type) {
                            switch ($type) {
                                case 'event_new':
                                    $child_title = 'Ближайшие';
                                    break;
                                case 'event_old':
                                    $child_title = 'Прошедшие';
                                    break;
                                default:
                                    $child_title = '';
                                    break;
                            }
                            $children[] = array(
                                'key' => $type,
                                'title' => $child_title,
                                'active' => ($active_child ? 'active' : ''),
                                'isactive' => ($active_child ? true : false),
                            );
                            if ($active_child && $show_tab === $key) {
                                $show_key = $type;
                            }
                            $active_child = false;

                        }
                    }

                    if (!$content_key) {
                        $content_key = $show_key = $key;
                    }

                    $data['tabs'][] = array(
                        'key' => $key,
                        'title' => $title,
                        'active' => ($content_key === $key ? 'active load' : ''),
                        'isactive' => ($content_key === $key ? true : false),
                        'children' => $children,
                    );
                    $active_tab = false;
                }
            }

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
                        'href' => $result['link'],
                        'title' => $result['title'],
                        'author' => $result['author'],
                        'exp' => $result['exp'],
                        'date' => $result['date'],
                        'time' => $result['time'],
                    );
                }
            }

            $data['content'] = $this->load->controller('expert/content/content_' . $show_key);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('expert/expert_info', $data));


        } else {

            $this->load->language('error/not_found');

            $url = '';

            if (isset($this->request->get['expert_id'])) {
                $url .= '&expert_id=' . $this->request->get['expert_id'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('expert/expert', $url)
            );

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

    public function getVisited()
    {
        $return = array();
        $data = array();

        session_write_close();


        $this->load->model('register/register');
        $this->load->model('visitor/expert');

        if (isset($this->request->get['expert_id'])) {
            $expert_id = (int)$this->request->get['expert_id'];
        } else {
            $expert_id = 0;
        }

        $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);

        if ($expert_info && $this->visitor->getId() && $this->visitor->getId() == $expert_id) {

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

            $now = strtotime("now");

            $event_list = $this->model_register_register->getVisitList($expert_info['b24id']);

            $sort_forum = array();

            foreach ($event_list as $event_item) {

                $time = strtotime($event_item['date']);

                $statuses = array();

                $statuses[] = array(
                    'text' => 'Заявка на рассмотрении',
                    'active' => true
                );

                if ($event_item['status'] === 'consideration') {
                    $statuses[] = array('text' => 'Участие одобрено', 'active' => false);
                }

                if ($event_item['status'] === 'paid_participation') {
                    $statuses[] = array('text' => 'Предложено платное участие', 'active' => false);
                }

                if (in_array($event_item['status'], array('admitted', 'visited', 'noVisited'))) {
                    $statuses[] = array('text' => 'Участие одобрено', 'active' => true);
                }

                if ($event_item['status'] === 'visited') {
                    $statuses[] = array('text' => 'Успешный визит', 'active' => true);
                }

                if ($event_item['status'] === 'noVisited') {
                    $statuses[] = array('text' => 'Визит не состоялся', 'active' => true);
                }

                foreach ($statuses as $key => &$status) {
                    if ($key == count($statuses) - 2 && !$statuses[count($statuses) - 1]['active']) {
                        $status['preactive'] = true;
                    } else {
                        $status['preactive'] = false;
                    }
                }

                $addresses = array();
                if (!empty($event_item['location'])) {
                    $addresses[] = $event_item['location'];
                }
                if (!empty($event_item['address'])) {
                    $addresses[] = $event_item['address'];
                }

                $event_item_info = array(
                    'type_event' => $event_item['type_event'],
                    'type' => $event_item['type'],
                    'name' => $event_item['name'],
                    'status' => $event_item['status'],
                    'old' => $time < $now ? true : false,
                    'statuses' => $statuses,
                    'date' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time)
                );

                switch ($event_item['type_event']) {
                    case 'webinar':
                        $event_item_info['date'] .= ' ' . date('h:i', $time);
                        $event_item_info['url'] = $event_item['url'];
                        $event_item_info['type_text'] = $time < $now ? 'Прошедший вебинар' : 'Вебинар';
                        break;

                    case 'forum':
                        $event_item_info['link'] = $event_item['ticket_public_url'];
                        $event_item_info['summ'] = $event_item['summ'];
                        $event_item_info['location'] = $event_item['location'];
                        $event_item_info['address'] = $event_item['address'];
                        $event_item_info['addresses'] = $addresses;
                        $event_item_info['type_text'] = $time < $now ? 'Прошедшее офлайн-мероприятие' : 'Офлайн-мероприятие';
                }

                $data['event_list'][] = $event_item_info;

                $sort_forum[] = $time;

            }

            array_multisort($sort_forum, SORT_DESC, $data['event_list']);

            $return['template'] = $this->load->view('expert/expert_events', $data);

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function getFutureEvents()
    {
        sleep(3);
        $return = array();
        $data = array();

        session_write_close();

        $this->load->model('register/register');
        $this->load->model('visitor/expert');

        if (isset($this->request->get['expert_id'])) {
            $expert_id = (int)$this->request->get['expert_id'];
        } else {
            $expert_id = 0;
        }

        $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);

        if ($expert_info && $this->visitor->getId() && $this->visitor->getId() == $expert_id) {
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

            $now = strtotime("now");

            $event_list = $this->model_register_register->getFutureEvents($expert_info['b24id']);

            $sort_forum = array();

            foreach ($event_list as $event_item) {
                $time = strtotime($event_item['date']);

                $statuses = array();

                $statuses[] = array(
                    'text' => 'Заявка на рассмотрении',
                    'active' => true
                );

                if ($event_item['status'] === 'consideration') {
                    $statuses[] = array('text' => 'Участие одобрено', 'active' => false);
                }

                if ($event_item['status'] === 'paid_participation') {
                    $statuses[] = array('text' => 'Предложено платное участие', 'active' => false);
                }

                if (in_array($event_item['status'], array('admitted', 'visited', 'noVisited'))) {
                    $statuses[] = array('text' => 'Участие одобрено', 'active' => true);
                }

                if ($event_item['status'] === 'visited') {
                    $statuses[] = array('text' => 'Успешный визит', 'active' => true);
                }

                if ($event_item['status'] === 'noVisited') {
                    $statuses[] = array('text' => 'Визит не состоялся', 'active' => true);
                }

                foreach ($statuses as $key => &$status) {
                    if ($key == count($statuses) - 2 && !$statuses[count($statuses) - 1]['active']) {
                        $status['preactive'] = true;
                    } else {
                        $status['preactive'] = false;
                    }
                }

                $addresses = array();
                if (!empty($event_item['location'])) {
                    $addresses[] = $event_item['location'];
                }
                if (!empty($event_item['address'])) {
                    $addresses[] = $event_item['address'];
                }

                $event_item_info = array(
                    'type_event' => $event_item['type_event'],
                    'type' => $event_item['type'],
                    'name' => $event_item['name'],
                    'price' => $event_item['price'],
                    'status' => $event_item['status'],
                    'about_url' => $event_item['about_url'],
                    'old' => false,
                    'statuses' => $statuses,
                    'date' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time)
                );

                switch ($event_item['type_event']) {
                    case 'webinar':
                        $event_item_info['date'] .= ' ' . date('h:i', $time);
                        $event_item_info['url'] = $event_item['url'];
                        $event_item_info['type_text'] = $time < $now ? 'Прошедший вебинар' : 'Вебинар';
                        break;

                    case 'forum':
                        $event_item_info['link'] = $event_item['ticket_public_url'];
                        $event_item_info['summ'] = $event_item['summ'];
                        $event_item_info['location'] = $event_item['location'];
                        $event_item_info['address'] = $event_item['address'];
                        $event_item_info['addresses'] = $addresses;
                        $event_item_info['type_text'] = $time < $now ? 'Прошедшее офлайн-мероприятие' : 'Офлайн-мероприятие';
                }

                $data['event_list'][] = $event_item_info;

                $sort_forum[] = $time;

            }

            array_multisort($sort_forum, SORT_DESC, $data['event_list']);

            $return['template'] = $this->load->view('expert/expert_future_events', $data);

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function edit()
    {

    }

    public function sendMail()
    {

        $this->load->model('visitor/expert');

        $expert_id = !empty($this->request->post['expert_id']) ? $this->request->post['expert_id'] : 0;

        $expert_info = $this->model_visitor_expert->getExpert($expert_id);

        $b24id = !empty($expert_info['b24id']) ? $expert_info['b24id'] : 0;

        if ($expert_info && $b24id) {

            $send_data = !empty($this->request->post) ? $this->request->post : array();
            $send_data['visitor_id'] = $expert_id;
            $send_data['emails'] = $expert_info['emails'];

            $this->model_visitor_expert->addExpertMail($send_data);

            $email_subject = 'Сообщение с сайта ' . $this->config->get('config_name');

            $mail = new Mail();
            $mail->protocol = $this->config->get('av_alert_mail_protocol');
            $mail->parameter = $this->config->get('av_alert_mail_parameter');
            $mail->smtp_hostname = $this->config->get('av_alert_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('av_alert_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('av_alert_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('av_alert_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('av_alert_mail_smtp_timeout');

            if (!empty($send_data['email'])) {
                $mail->setReplyTo($send_data['email']);
            }
            $mail->setFrom($this->config->get('av_alert_mail_protocol') === 'smtp' ? $this->config->get('av_alert_mail_smtp_username') : $this->config->get('av_alert_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject($email_subject);

            $text = '';
            $html = '';

            if (isset($this->request->post['name'])) {
                $html .= '<strong>Имя: </strong> ' . $this->request->post['name'] . "<br>";
                $text .= 'Имя:  ' . $this->request->post['name'] . "\n\n";
            }

            if (isset($this->request->post['company'])) {
                $html .= '<strong>Компания: </strong> ' . $this->request->post['company'] . "<br>";
                $text .= 'Компания:  ' . $this->request->post['company'] . "\n\n";
            }
            if (isset($this->request->post['phone'])) {
                $html .= '<strong>Телефон: </strong> ' . $this->request->post['phone'] . "<br>";
                $text .= 'Телефон:  ' . $this->request->post['phone'] . "\n\n";
            }
            if (isset($this->request->post['email'])) {
                $html .= '<strong>E-mail: </strong> ' . $this->request->post['email'] . "<br>";
                $text .= 'E-mail:  ' . $this->request->post['email'] . "\n\n";
            }
            if (isset($this->request->post['web'])) {
                $html .= '<strong>Сайт: </strong> ' . $this->request->post['web'] . "<br>";
                $text .= 'Сайт:  ' . $this->request->post['web'] . "\n\n";
            }
            if (isset($this->request->post['message'])) {
                $html .= '<strong>Сообщение: </strong> ' . $this->request->post['message'] . "<br>";
                $text .= 'Сообщение:  ' . $this->request->post['message'] . "\n\n";
            }


            // $text .= "<br>" ;
            $mail->setText($text);
            $mail->setHtml($html);

            $emails = explode(',', $expert_info['emails']);

            foreach ($emails as $email_item) {
                if ($email_item && preg_match($this->config->get('config_mail_regexp'), $email_item)) {
                    $mail->setTo($email_item);
                    $mail->send();
                }
            }

            $url = str_replace('{id}', $b24id, $this->url_send_mail);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
                "message" => $text
            )));
            $body = curl_exec($ch);
            curl_close($ch);

            $return = json_decode($body, true);

            $json['success'] = 'success';

        } else {
            $json['error'] = 'Эксперт не найден!';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
