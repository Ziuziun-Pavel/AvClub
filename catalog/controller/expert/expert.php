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
        $is_expert = $this->model_visitor_expert->isExpert(0, $expert_id);

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
            $is_modified = $this->model_visitor_expert->isModified($expert_info['b24id']) === "1";

            $data['registers'] = array();
            $data['logged'] = $status_logged;
            $data['event_list'] = $status_logged;
            $data['alternate_count'] = $expert_info['alternate_count'];
            $data['is_expert'] = $is_expert;
            $data['is_modified'] = $is_modified;

            $data['edit_info'] = $status_logged ? $this->url->link('register/edit') : '';
            $data['publication'] = $status_logged ? $this->url->link('register/publication') : '';
            $data['company_add_href'] = $status_logged ? $this->url->link('register/company/companyProfile') : '';

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
            $data['show_tab'] = $show_tab;

            $tabs = $this->model_visitor_expert->getTabsExpert($expert_id);

            if ($tabs) {
                $data['show_tab'] = 'speeches';

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
            if ($this->visitor->getId()) {
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

    public function getRegistrations()
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

        $event_type = $this->request->get['event_type'];

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

            $event_list = [];

            if ($event_type === 'forum') {
                $event_list = $this->model_visitor_expert->getRegistrations($expert_info['b24id'], 'forum')['list'];
            } else {
                $event_list = $this->model_visitor_expert->getRegistrations($expert_info['b24id'], 'webinar')['list'];
            }

            $start_index = 0;
            $length = 3;

            $event_list = array_slice($event_list, $start_index, $length);

            $sort_forum = array();

            foreach ($event_list as $event_item) {
                $time = strtotime($event_item['date']);

                $statuses = array();
                $active = false;

                if ($event_item['status'] === 'invited') {
                    $statuses[] = array(
                        'text' => 'Выслано приглашение',
                        'active' => false,
                        'preactive' => false
                    );
                } else {
                    $statuses[] = array(
                        'text' => 'Заявка на рассмотрении',
                        'active' => false,
                        'preactive' => false
                    );
                }

                if ($event_item['status'] === 'consideration') {
                    $statuses[] = array('text' => 'Участие одобрено', 'active' => false, 'preactive' => true);
                }

                if ($event_item['status'] === 'paid_participation') {
                    $statuses[] = array('text' => 'Предложено платное участие', 'active' => false, 'preactive' => true);
                }

                if (in_array($event_item['status'], array('admitted', 'visited', 'noVisited'))) {
                    $statuses[] = array('text' => 'Участие одобрено', 'active' => true, 'preactive' => true);
                }

                if ($event_item['status'] === 'visited') {
                    $statuses[] = array('text' => 'Успешный визит', 'active' => true, 'preactive' => true);
                }

                if ($event_item['status'] === 'noVisited') {
                    $statuses[] = array('text' => 'Визит не состоялся', 'active' => true, 'preactive' => true);
                }

                if ($event_item['status'] === 'noVisited') {
                    $statuses[] = array('text' => 'Визит не состоялся', 'active' => true, 'preactive' => true);
                }

                $addresses = array();
                if (!empty($event_item['location'])) {
                    $addresses[] = $event_item['location'];
                }
                if (!empty($event_item['address'])) {
                    $addresses[] = $event_item['address'];
                }

                $event_item_info = array(
                    'type_event' => $event_type,
                    'id' => $event_item["id"],
                    'landing_url' => $event_item["landing_url"],
                    'type' => $event_item['type'],
                    'name' => $event_item['name'],
                    'status' => $event_item['status'],
                    'old' => $time < $now ? true : false,
                    'statuses' => $statuses,
                    'date' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time)
                );

                switch ($event_type) {
                    case 'webinar':
                        $event_item_info['date'] .= ' ' . date('H:i', $time);
                        $event_item_info['url'] = $event_item['url'];
                        $event_item_info['type_text'] = $time < $now ? 'Прошедший онлайн-событие' : 'Онлайн-событие';
                        break;

                    case 'forum':
                        $date_stop = $event_item['date_end'] ? strtotime($event_item['date_end']) : $time;
                        $date_month = $month_list[(int)date('m', $time)];

                        $date_stop_month = $month_list[(int)date('m', $date_stop)];

                        $event_item_info['link'] = $event_item['ticket_public_url'];

                        $event_item_info['date'] = date('d', $time);

                        $event_item_info['date_stop'] = date('d', $date_stop);

                        $event_item_info['date_month'] = $date_month;
                        $event_item_info['date_year'] = date('Y', $time);
                        $event_item_info['date_stop_month'] = $date_stop_month;

                        $event_item_info['ticket_public_url'] = $event_item['ticket_public_url'];
                        $event_item_info['qr'] = $event_item['qr'];
                        $event_item_info['location'] = $event_item['location'];
                        $event_item_info['address'] = $event_item['address'];
                        $event_item_info['addresses'] = $addresses;
                        $event_item_info['type_text'] = $time < $now ? 'Прошедшее офлайн-мероприятие' : 'Офлайн-мероприятие';
                }

                $data['event_list'][] = $event_item_info;

                $sort_forum[] = $time;

            }

            array_multisort($sort_forum, SORT_ASC, $data['event_list']);
            $return['template'] = $this->load->view('expert/expert_events', $data);

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function confirmParticipation()
    {
        $this->load->model('register/register');

        $deal_id = !empty($this->request->get['deal_id']) ? $this->request->get['deal_id'] : 0;

        $type = !empty($this->request->get['type']) ? $this->request->get['type'] : '';

        $response = $this->model_register_register->confirmParticipation($deal_id, $type);

        if ($response) {
            $response['success'] = true;
        } else {
            $response['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
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

    public function sendPublication()
    {
        $return = array();
        $data = $this->request->post["data"];

        session_write_close();

        $this->load->model('visitor/expert');

        if (isset($this->request->post['expert_id'])) {
            $expert_id = (int)$this->request->post['expert_id'];
        } else {
            $expert_id = 0;
        }

        if (isset($this->request->post['deal_id'])) {
            $deal_id = (int)$this->request->post['deal_id'];
        } else {
            $deal_id = 0;
        }

        $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);
        $data['contact_id'] = $expert_info['b24id'];
        $data['deal_id'] = $deal_id;

        if ($data && $this->visitor->getId() && (int)$this->visitor->getId() == $expert_id) {
            $json = $this->model_visitor_expert->sendPublication($data);

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

    public function getCatalogList()
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

            $catalog_list = $this->model_register_register->getCatalogList($expert_info['b24id']);

            $sort_catalog = array();

            foreach ($catalog_list as $catalog_item) {

                $time = strtotime($catalog_item['date']);

                $statuses = array();

                if ($catalog_item['status'] === 'new') {
                    $statuses[] = array(
                        'text' => 'Новая заявка',
                        'active' => true,
                        'preactive' => true
                    );
                } else {
                    $statuses[] = array(
                        'text' => 'Новая заявка',
                        'active' => false,
                        'preactive' => false
                    );
                }

                if ($catalog_item['status'] === 'wait_payment') {
                    $statuses[] = array('text' => 'Ожидает оплаты', 'active' => true, 'preactive' => true);
                } else {
                    $statuses[] = array(
                        'text' => 'Ожидает оплаты',
                        'active' => false,
                        'preactive' => false
                    );

                }

                if ($catalog_item['status'] === 'work') {
                    $statuses[] = array('text' => 'Подготовка к размещению', 'active' => true, 'preactive' => true);
                } else {
                    $statuses[] = array(
                        'text' => 'Подготовка к размещению',
                        'active' => false,
                        'preactive' => false
                    );
                }

                if ($catalog_item['status'] === 'won') {
                    $statuses[] = array('text' => 'Завершена', 'active' => true, 'preactive' => true);
                }

                if ($catalog_item['status'] === 'cancel') {
                    $statuses[] = array('text' => 'Отмена', 'active' => true, 'preactive' => true);
                }

                if ($catalog_item['status'] !== 'won' && $catalog_item['status'] !== 'cancel') {
                    $statuses[] = array(
                        'text' => 'Завершена',
                        'active' => false,
                        'preactive' => false
                    );
                }


//                foreach ($statuses as $key => &$status) {
//                    if ($key == count($statuses) - 2 && !$statuses[count($statuses) - 1]['active']) {
//                        $status['preactive'] = true;
//                    } else {
//                        $status['preactive'] = false;
//                    }
//                }

                $catalog_item_info = array(
                    'title' => $catalog_item['title'],
                    'date' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
                    'status' => $catalog_item['status'],
                    'statuses' => $statuses,
                    'company' => $catalog_item['company'],
                    'invoice' => $catalog_item['invoice'],
                );

                $data['catalog_list'][] = $catalog_item_info;

                $sort_catalog[] = $time;
            }

            array_multisort($sort_catalog, SORT_DESC, $data['catalog_list']);

            $return['template'] = $this->load->view('register/catalog_list', $data);

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function getActiveVotes()
    {
        $return = array();
        $data = array();

        session_write_close();

        $this->load->model('register/register');
        $this->load->model('visitor/expert');

        if (isset($this->request->get['expert_id'])) {
            $expert_id = (int)$this->request->get['expert_id'];
        } else if ($this->request->get['route'] === 'register/account') {
            $expert_id = $this->visitor->getId();
        } else {
            $expert_id = 0;
        }

        $data['expert_id'] = $expert_id;

        $last_id = $this->request->get['last_id'];

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

            $vote_list = [];

            $vote_list = $this->model_visitor_expert->getVotes($expert_info['b24id'], $last_id);

            $sort_vote_list = array();
            $sort_quiz_list = array();

            foreach ($vote_list['list'] as $vote_item) {
                if ($vote_item['status'] !== 'wait') {
                    continue;
                }

                $time = strtotime($vote_item['date_end']);

                $statuses = array();

                $statuses[] = array(
                    'text' => 'Ожидание голосования',
                    'active' => false,
                    'preactive' => false
                );

                if ($vote_item['status'] === 'success') {
                    $statuses[] = array('text' => 'Прошёл опрос', 'active' => true, 'preactive' => true);
                }

                if ($vote_item['status'] === 'fail') {
                    $statuses[] = array('text' => 'Не прошёл опрос', 'active' => true, 'preactive' => true);
                }

                $vote_item_info = array(
                    'name' => $vote_item['name'],
                    'status' => $vote_item['status'],
                    'link' => $vote_item['link'],
                    'landing_url' => $vote_item['landing_url'] ?? '',
                    'statuses' => $statuses,
                    'date_end' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
                    'answers' => json_decode($vote_item['answers'], true)
                );

                $data['vote_list'][] = $vote_item_info;

                $sort_vote_list[] = $time;

            }

            foreach ($vote_list['quiz'] as $quiz_item) {
                $time = strtotime($quiz_item['date_end']);

                $quiz_item_info = array(
                    'id' => $quiz_item['id'],
                    'link' => $this->url->link('voting/voting', 'quiz_id=' . $quiz_item['id']),
                    'name' => $quiz_item['name'],
                    'type' => $quiz_item['type'],
                    'date_end' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time)
                );

                $data['quiz_list'][] = $quiz_item_info;

                $sort_quiz_list[] = $time;
            }

            array_multisort($sort_vote_list, SORT_DESC, $data['vote_list']);
            array_multisort($sort_quiz_list, SORT_DESC, $data['quiz_list']);

            $return['template'] = $this->load->view('expert/expert_votes', $data);

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function getFinishedVotes()
    {
        $return = array();
        $data = array();

        session_write_close();

        $this->load->model('register/register');
        $this->load->model('visitor/expert');

        if (isset($this->request->get['expert_id'])) {
            $expert_id = (int)$this->request->get['expert_id'];
        } else if ($this->request->get['route'] === 'register/account') {
            $expert_id = $this->visitor->getId();
        } else {
            $expert_id = 0;
        }

        $data['expert_id'] = $expert_id;

        $last_id = $this->request->get['last_id'];

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

            $vote_list = [];

            $vote_list = $this->model_visitor_expert->getVotes($expert_info['b24id'], $last_id)['list'];

            $sort_vote_list = array();

            foreach ($vote_list as $vote_item) {
                if ($vote_item['status'] === 'wait') {
                    continue;
                }

                $time = strtotime($vote_item['date_end']);

                $statuses = array();

                $statuses[] = array(
                    'text' => 'Ожидание голосования',
                    'active' => false,
                    'preactive' => false
                );

                if ($vote_item['status'] === 'success') {
                    $statuses[] = array('text' => 'Прошёл опрос', 'active' => true, 'preactive' => true);
                }

                if ($vote_item['status'] === 'fail') {
                    $statuses[] = array('text' => 'Не прошёл опрос', 'active' => true, 'preactive' => true);
                }

                foreach ($statuses as $key => &$status) {
                    if ($key == count($statuses) - 2 && !$statuses[count($statuses) - 1]['active']) {
                        $status['preactive'] = true;
                    } else {
                        $status['preactive'] = false;
                    }
                }

                $vote_item_info = array(
                    'name' => $vote_item['name'],
                    'status' => $vote_item['status'],
                    'link' => $vote_item['link'],
                    'landing_url' => $vote_item['landing_url'] ?? '',
                    'statuses' => $statuses,
                    'date_end' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
                    'answers' => json_decode($vote_item['answers'], true)
                );


                $data['vote_list'][] = $vote_item_info;

                $sort_vote_list[] = $time;

            }

            array_multisort($sort_vote_list, SORT_DESC, $data['vote_list']);

            $return['template'] = $this->load->view('expert/expert_votes', $data);

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function getServices()
    {
        $return = array();
        $data = array();

        session_write_close();

        $this->load->model('register/register');
        $this->load->model('visitor/expert');

        if (isset($this->request->get['expert_id'])) {
            $expert_id = (int)$this->request->get['expert_id'];
        } else if ($this->request->get['route'] === 'register/account') {
            $expert_id = $this->visitor->getId();
        } else {
            $expert_id = 0;
        }

        $data['expert_id'] = $expert_id;

        $type = $this->request->get['type'];

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

            $services_list = [];

            $services_list = $this->model_visitor_expert->getServices($expert_info['b24id'], $type)['list'];

            $sort_services_list = array();

            foreach ($services_list as $service_item) {
                $time = strtotime($service_item['date']);

                $service_item_info = array(
                    'title' => $service_item['title'],
                    'link' => $service_item['link'],
                    'date' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time)
                );

                $data['services_list'][] = $service_item_info;

                $sort_services_list[] = $time;

            }

            array_multisort($sort_services_list, SORT_DESC, $data['services_list']);

            $return['template'] = $this->load->view('expert/expert_services', $data);

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function getApps($type = 'forum')
    {
        $return = array();
        $data = array();

        session_write_close();

        $this->load->model('register/register');
        $this->load->model('visitor/expert');

        if (isset($this->request->get['expert_id'])) {
            $expert_id = (int)$this->request->get['expert_id'];
        } else if ($this->request->get['route'] === 'register/account') {
            $expert_id = $this->visitor->getId();
        } else {
            $expert_id = 0;
        }

        $data['expert_id'] = $expert_id;

        $last_id = $this->request->get['last_id'];
        $type = $this->request->get['type'];

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

            $app_list = [];

            $app_list = $this->model_visitor_expert->getApps($expert_info['b24id'], $type, $last_id)['list'];

            $sort_app_list = array();

            foreach ($app_list as $app_item) {
//                if ($app_item['status'] !== 'wait') {
//                    continue;
//                }

                $time = strtotime($app_item['date']);

                $statuses = array();

                if ($app_item['status'] === 'wait') {
                    $statuses[] = array('text' => 'Ожидание заполнения', 'active' => true, 'preactive' => true);

                    $statuses[] = array('text' => 'Бриф заполнен', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Мероприятие опубликовано', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Обработка результатов', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Работа над видео', 'active' => false, 'preactive' => false);
                } else {
                    $statuses[] = array(
                        'text' => 'Ожидание заполнения',
                        'active' => false,
                        'preactive' => false
                    );
                }


                if ($app_item['status'] === 'filled') {
                    $statuses[] = array('text' => 'Бриф заполнен', 'active' => true, 'preactive' => true);

                    $statuses[] = array('text' => 'Мероприятие опубликовано', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Обработка результатов', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Работа над видео', 'active' => false, 'preactive' => false);
                }

                if ($app_item['status'] === 'published') {
                    $statuses[] = array('text' => 'Бриф заполнен', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Мероприятие опубликовано', 'active' => true, 'preactive' => true);

                    $statuses[] = array('text' => 'Обработка результатов', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Работа над видео', 'active' => false, 'preactive' => false);
                }

                if ($app_item['status'] === 'processing') {
                    $statuses[] = array('text' => 'Бриф заполнен', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Мероприятие опубликовано', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Обработка результатов', 'active' => true, 'preactive' => true);

                    $statuses[] = array('text' => 'Работа над видео', 'active' => false, 'preactive' => false);
                }

                if ($app_item['status'] === 'video') {
                    $statuses[] = array('text' => 'Бриф заполнен', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Мероприятие опубликовано', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Обработка результатов', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Работа над видео', 'active' => true, 'preactive' => true);
                }

                $app_item_info = array(
                    'title' => $app_item['title'],
                    'date' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
                    'status' => $app_item['status'],
                    'link_crm' => $app_item['link_crm'],
                    'act_url' => $app_item['act_url'],
                    'reports' => $app_item['reports'],
                    'landing_url' => $app_item['landing_url'],
                    'answers' => json_decode($app_item['answers'], true),
                    'statuses' => $statuses
                );

                $data['app_list'][] = $app_item_info;

                $sort_app_list[] = $time;

            }

            array_multisort($sort_app_list, SORT_DESC, $data['app_list']);

            switch ($type) {
                case 'webinar':
                    $return['template'] = $this->load->view('expert/expert_apps_webinars', $data);
                    break;
                case 'pubs':
                    $return['template'] = $this->load->view('expert/expert_apps_pubs', $data);
                    break;
                case 'ads':
                    $return['template'] = $this->load->view('expert/expert_apps_ads', $data);
                    break;
                default:
                    $return['template'] = $this->load->view('expert/expert_apps_forums', $data);
                    break;
            }

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function getFinishedApps()
    {
        $return = array();
        $data = array();
        $years = [2024, 2023, 2022];

        session_write_close();

        $this->load->model('register/register');
        $this->load->model('visitor/expert');

        if (isset($this->request->get['expert_id'])) {
            $expert_id = (int)$this->request->get['expert_id'];
        } else if ($this->request->get['route'] === 'register/account') {
            $expert_id = $this->visitor->getId();
        } else {
            $expert_id = 0;
        }

        $data['expert_id'] = $expert_id;

        $last_id = $this->request->get['last_id'];
        $type = $this->request->get['type'];
        $year = isset($this->request->get['year']) ? $this->request->get['year'] : 2024;

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

            $app_list = [];

            $app_list = $this->model_visitor_expert->getFinishedApps($expert_info['b24id'], $year , $last_id)['list'];

            $sort_app_list = array();

            /*
             * статусы:
             * - ждём заполнения
             * (отображаем:
             *      1)название(в названии прописываем формат "Онлайн-событие: формат(из сделки)"),
             *      2)дата проведения,
             *      3)кнопка "заполнить бриф")
             * - бриф заполнен
             * (отображаем:
             *      1)название(в названии прописываем формат "Онлайн-событие: формат(из сделки)"),
             *      2)дата проведения,
             *      3)ответы заполненного брифа(по аналогии с голосованием(вопрос-ответ)) json
             *      )
             * - мероприятие опубликовано(кликабельная ссылка)
             * (отображаем:
             *      1)название(в названии прописываем формат "Онлайн-событие: формат(из сделки)"),
             *      2)дата проведения,
             *      3)ответы заполненного брифа(по аналогии с голосованием(вопрос-ответ))
             *      4)ссылка на лендинг
             *      )
             * - обработка результатов(после завершения события)
             * (отображаем:
             *      1)название(в названии прописываем формат "Онлайн-событие: формат(из сделки)"),
             *      2)дата проведения,
             *      3)ссылка на акт
             *      4)ответы заполненного брифа(по аналогии с голосованием(вопрос-ответ))
             *      5)ссылка на лендинг
             *      )
             * - работа над видео
             * (отображаем:
             *      1)название(в названии прописываем формат "Онлайн-событие: формат(из сделки)"),
             *      2)дата проведения,
             *      3)ссылка на акт
             *      4)ссылки на отчет(1-3)
             *      5)ответы заполненного брифа(по аналогии с голосованием(вопрос-ответ))
             *      6)ссылка на лендинг
             *      )
             */

            foreach ($app_list as $app_item) {
                $time = strtotime($app_item['date']);

                $statuses = [];

                if ($app_item['status'] === 'finish') {
                    $statuses[] = array('text' => 'Ожидание заполнения', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Бриф заполнен', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Мероприятие опубликовано', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Обработка результатов', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Работа над видео', 'active' => false, 'preactive' => false);

                    $statuses[] = array('text' => 'Завершён', 'active' => true, 'preactive' => true);
                }

                $app_item_info = array(
                    'title' => $app_item['title'],
                    'date' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
                    'status' => $app_item['status'],
                    'year' => $year,
                    'link_crm' => $app_item['link_crm'],
                    'video' => $app_item['video'],
                    'act_url' => $app_item['act_url'],
                    'reports' => $app_item['reports'],
                    'landing_url' => $app_item['landing_url'],
                    'answers' => json_decode($app_item['answers'], true),
                    'statuses' => $statuses
                );

                $data['app_list'][] = $app_item_info;

                $sort_app_list[] = $time;

            }

            array_multisort($sort_app_list, SORT_DESC, $data['app_list']);
            $data['years'] = array_unique($years);

            $return['template'] = $this->load->view('expert/expert_apps_finished', $data);

        } else {
            $return['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

}
