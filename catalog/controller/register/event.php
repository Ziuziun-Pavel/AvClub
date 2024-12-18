<?php

class ControllerRegisterEvent extends Controller
{
//    private $b24_hook = 'https://avclub.bitrix24.ru/rest/669/0nvck395h18aqtai/';
    private $b24_hook = 'https://avclub.bitrix24.ru/rest/677/qtn6xpqwuido3qts/';
//    private $b24_contacts = 'https://avclub.bitrix24.ru/rest/669/2yt2mpuav23aqllx/';
    private $b24_contacts = 'https://avclub.bitrix24.ru/rest/677/hgv4fvnz8xdrqk2k/';

    private $forum_list_id = 77;

    private $master_class_list_id = 117;

    private $webinar_list_id = 105;

    private $url_contact_create = "http://clients.techin.by/avclub/site/api/v1/contact/create";
    private $url_contact_update = "http://clients.techin.by/avclub/site/api/v1/contact/{id}/update";

    private $write_log = true;
    private $attention = true;
    private $attention_text = 'Регистрация работает в тестовом режиме. <br class="d-md-none">Приносим свои извинения за&nbsp;неудобства!';

    public function index()
    {
        $user_check_data = [];

        unset($this->session->data['register_code']);
        unset($this->session->data['register_phone']);
        unset($this->session->data['register_hash']);
        unset($this->session->data['register_event']);
        unset($this->session->data['register_email']);
        unset($this->session->data['register_user']);
        unset($this->session->data['register_contact_id']);
        unset($this->session->data['register_code']);

        $bitrixWebHook = $this->b24_hook;
        require_once(DIR_SYSTEM . 'library/crest/crest.php');

        $data = array();
        $has_event = false;

        $data['session'] = session_id();

        $this->load->language('expert/expert');

        $this->load->model('register/register');
        $this->load->model('tool/image');
        $this->load->model('themeset/themeset');
        $this->load->model('themeset/image');
        $this->load->model('themeset/expert');

        $data['attention'] = $this->attention;
        $data['attention_text'] = $this->attention_text;

        /* FORUM */
        if (isset($this->request->get['forum_id']) && !isset($this->request->get['master_class_id']) && !$has_event) {
            $forum_id = !empty($this->request->get['forum_id']) ? (int)$this->request->get['forum_id'] : 0;
            $result = CRest::call(
                'lists.element.get',
                [
                    "IBLOCK_TYPE_ID" => "lists",
                    "IBLOCK_ID" => $this->forum_list_id,
                    "filter" => array("=ID" => array($forum_id))
                ]
            );

            if (!empty($forum_id) && !empty($result['result'][0])) {

                $timeToClose = 17 + current($result['result'][0]['PROPERTY_477']) . ':00:00';

                $isClosed = !(
                    strtotime(date('d.m.Y')) < strtotime(current($result['result'][0]['PROPERTY_665'])) ||
                    strtotime(date('d.m.Y')) == strtotime(current($result['result'][0]['PROPERTY_665'])) && time() <= strtotime($timeToClose)
                );

                $this->session->data['register_event'] = array(
                    'type' => 'forum',
                    'forum_id' => $forum_id,
                    'name' => $result['result'][0]['NAME'],
                    'city' => current($result['result'][0]['PROPERTY_479']),
                    'date' => current($result['result'][0]['PROPERTY_427']),
                    'date_stop' => current($result['result'][0]['PROPERTY_665']),
                    'isClosed' => $isClosed,
                    'timezone' => current($result['result'][0]['PROPERTY_477']),
                    'address_1' => current($result['result'][0]['PROPERTY_473']),
                    'address_2' => current($result['result'][0]['PROPERTY_475']),
                    'price' => !empty($result['result'][0]['PROPERTY_547']) ? current($result['result'][0]['PROPERTY_547']) : '',
                );

                $data['event_info'] = array(
                    'type' => 'forum',
                    'title' => 'Регистрация на форум AV FOCUS ' . $this->session->data['register_event']['name'],
                    'name' => $this->session->data['register_event']['name'],
                    'city' => $this->session->data['register_event']['city'],
                    'date' => $this->session->data['register_event']['date'],
                    'date_stop' => $this->session->data['register_event']['date_stop'],
                    'isClosed' => $this->session->data['register_event']['isClosed'],
                    'timezone' => $this->session->data['register_event']['timezone'],
                    'price' => $this->session->data['register_event']['price'],
                    'address' => array()
                );
                if ($this->session->data['register_event']['address_1']) {
                    $data['event_info']['address'][] = $this->session->data['register_event']['address_1'];
                }
                if ($this->session->data['register_event']['address_2']) {
                    $data['event_info']['address'][] = $this->session->data['register_event']['address_2'];
                }

                $has_event = true;
            }

        }
        /* # FORUM */

        /* WEBINAR */
        if (isset($this->request->get['webinar_id']) && !$has_event) {
            $webinar_id = !empty($this->request->get['webinar_id']) ? (int)$this->request->get['webinar_id'] : 0;

            $result = CRest::call(
                'lists.element.get',
                [
                    "IBLOCK_TYPE_ID" => "lists",
                    "IBLOCK_ID" => $this->webinar_list_id,
                    "filter" => array("=ID" => array($webinar_id))
                ]
            );

            if (!empty($webinar_id) && !empty($result['result'][0])) {

                if (!empty($result['result'][0]['PROPERTY_555'])) {
                    $time = strtotime(current($result['result'][0]['PROPERTY_555']));
                    $date = date('d.m.Y H:i', $time);
                } else {
                    $date = '';
                }

                $fullDate = $date . ':00';
                $dateToClose = explode(" ", $fullDate)[0];
                $timeToClose = explode(" ", $fullDate)[1];

                $isClosed = !(
                    strtotime(date('d.m.Y')) < strtotime($dateToClose) ||
                    strtotime(date('d.m.Y')) == strtotime($dateToClose) && time() <= strtotime($timeToClose));

                $this->session->data['register_event'] = array(
                    'type' => 'webinar',
                    'webinar_id' => $webinar_id,
                    'name' => !empty(current($result['result'][0]['PROPERTY_727'])) ? current($result['result'][0]['PROPERTY_727']) : $result['result'][0]['NAME'],
                    'date' => $date,
                    'isClosed' => $isClosed,
                    'eventSessionId' => current($result['result'][0]['PROPERTY_557']),
                    'eventId' => current($result['result'][0]['PROPERTY_559']),
                    'duration' => current($result['result'][0]['PROPERTY_563']),
                    'link' => current($result['result'][0]["PROPERTY_691"]),
                );

                $data['event_info'] = array(
                    'type' => 'webinar',
                    'title' => 'Регистрация на онлайн-событие',
                    'name' => $this->session->data['register_event']['name'],
                    'date' => $this->session->data['register_event']['date'],
                    'isClosed' => $this->session->data['register_event']['isClosed'],
                    'eventSessionId' => $this->session->data['register_event']['eventSessionId'],
                    'eventId' => $this->session->data['register_event']['eventId'],
                    'duration' => $this->session->data['register_event']['duration'],
                    'link' => $this->session->data['register_event']['link']
                );

                $has_event = true;
            }

        }
        /* # WEBINAR */

        /* MASTER CLASS */
        if (isset($this->request->get['master_class_id']) && isset($this->request->get['forum_id']) && !$has_event) {
            $master_class_id = !empty($this->request->get['master_class_id']) ? (int)$this->request->get['master_class_id'] : 0;
            $forum_id = !empty($this->request->get['forum_id']) ? (int)$this->request->get['forum_id'] : 0;

            $result = CRest::callBatch([
                'get_master_class' => [
                    'method' => 'lists.element.get',
                    'params' => [
                        "IBLOCK_TYPE_ID" => "lists",
                        "IBLOCK_ID" => $this->master_class_list_id,
                        "filter" => array("=ID" => array($master_class_id))
                    ]
                ],
                'get_forum' => [
                    'method' => 'lists.element.get',
                    'params' => [
                        "IBLOCK_TYPE_ID" => "lists",
                        "IBLOCK_ID" => $this->forum_list_id,
                        "filter" => array("=ID" => array($forum_id))
                    ]
                ],
            ]);

            //название мастер-класса
            $master_class_name = array_shift($result['result']['result']['get_master_class'][0]['PROPERTY_669']);

            if (!empty($master_class_id) && !empty($result['result']['result']['get_forum'][0])) {

                $timeToClose = 17 + current($result['result']['result']['get_forum'][0]['PROPERTY_477']) . ':00:00';

                $isClosed = !(
                    strtotime(date('d.m.Y')) < strtotime(current($result['result']['result']['get_forum'][0]['PROPERTY_665'])) ||
                    strtotime(date('d.m.Y')) == strtotime(current($result['result']['result']['get_forum'][0]['PROPERTY_665'])) && time() <= strtotime($timeToClose)
                );

                $this->session->data['register_event'] = array(
                    'type' => 'master_class',
                    'forum_id' => $forum_id,
                    'master_class_id' => $master_class_id,
                    'name' => $master_class_name,
                    'city' => current($result['result']['result']['get_forum'][0]['PROPERTY_479']),
                    'date' => current($result['result']['result']['get_forum'][0]['PROPERTY_427']),
                    'date_stop' => current($result['result']['result']['get_forum'][0]['PROPERTY_665']),
                    'isClosed' => $isClosed,
                    'timezone' => current($result['result']['result']['get_forum'][0]['PROPERTY_477']),
                    'address_1' => current($result['result']['result']['get_forum'][0]['PROPERTY_473']),
                    'address_2' => current($result['result']['result']['get_forum'][0]['PROPERTY_475']),
                    'price' => !empty($result['result']['result']['get_forum'][0]['PROPERTY_547']) ? current($result['result']['result']['get_forum'][0]['PROPERTY_547']) : '',
                );

                $data['event_info'] = array(
                    'type' => 'master_class',
                    'title' => 'Регистрация на мастер-класс ' . $this->session->data['register_event']['name'],
                    'name' => $this->session->data['register_event']['name'],
                    'city' => $this->session->data['register_event']['city'],
                    'date' => $this->session->data['register_event']['date'],
                    'date_stop' => $this->session->data['register_event']['date_stop'],
                    'isClosed' => $this->session->data['register_event']['isClosed'],
                    'timezone' => $this->session->data['register_event']['timezone'],
                    'price' => $this->session->data['register_event']['price'],
                    'address' => array()
                );

                if ($this->session->data['register_event']['address_1']) {
                    $data['event_info']['address'][] = $this->session->data['register_event']['address_1'];
                }
                if ($this->session->data['register_event']['address_2']) {
                    $data['event_info']['address'][] = $this->session->data['register_event']['address_2'];
                }

                $has_event = true;
            }

        }
        /* # MASTER CLASS */

        if (!empty($data['event_info'])) {

            if ($this->visitor->isLogged()) {
                $contact_info = $this->model_register_register->getContactInfo($this->visitor->getB24id());


                if (!empty($contact_info['ID']) && !empty($contact_info['PHONE'][0])) {
                    $email = '';

                    $user_check_data['contact_id'] = $contact_info['ID'];
                    $user_check_data['type'] = '';

                    if ($this->request->get['webinar_id']) {
                        $user_check_data['type'] = 'webinar';
                    } elseif (isset($this->request->get['forum_id']) && !isset($this->request->get['master_class_id'])) {
                        $user_check_data['type'] = 'forum';
                    } else {
                        $user_check_data['type'] = 'master_class';
                    }

                    $user_check_data['event_id'] = '';

                    if ($user_check_data['type'] === 'webinar') {
                        $user_check_data['event_id'] = $this->request->get['webinar_id'];
                    } elseif ($user_check_data['type'] === 'forum') {
                        $user_check_data['event_id'] = $this->request->get['forum_id'];
                    } else {
                        $user_check_data['event_id'] = $this->request->get['master_class_id'];
                        $user_check_data['forum_id'] = $this->request->get['forum_id'];
                    }

                    $user_check_data['contact_ids'] = $this->model_register_register->getAlternateUsers($user_check_data['contact_id']);
                    $register_exists = $this->model_register_register->checkRegistration($user_check_data['type'], $user_check_data['event_id'], $user_check_data['forum_id'], $user_check_data['contact_ids'], $user_check_data['fields']);

                    $data['register_exists'] = $register_exists;
                    $this->session->data['register_event']['register_exists'] = $register_exists;

                    $user_data = array(
                        'user_id' => $contact_info['ID'],
                        'old_user_id' => $contact_info['ID'],
                        'name' => $contact_info['NAME'],
                        'lastname' => $contact_info['LAST_NAME'],
                        'post' => $contact_info['POST'],
                        'b24_company_id' => !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : 0,
                        'b24_company_old_id' => !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : 0,
                        'company' => '',
                        'company_status' => !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : '',
                        'company_phone' => '',
                        'company_site' => '',
                        'company_activity' => '',
                        'city' => '',
                        'email' => '',
                        'avatar' => $this->model_themeset_image->original('user_no_avatar.png'),
                    );

                    $photo = !empty($contact_info['PHOTO']['downloadUrl']) ? $this->model_themeset_expert->saveExpertPhoto($contact_info['PHOTO']['downloadUrl'], $contact_info['ID'], 'catalog/visitors/') : '';

                    if (!empty($photo) && is_file(DIR_IMAGE . $photo)) {
                        $user_data['avatar'] = $this->model_themeset_image->original($photo);
                    }

                    if (!empty($contact_info['EMAIL'])) {
                        foreach ($contact_info['EMAIL'] as $item) {
                            $user_data['email'] = $item;
                            break;
                        }
                    }

                    if (!empty($user_data['b24_company_id'])) {

                        $filter_data = array(
                            'filter_b24id' => $user_data['b24_company_id'],
                            'filter_start' => 0,
                            'filter_limit' => 1
                        );
                        $results_companies = $this->model_register_register->getCompanyNames($filter_data);
                        if (!empty($results_companies)) {

                            $company_info = $results_companies[0];

                            $user_data['company'] = $company_info['name'];

                            $user_data['city'] = $company_info['city'];
                            $user_data['company_phone'] = $company_info['phone'];
                            $user_data['company_site'] = $company_info['web'];
                            $user_data['company_activity'] = $company_info['activity'];

                        } else {
                            $user_data['b24_company_id'] = 0;
                            $user_data['b24_company_old_id'] = 0;
                        }
                    }


                    if (!empty($user_data['email'])) {
                        $email = $user_data['email'];
                    }


                    $user_data['email'] = !empty($email) ? $email : '';


                }
            }

            if (!empty($user_data) && !empty($contact_info)) {

                $this->session->data['register_contact_id'] = $contact_info['ID'];
                $this->session->data['register_phone'] = $contact_info['PHONE'][0];
                $this->session->data['register_hash'] = $this->model_register_register->hashCode($code);
                $this->session->data['register_user'] = $user_data;
                $this->session->data['register_email'] = $user_data['email'];

                $data['user'] = $user_data;
                $data['phone'] = $this->session->data['register_phone'];
                $data['button_register'] = 'Все верно';
                $data['show_name'] = true;
                $data['show_notme'] = true;
                $data['template'] = $this->load->view('register/event_user_main', $data);

            } else {
                $data['template'] = $this->load->view('register/event_phone', $data);
            }

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('register/event', $data));


        } else {


            $this->load->language('error/not_found');

            $data['breadcrumbs'] = array();

            $url = '';

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

    public function list()
    {

        $bitrixWebHook = $this->b24_hook;
        require_once(DIR_SYSTEM . 'library/crest/crest.php');

        $type = !empty($this->request->get['type']) ? $this->request->get['type'] : '';

        if (!empty($type)) {

            switch ($type) {
                case 'webinar':
                    $list_id = $this->webinar_list_id;
                    break;

                default:
                    $list_id = $this->forum_list_id;
            }

            $result = CRest::call(
                'lists.element.get',
                [
                    "IBLOCK_TYPE_ID" => "lists",
                    "IBLOCK_ID" => $list_id,
                    "start" => !empty($this->request->get['start']) ? $this->request->get['start'] : 0
                ]
            );

            echo '<pre>';
            print_r($result);
            echo '</pre>';
        }

    }

    public function authorize()
    {

        $this->load->model('register/register');
        $this->load->model('themeset/image');
        $this->load->model('themeset/expert');

        $return = array();
        $data = array();
        $error = false;
        $exist = false;

        $sid = $this->request->post['sid'];
        $data['session'] = $sid;
        $data['attention'] = $this->attention;
        $data['attention_text'] = $this->attention_text;

        $phone = !empty($this->request->post['telephone']) ? $this->request->post['telephone'] : '';
        $email = !empty($this->request->post['email']) ? $this->request->post['email'] : '';

        if (!empty($this->session->data['register_phone']) && $this->session->data['register_phone'] === $phone && !empty($this->session->data['register_user'])) {
            $exist = true;
        } else {
            unset($this->session->data['register_phone']);
            unset($this->session->data['register_user']);
            unset($this->session->data['register_contact_id']);
        }

        $this->session->data['register_phone'] = $phone;


        if (!$phone) {
            $return['error'] = 'Введите номер телефона';
            $error = true;
        }


        if (!$error) {

            $contact_id = !empty($this->session->data['register_contact_id']) ? $this->session->data['register_contact_id'] : $this->model_register_register->searchContactByPhone($this->session->data['register_phone']);

            $this->session->data['register_contact_id'] = $contact_id;

            if ($exist && !empty($this->session->data['register_user'])) {
                $user_data = $this->session->data['register_user'];
            } else {
                $contact_info = $this->model_register_register->getContactInfo($contact_id);

                if (!empty($contact_info['ID'])) {
                    $user_check_data['contact_id'] = $contact_info['ID'];
                    $user_check_data['type'] = '';

                    if ($this->session->data["register_event"]['webinar_id']) {
                        $user_check_data['type'] = 'webinar';
                    } elseif (isset($this->session->data["register_event"]['forum_id']) && !isset($this->session->data["register_event"]['master_class_id'])) {
                        $user_check_data['type'] = 'forum';
                    } else {
                        $user_check_data['type'] = 'master_class';
                    }

                    $user_check_data['event_id'] = '';

                    if ($user_check_data['type'] === 'webinar') {
                        $user_check_data['event_id'] = $this->session->data["register_event"]['webinar_id'];
                    } elseif ($user_check_data['type'] === 'forum') {
                        $user_check_data['event_id'] = $this->session->data["register_event"]['forum_id'];
                    } else {
                        $user_check_data['event_id'] = $this->session->data["register_event"]['master_class_id'];
                        $user_check_data['forum_id'] = $this->session->data["register_event"]['forum_id'];
                    }


                    $user_check_data['contact_ids'] = $this->model_register_register->getAlternateUsers($user_check_data['contact_id']);

                    $register_exists = $this->model_register_register->checkRegistration($user_check_data['type'], $user_check_data['event_id'], $user_check_data['forum_id'], $user_check_data['contact_ids'], $user_check_data['fields']);

                    $data['register_exists'] = $register_exists;
                    $this->session->data['register_event']['register_exists'] = $register_exists;

                    $user_data = array(
                        'user_id' => $contact_info['ID'],
                        'old_user_id' => $contact_info['ID'],
                        'name' => $contact_info['NAME'],
                        'lastname' => $contact_info['LAST_NAME'],
                        'post' => $contact_info['POST'],
                        'b24_company_id' => !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : 0,
                        'b24_company_old_id' => !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : 0,
                        'company' => '',
                        'company_status' => !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : '',
                        'company_phone' => '',
                        'company_site' => '',
                        'company_activity' => '',
                        'city' => '',
                        'email' => '',
                        'avatar' => $this->model_themeset_image->original('user_no_avatar.png'),
                    );

                    $photo = !empty($contact_info['PHOTO']['downloadUrl']) ? $this->model_themeset_expert->saveExpertPhoto($contact_info['PHOTO']['downloadUrl'], $contact_info['ID'], 'catalog/visitors/') : '';

                    if (!empty($photo) && is_file(DIR_IMAGE . $photo)) {
                        $user_data['avatar'] = $this->model_themeset_image->original($photo);
                    }

                    if (!empty($contact_info['EMAIL'])) {
                        foreach ($contact_info['EMAIL'] as $item) {
                            $user_data['email'] = $item;
                            break;
                        }
                    }

                    if (!empty($user_data['b24_company_id'])) {

                        $filter_data = array(
                            'filter_b24id' => $user_data['b24_company_id'],
                            'filter_start' => 0,
                            'filter_limit' => 1
                        );
                        $results_companies = $this->model_register_register->getCompanyNames($filter_data);
                        if (!empty($results_companies)) {

                            $company_info = $results_companies[0];

                            $user_data['company'] = $company_info['name'];

                            $user_data['city'] = $company_info['city'];
                            $user_data['company_phone'] = $company_info['phone'];
                            $user_data['company_site'] = $company_info['web'];
                            $user_data['company_activity'] = $company_info['activity'];

                        } else {
                            $user_data['b24_company_id'] = 0;
                            $user_data['b24_company_old_id'] = 0;
                        }
                    }

                } else {
                    $user_data = array(
                        'user_id' => 0,
                        'old_user_id' => 0,
                        'name' => '',
                        'lastname' => '',
                        'post' => '',
                        'b24_company_id' => 0,
                        'b24_company_old_id' => 0,
                        'company' => '',
                        'company_status' => '',
                        'company_phone' => '',
                        'company_site' => '',
                        'company_activity' => '',
                        'city' => '',
                        'email' => !empty($this->session->data['register_email']) ? $this->session->data['register_email'] : '',
                        'avatar' => $this->model_themeset_image->original('user_no_avatar.png'),
                    );

                    $user_check_data['contact_id'] = $contact_info['ID'];
                    $user_check_data['type'] = '';

                    if ($this->session->data["register_event"]['webinar_id']) {
                        $user_check_data['type'] = 'webinar';
                    } elseif (isset($this->session->data["register_event"]['forum_id']) && !isset($this->session->data["register_event"]['master_class_id'])) {
                        $user_check_data['type'] = 'forum';
                    } else {
                        $user_check_data['type'] = 'master_class';
                    }

                    $user_check_data['event_id'] = '';

                    if ($user_check_data['type'] === 'webinar') {
                        $user_check_data['event_id'] = $this->session->data["register_event"]['webinar_id'];
                    } elseif ($user_check_data['type'] === 'forum') {
                        $user_check_data['event_id'] = $this->session->data["register_event"]['forum_id'];
                    } else {
                        $user_check_data['event_id'] = $this->session->data["register_event"]['master_class_id'];
                        $user_check_data['forum_id'] = $this->session->data["register_event"]['forum_id'];
                    }
                    $user_check_data['fields'] = [
                        'contact_name' => $user_data['name'],
                        'contact_last_name' => $user_data['lastname'],
                        'contact_phone' => $this->session->data['register_phone'],
                        'contact_email' => $this->session->data['register_email'],
                    ];

                    $user_check_data['contact_ids'] = $this->model_register_register->getAlternateUsers($user_check_data['contact_id']);

                    $register_exists = $this->model_register_register->checkRegistration($user_check_data['type'], $user_check_data['event_id'], $user_check_data['forum_id'], $user_check_data['contact_ids'], $user_check_data['fields']);

                    $data['register_exists'] = $register_exists;
                    $this->session->data['register_event']['register_exists'] = $register_exists;

                }

            }

            if ((is_array($data['register_exists']) && $data['register_exists']["registration_id"]) ||
                (!is_array($data['register_exists']) && $data['register_exists'])) {
                $return['error'] = 'Пользователь уже зарегистрирован на это мероприятие.';
                $return['template'] = $this->load->view('register/event_code', $data);

                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($return));
            } else {
                if (!empty($user_data['email'])) {
                    $email = $user_data['email'];
                } else if (empty($email)) {
                    $return['error'] = 'Введите адрес электронной почты';
                    $return['show_email'] = true;
                    $error = true;
                }

                $this->session->data['register_email'] = !empty($email) ? $email : '';
                $user_data['email'] = $this->session->data['register_email'];

                $this->session->data['register_user'] = $user_data;
            }
        }

        if (!$error) {

            $request = $this->model_register_register->sendSMS($phone);
            // $request = $this->model_register_register->sendCall($phone);
            // $request = $this->model_register_register->sendTest($phone);

            if (!empty($request['error'])) {
                $return['error'] = $request['error'];
                $error = true;
            } else {
                $return['success'] = true;
                $code = $request['code'];
            }


        }

        if ($error && $this->write_log) {
            $log = array(
                'step' => 'Ввод телефона -- ERROR',
                'session' => $sid,
                'browser' => $_SERVER['HTTP_USER_AGENT'],
                'phone' => $phone,
                'email' => $email,
                'error' => $return['error'],
                'request' => !empty($request) ? $request : 'empty'
            );
            $this->model_register_register->log($log, 'info');
        }

        if ((is_array($data['register_exists']) && $data['register_exists']["registration_id"]) ||
            (!is_array($data['register_exists']) && $data['register_exists'])) {
            $return['error'] = 'Пользователь уже зарегистрирован на это мероприятие.';
            $return['template'] = $this->load->view('register/event_code', $data);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($return));
        } else {
            if (!$error && !empty($return['success'])) {
                $this->session->data['register_hash'] = $this->model_register_register->hashCode($code);

                $data['info_text'] = '';
                if (!empty($email)) {
                    $email_hide = $this->model_register_register->hideEmail($email);
                    $data['info_text'] = 'Проверочный код отправлен на Ваш номер телефона и адрес электронной почты ' . $email_hide;
                    $this->model_register_register->sendCodeToEmail($code, $email);

                    $this->session->data['register_email'] = $email;
                }


                if ($this->write_log) {
                    $log = array(
                        'step' => 'Ввод телефона -- SUCCESS',
                        'session' => $sid,
                        'browser' => $_SERVER['HTTP_USER_AGENT'],
                        'phone' => $phone,
                        'email' => $email,
                        'code' => $code,
                    );
                    $this->model_register_register->log($log, 'info');
                }

                $return['template'] = $this->load->view('register/event_code', $data);
            } else {
                unset($this->session->data['register_hash']);
            }
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function inputCode()
    {

        $this->load->model('register/register');
        $this->load->model('themeset/expert');
        $this->load->model('themeset/image');
        $this->load->model('company/company');

        $return = array();
        $data = array();
        $error = false;

        $code = !empty($this->request->post['code']) ? $this->request->post['code'] : '';
        $sid = $this->request->post['sid'];
        $data['session'] = $sid;
        $data['attention'] = $this->attention;
        $data['attention_text'] = $this->attention_text;

        // нет хэша
        if (empty($this->session->data['register_hash']) || empty($this->session->data['register_phone']) || empty($this->session->data['register_user'])) {
            $error = true;
            $return['reload'] = true;
            $return['error'] = 'Сессия истекла';
        }

        // пустое поле кода
        if (empty($code)) {
            $error = true;
            $return['error'] = 'Введите проверочный код';
        }

        // код неверный
        if (!$error && !$this->model_register_register->validateCode($code)) {
            $error = true;
            $return['error'] = 'Неверный проверочный код';
        }

        if ($error && $this->write_log) {
            $log = array(
                'step' => 'Проверочный код -- ERROR',
                'session' => $sid,
                'browser' => $_SERVER['HTTP_USER_AGENT'],
                'error' => $return['error'],
            );
            $this->model_register_register->log($log, 'info');
        }

        // все ок, ошибок нет
        if (!$error) {

            $user_data = $this->session->data['register_user'];

            // $contact_id = $this->model_register_register->searchContactByPhone($this->session->data['register_phone']);

            // $contact_info = $this->model_register_register->getContactInfo($contact_id);


            $data['user'] = $user_data;
            $data['phone'] = $this->session->data['register_phone'];
            $data['countries'] = $this->model_company_company->getListOfCountries();
            if (!empty($user_data['user_id'])) {
                /* КОНТАКТ НАЙДЕН */

                $data['button_register'] = 'Все верно';

                $data['show_name'] = true;
                $data['show_notme'] = true;
                $data['save_btn_disabled'] = false;

                $return['template'] = $this->load->view('register/event_user_main', $data);

                /* # КОНТАКТ НАЙДЕН */
            } else {
                /* НЕТ КОНТАКТА */

                $data['title'] = 'Заполните свои персональные данные для профайла резидента АВ&nbsp;Клуба';

                $data['show_name'] = false;
                $data['show_notme'] = false;
                $data['show_name_fields'] = true;
                $data['save_btn_disabled'] = true;
                $data['company_template'] = $this->load->view('register/_brand_main', $data);

                $return['template'] = $this->load->view('register/event_user_change', $data);
                /* # НЕТ КОНТАКТА */
            }

            $this->session->data['register_user'] = $user_data;

            if ($this->write_log) {
                $log = array(
                    'step' => 'Проверка проверочного кода -- SUCCESS',
                    'session' => $sid,
                    'browser' => $_SERVER['HTTP_USER_AGENT'],
                    'user_data' => $user_data,
                );
                $this->model_register_register->log($log, 'info');
            }

        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function changeData()
    {

        $this->load->model('register/register');
        $this->load->model('company/company');

        $return = array();
        $data = array();
        $error = false;

        $sid = $this->request->post['sid'];
        $data['session'] = $sid;
        $data['attention'] = $this->attention;
        $data['attention_text'] = $this->attention_text;

        // нет хэша
        if (empty($this->session->data['register_hash']) || empty($this->session->data['register_phone']) || empty($this->session->data['register_user'])) {
            $error = true;
            $return['reload'] = true;
            $return['error'] = 'Сессия истекла';
        }

        if ($error && $this->write_log) {
            $log = array(
                'step' => 'Страница изменения данных -- ERROR',
                'session' => $sid,
                'browser' => $_SERVER['HTTP_USER_AGENT'],
                'error' => $return['error'],
            );
            $this->model_register_register->log($log, 'info');
        }

        // все ок, ошибок нет
        if (!$error) {

            $user_data = $this->session->data['register_user'];

            $data['user'] = $user_data;
            $data['phone'] = $this->session->data['register_phone'];

            $data['title'] = 'Обновить данные в профайле резидента АВ&nbsp;Клуба';
            $data['show_name'] = true;
            $data['show_notme'] = true;
            $data['show_name_fields'] = false;

            $data_company = array();
            $data_company['activity'] = $this->load->controller('register/company/getCompanyActivity');
            $data_company['company_info'] = array(
                'b24_company_old_id' => $user_data['b24_company_old_id'],
                'b24_company_id' => $user_data['b24_company_id'],
                'title' => $user_data['company'],
                'city' => $user_data['city'],
                'web' => $user_data['company_site'],
                'phone' => $user_data['company_phone'],
                'activity' => $user_data['company_activity'],

                'search' => $user_data['company'],
            );
            $data_company['countries'] = $this->model_company_company->getListOfCountries();

            if ($user_data['company']) {
                $data['company_template'] = $this->load->view('register/_brand_data_noedit', $data_company);
            } else {
                $data['company_template'] = $this->load->view('register/_brand_main', $data_company);
            }

            $return['template'] = $this->load->view('register/event_user_change', $data);


            if ($this->write_log) {
                $log = array(
                    'step' => 'Страница изменения данных --  SUCCESS',
                    'session' => $sid,
                    'browser' => $_SERVER['HTTP_USER_AGENT'],
                    'user_data' => $user_data,
                );
                $this->model_register_register->log($log, 'info');
            }

        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function notmeData()
    {

        $this->load->model('register/register');
        $this->load->model('themeset/image');
        $this->load->model('company/company');

        $return = array();
        $data = array();
        $error = false;

        $sid = $this->request->post['sid'];
        $data['session'] = $sid;
        $data['attention'] = $this->attention;
        $data['attention_text'] = $this->attention_text;

        // нет хэша
        if (empty($this->session->data['register_hash']) || empty($this->session->data['register_phone']) || empty($this->session->data['register_user'])) {
            $error = true;
            $return['reload'] = true;
            $return['error'] = 'Сессия истекла';
        }

        if ($error && $this->write_log) {
            $log = array(
                'step' => 'Страница "Это не я" -- ERROR',
                'session' => $sid,
                'browser' => $_SERVER['HTTP_USER_AGENT'],
                'error' => $return['error'],
            );
            $this->model_register_register->log($log, 'info');
        }

        // все ок, ошибок нет
        if (!$error) {

            $user_data = array(
                'user_id' => 0,
                'old_user_id' => 0,
                'name' => '',
                'lastname' => '',
                'post' => '',
                'b24_company_id' => 0,
                'b24_company_old_id' => 0,
                'company' => '',
                'company_status' => '',
                'company_phone' => '',
                'company_site' => '',
                'company_activity' => array(),
                'city' => '',
                'email' => '',
                'avatar' => $this->model_themeset_image->original('user_no_avatar.png'),
            );

            $this->session->data['register_user'] = $user_data;

            $data['user'] = $user_data;
            $data['phone'] = $this->session->data['register_phone'];
            $data['countries'] = $this->model_company_company->getListOfCountries();

            $data['title'] = 'Укажите корректные данные и сохраните изменения';
            $data['show_name'] = false;
            $data['show_notme'] = false;
            $data['show_name_fields'] = true;

            $data['company_template'] = $this->load->view('register/_brand_main', $data);

            $return['template'] = $this->load->view('register/event_user_change', $data);

            if ($this->write_log) {
                $log = array(
                    'step' => 'Страница "Это не я" -- SUCCESS',
                    'session' => $sid,
                    'browser' => $_SERVER['HTTP_USER_AGENT'],
                    'user_data' => $user_data,
                );
                $this->model_register_register->log($log, 'info');
            }

        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function saveData()
    {
        $post = $this->request->post;

        $this->load->model('register/register');
        $this->load->model('themeset/expert');
        $this->load->model('themeset/image');
        $this->load->model('themeset/themeset');

        $return = array();
        $data = array();
        $error = false;

        $sid = $this->request->post['sid'];
        $data['session'] = $sid;
        $data['attention'] = $this->attention;
        $data['attention_text'] = $this->attention_text;
        $data['second_choice'] = false;
        // нет хэша
        if (empty($this->session->data['register_user'])) {
            $error = true;
            $return['reload'] = true;
            $return['error'] = 'Сессия истекла';
        }

        if ($error && $this->write_log) {
            $log = array(
                'step' => 'Сохранение данных -- ERROR',
                'session' => $sid,
                'browser' => $_SERVER['HTTP_USER_AGENT'],
                'error' => $return['error'],
            );
            $this->model_register_register->log($log, 'info');
        }

        // все ок, ошибок нет
        if (!$error) {
            //если поля email и должность были изменены, то true
            $this->session->data['register_user']['userFieldsChanged'] = $post['userFieldsChanged'] === "true";

            //показывает сменил ли пользователь компанию
            $this->session->data['register_user']['isCompanyChanged'] = isset($post['isCompanyChanged']) ? true : $this->session->data['register_user']['company'] !== $post["company"];

            $user_data = array(
                //'user_id'					=> $this->session->data['register_user']['user_id'],
                'user_id' => 0,
                'old_user_id' => $this->session->data['register_user']['old_user_id'],
                'name' => isset($post['name']) ? $post['name'] : $this->session->data['register_user']['name'],
                'lastname' => isset($post['lastname']) ? $post['lastname'] : $this->session->data['register_user']['lastname'],
                'post' => isset($post['post']) ? $post['post'] : '',
                'b24_company_id' => isset($post['b24_company_id']) ? $post['b24_company_id'] : 0,
                'b24_company_old_id' => isset($post['b24_company_old_id']) ? $post['b24_company_old_id'] : 0,
                'company' => isset($post['company']) ? $post['company'] : '',
                'company_status' => isset($post['company_status']) ? $post['company_status'] : '',
                'company_phone' => isset($post['company_phone']) ? $post['company_phone'] : '',
                'company_site' => isset($post['company_site']) ? $post['company_site'] : '',
                'company_activity' => isset($post['company_activity']) ? $post['company_activity'] : '',
                'company_country' => isset($this->session->data["register_event"]['company_country']) ? $this->session->data["register_event"]['company_country'] : '',
                'company_inn' => isset($this->session->data["register_event"]['company_inn']) ? $this->session->data["register_event"]['company_inn'] : '',
                'company_address' => isset($this->session->data["register_event"]['company_address']) ? $this->session->data["register_event"]['company_address'] : '',
                'company_director' => isset($this->session->data["register_event"]['company_director']) ? $this->session->data["register_event"]['company_director'] : '',
                'city' => isset($post['city']) ? $post['city'] : '',
                'email' => isset($post['email']) ? $post['email'] : '',
                'avatar' => $this->session->data['register_user']['avatar'],
                'userFieldsChanged' => $this->session->data['register_user']['userFieldsChanged'],
                'isCompanyChanged' => $this->session->data['register_user']['isCompanyChanged']
            );

            $user_check_data['fields'] = [
                'contact_name' => $user_data['name'],
                'contact_last_name' => $user_data['lastname'],
                'contact_phone' => $this->session->data['register_phone'],
                'contact_email' => $this->session->data['register_email'],
            ];

            $user_check_data['type'] = '';

            if ($this->session->data["register_event"]['webinar_id']) {
                $user_check_data['type'] = 'webinar';
            } elseif (isset($this->session->data["register_event"]['forum_id']) && !isset($this->session->data["register_event"]['master_class_id'])) {
                $user_check_data['type'] = 'forum';
            } else {
                $user_check_data['type'] = 'master_class';
            }

            $user_check_data['event_id'] = '';

            if ($user_check_data['type'] === 'webinar') {
                $user_check_data['event_id'] = $this->session->data["register_event"]['webinar_id'];
            } elseif ($user_check_data['type'] === 'forum') {
                $user_check_data['event_id'] = $this->session->data["register_event"]['forum_id'];
            } else {
                $user_check_data['event_id'] = $this->session->data["register_event"]['master_class_id'];
                $user_check_data['forum_id'] = $this->session->data["register_event"]['forum_id'];
            }

            $user_check_data['contact_ids'] = $this->model_register_register->getAlternateUsers($user_check_data['contact_id']);

            $register_exists = $this->model_register_register->checkRegistration($user_check_data['type'], $user_check_data['event_id'], $user_check_data['forum_id'], $user_check_data['contact_ids'], $user_check_data['fields']);
            $data['register_exists'] = $register_exists;
            $this->session->data['register_event']['register_exists'] = $register_exists;

            $this->session->data['register_user'] = $user_data;

            $data['user'] = $user_data;
            $data['phone'] = $this->session->data['register_phone'];

            $data['button_register'] = 'Зарегистрироваться';

            $data['show_name'] = true;
            $data['show_notme'] = false;
            $data['search'] = $data["user"]["company"];

            $filter_data = array(
                'filter_start' => 0,
                'filter_limit' => 10
            );

            $filter_data['filter_name'] = $data["user"]["company"];
            $filter_data['filter_address'] = $data["user"]["city"];
            $filter_data['filter_site'] = $data["user"]["company_site"];

            $results = [];

            if ($this->session->data["register_event"]['company_add']) {
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
            }

            if ($data['companies'] && !$this->session->data['register_event']['company_second_choice'] && $user_data['isCompanyChanged']) {
                $data['second_choice'] = true;
                $this->session->data['register_event']['company_second_choice'] = true;

                $data['company_second_choice'] = $this->session->data['register_event']['company_second_choice'];

                $data['text_result'] = 'По запросу <strong>“' . $data["user"]["company"] . '”</strong> ' . $this->model_themeset_themeset->trueText(count($data['companies']), 'найдена', 'найдено', 'найдено') . ' ' . count($data['companies']) . ' ' . $this->model_themeset_themeset->trueText(count($data['companies']), 'компания', 'компании', 'компаний');
                $data['company_template'] = $this->load->view('register/_brand_results', $data);

                $return['template'] = $this->load->view('register/event_user_change', $data);
            } else {
                $return['template'] = $this->load->view('register/event_user_main', $data);
            }


            if ($this->write_log) {
                $log = array(
                    'step' => 'Сохранение данных -- SUCCESS',
                    'session' => $sid,
                    'browser' => $_SERVER['HTTP_USER_AGENT'],
                    'user_data' => $user_data,
                );
                $this->model_register_register->log($log, 'info');
            }
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function checkPromo()
    {
        $type = '';

        switch ($this->session->data['register_event']['type']) {
            case 'webinar':
                $this->register();
                break;
            case 'master_class':
                $this->session->data['register_event']['register_exists']['deal_id'] ? $this->register() : $this->showPromo();
                break;
            default:
                $this->showPromo();
        }
    }

    public function showPromo()
    {

        $this->load->model('register/register');

        $return = array();
        $data = array();
        $error = false;

        $sid = $this->request->post['sid'];
        $data['session'] = $sid;
        $data['attention'] = $this->attention;
        $data['attention_text'] = $this->attention_text;

        // нет хэша
        if (empty($this->session->data['register_hash']) || empty($this->session->data['register_phone']) || empty($this->session->data['register_user'])) {
            $error = true;
            $return['reload'] = true;
            $return['error'] = 'Сессия истекла';
        }

        if ($error && $this->write_log) {
            $log = array(
                'step' => 'Страница промокода -- ERROR',
                'session' => $sid,
                'browser' => $_SERVER['HTTP_USER_AGENT'],
                'error' => $return['error'],
            );
            $this->model_register_register->log($log, 'info');
        }

        // все ок, ошибок нет
        if (!$error) {

            $user_data = $this->session->data['register_user'];

            $data['user'] = $user_data;
            $data['phone'] = $this->session->data['register_phone'];

            $data['title'] = 'У вас есть промокод?';
            $return['template'] = $this->load->view('register/event_promo', $data);


            if ($this->write_log) {
                $log = array(
                    'step' => 'Страница промокода -- SUCCESS',
                    'session' => $sid,
                    'browser' => $_SERVER['HTTP_USER_AGENT'],
                    'user_data' => $user_data,
                );
                $this->model_register_register->log($log, 'info');
            }

        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    public function register()
    {
        $this->load->model('register/register');
        $this->load->model('themeset/expert');
        $this->load->model('visitor/expert');

        $return = array();
        $data = array();
        $error = false;

        $sid = $this->request->post['sid'];
        $data['session'] = $sid;
        $data['attention'] = $this->attention;
        $data['attention_text'] = $this->attention_text;

        $promo = !empty($this->request->post['promo']) ? $this->request->post['promo'] : '';
        $hasPromo = !empty($this->request->post['hasPromo']) ? $this->request->post['hasPromo'] : 0;

        // нет хэша
        if (empty($this->session->data['register_hash']) || empty($this->session->data['register_phone']) || empty($this->session->data['register_user'])) {
            $error = true;
            $return['reload'] = true;
            $return['error'] = 'Сессия истекла';
        }

        // проверка промо
        if ($hasPromo && empty($promo)) {
            $error = true;
            $return['error'] = 'Введите промокод';
        }

//        $old_company = $this->model_register_register->getCompanyByB24id(!empty($this->session->data['register_user']['b24_company_old_id']) ? $this->session->data['register_user']['b24_company_old_id'] : 0);

        //нужно для регистрации для отслеживания изменений данных о компании
//        $isCompanyEdit = !empty($old_company) && ($old_company["city"] !== $this->session->data['register_user']['city'] ||
//            $old_company["web"] !== $this->session->data['register_user']['company_site'] ||
//            $old_company["phone"] !== $this->session->data['register_user']['company_phone'] ||
//            $old_company["activity"] !== $this->session->data['register_user']['company_activity']);

        $user_data = array(
            'user_id' => $this->session->data['register_user']['user_id'],
            'old_user_id' => $this->session->data['register_user']['old_user_id'],
            'isExpert' => $this->model_visitor_expert->isExpert($this->session->data['register_user']['old_user_id']) ?? false,
            'phone' => $this->session->data['register_phone'],
            'name' => $this->session->data['register_user']['name'],
            'lastname' => $this->session->data['register_user']['lastname'],
            'email' => $this->session->data['register_user']['email'],
            'post' => $this->session->data['register_user']['post'],
            'company' => htmlspecialchars_decode($this->session->data['register_user']['company']),
            'userFieldsChanged' => $this->session->data['register_user']['userFieldsChanged'],
            'isCompanyChanged' => $this->session->data['register_user']['isCompanyChanged'],
            'company_status' => $this->session->data['register_user']['company_status'],
            'company_phone' => $this->session->data['register_user']['company_phone'],
            'company_site' => $this->session->data['register_user']['company_site'],
            'company_activity' => $this->session->data['register_user']['company_activity'],
            'company_country' => isset($this->session->data["register_event"]['company_country']) ? $this->session->data["register_event"]['company_country'] : '',
            'company_inn' => isset($this->session->data["register_event"]['company_inn']) ? $this->session->data["register_event"]['company_inn'] : '',
            'company_address' => isset($this->session->data["register_event"]['company_address']) ? $this->session->data["register_event"]['company_address'] : '',
            'company_director' => isset($this->session->data["register_event"]['company_director']) ? $this->session->data["register_event"]['company_director'] : '',
            'city' => $this->session->data['register_user']['city'],
            'b24_company_id' => !empty($this->session->data['register_user']['b24_company_id']) ? $this->session->data['register_user']['b24_company_id'] : 0,
            'b24_company_old_id' => !empty($this->session->data['register_user']['b24_company_old_id']) ? $this->session->data['register_user']['b24_company_old_id'] : 0,
        );

        if ($error && $this->write_log) {
            $log = array(
                'step' => 'Регистрация -- ERROR',
                'session' => $sid,
                'browser' => $_SERVER['HTTP_USER_AGENT'],
                'error' => $return['error'],
                'user_data' => $user_data,
            );
            $this->model_register_register->log($log, 'info');
        }

        $user_check_data = [];

        $user_check_data['fields'] = [
            'contact_name' => $user_data['name'],
            'contact_last_name' => $user_data['lastname'],
            'contact_phone' => $this->session->data['register_phone'],
            'contact_email' => $this->session->data['register_email'],
        ];

        $user_check_data['type'] = '';

        if ($this->session->data["register_event"]['webinar_id']) {
            $user_check_data['type'] = 'webinar';
        } elseif (isset($this->session->data["register_event"]['forum_id']) && !isset($this->session->data["register_event"]['master_class_id'])) {
            $user_check_data['type'] = 'forum';
        } else {
            $user_check_data['type'] = 'master_class';
        }

        $user_check_data['event_id'] = '';

        if ($user_check_data['type'] === 'webinar') {
            $user_check_data['event_id'] = $this->session->data["register_event"]['webinar_id'];
        } elseif ($user_check_data['type'] === 'forum') {
            $user_check_data['event_id'] = $this->session->data["register_event"]['forum_id'];
        } else {
            $user_check_data['event_id'] = $this->session->data["register_event"]['master_class_id'];
            $user_check_data['forum_id'] = $this->session->data["register_event"]['forum_id'];
        }

        $user_check_data['contact_ids'] = $this->model_register_register->getAlternateUsers($user_check_data['contact_id']);

        $register_exists = $this->model_register_register->checkRegistration($user_check_data['type'], $user_check_data['event_id'], $user_check_data['forum_id'], $user_check_data['contact_ids'], $user_check_data['fields']);

        if ((is_array($register_exists) && $register_exists["registration_id"]) ||
            (!is_array($register_exists) && $register_exists)) {
            $data['title'] = 'Пользователь уже зарегистрирован.';

            $return['template'] = $this->load->view('register/event_success', $data);
        } else {
            // все ок, ошибок нет
            if (!$error) {
                switch (true) {
                    /* данные не менялись */
                    case (!$user_data['userFieldsChanged'] && !$user_data['isCompanyChanged'] && $user_data['old_user_id']):
                        $contact_id = $user_data['old_user_id'];
                        break;

                    /* данные поменялись */
                    case (($user_data['userFieldsChanged'] || $user_data['isCompanyChanged']) && $user_data['old_user_id']):
                        $return_contact = $this->model_register_register->createContact($user_data);
                        $contact_id = $return_contact['id'];

                        if ($user_data['old_user_id'] !== $contact_id) {
                            $this->model_register_register->addAlternateId($user_data['old_user_id'], $contact_id);
                        }
                        // $this->model_register_register->updateExpertID($user_data['old_user_id'], $contact_id);
                        break;

                    /* новый контакт */
                    default:
                        $user_data['isExpert'] = false;
                        $return_contact = $this->model_register_register->createContact($user_data);
                        $contact_id = $return_contact['id'];
                }

                if (!empty($return_contact)) {
                    $contact_info = $return_contact;
                } else {
                    $contact_info = $this->model_register_register->getContactInfo($contact_id);
                }



                $log = array(
                    'step' => 'Создание контакта',
                    'session' => $sid,
                    'browser' => $_SERVER['HTTP_USER_AGENT'],
                    'error' => $return['error'],
                    'return_contact' => $return_contact,
                    'contact_info' => $contact_info,
                );
                $this->model_register_register->log($log, 'info');
                $master_class_info = [];

                switch ($this->session->data['register_event']['type']) {
                    case 'webinar':
                        $url = $_SERVER['HTTP_REFERER'];

                        $urlComponents = parse_url($url);

                        parse_str($urlComponents['query'], $queryParams);
                        $webinarId = $queryParams['webinar_id'];

                        $event_info = array(
                            'company_id' => $contact_info['COMPANY_ID'] ? $contact_info['COMPANY_ID'] : $contact_info['company_id'],
                            'contact_id' => $contact_id,
                            'dealType' => 'webinar',
                            'webinar_id' => $this->session->data['register_event']['webinar_id'] ? $this->session->data['register_event']['webinar_id'] : $webinarId,
                        );
                        $type_text = 'вебинар';
                        break;
                    case 'master_class':
                        $url = $_SERVER['HTTP_REFERER'];

                        $urlComponents = parse_url($url);

                        parse_str($urlComponents['query'], $queryParams);
                        $masterClassId = $queryParams['master_class_id'];
                        $forumId = $queryParams['forum_id'];

                        $event_info = array(
                            'company_id' => $contact_info['COMPANY_ID'] ? $contact_info['COMPANY_ID'] : $contact_info['company_id'],
                            'contact_id' => $contact_id,
                            'dealType' => 'forum',
                            'forum_id' => $this->session->data['register_event']['forum_id'] ? $this->session->data['register_event']['forum_id'] : $forumId,
                            'promocode' => $hasPromo && !empty($promo) ? $promo : ''
                        );

                        $master_class_info = array(
                            'company_id' => $contact_info['COMPANY_ID'] ? $contact_info['COMPANY_ID'] : $contact_info['company_id'],
                            'contact_id' => $contact_id,
                            'dealType' => 'master_class',
                            'forum_id' => $this->session->data['register_event']['forum_id'] ? $this->session->data['register_event']['forum_id'] : $forumId,
                            'master_class_id' => $this->session->data['register_event']['master_class_id'] ? $this->session->data['register_event']['master_class_id'] : $masterClassId,
                        );
                        $type_text = 'мастер-класс';
                        break;
                    default:
                        $url = $_SERVER['HTTP_REFERER'];

                        $urlComponents = parse_url($url);

                        parse_str($urlComponents['query'], $queryParams);
                        $forumId = $queryParams['forum_id'];

                        $event_info = array(
                            'company_id' => $contact_info['COMPANY_ID'] ? $contact_info['COMPANY_ID'] : $contact_info['company_id'],
                            'contact_id' => $contact_id,
                            'dealType' => 'forum',
                            'forum_id' => $this->session->data['register_event']['forum_id'] ? $this->session->data['register_event']['forum_id'] : $forumId,
                            'promocode' => $hasPromo && !empty($promo) ? $promo : ''
                        );
                        $type_text = 'форум';
                }

                $deal_info = [];
                $smart_proccess_info = [];

                if ($this->session->data['register_event']['type'] === 'master_class') {
                    if ($this->session->data['register_event']['register_exists']['deal_id']) {
                        $master_class_info['deal_id'] = $this->session->data['register_event']['register_exists']['deal_id'];
                    } else {
                        $deal_info = $this->model_register_register->createDeal($event_info);
                        $master_class_info['deal_id'] = $deal_info['id'];
                    }

                    $smart_proccess_info = $this->model_register_register->createSmartProccess($master_class_info);

                } else {
                    $deal_info = $this->model_register_register->createDeal($event_info);
                }

                if (($this->session->data['register_event']['type'] === 'master_class' && $smart_proccess_info['code'] == 200)) {
                    if ($deal_info['code'] == 200 || $master_class_info['deal_id']) {
                        $return['old_id'] = $this->session->data['register_user']['user_id'];
                        $return['new_id'] = $contact_id;
                        $return['contact_info'] = $contact_info;

                        if (!empty($deal_info)) {
                            $return['deal_info'] = $deal_info;
                            $return['smart_proccess_info'] = $smart_proccess_info;
                        } else {
                            $return['smart_proccess_info'] = $smart_proccess_info;
                        }

                        if (isset($deal_info['sum'])) {
                            $data['promocode'] = $deal_info['sum'] > 0 && $hasPromo ? $promo : '';
                            $data['price'] = (float)$deal_info['sum'] > 0 ? $deal_info['sum'] . ' руб.' : '';
                        } else {
                            $data['price'] = !empty($this->session->data['register_event']['price']) ? $this->session->data['register_event']['price'] . ' руб.' : '';
                            $data['promocode'] = '';
                        }

                        $data['type'] = $this->session->data['register_event']['type'];

                        if (!empty($deal_info['message']) && $deal_info['message'] === 'Exists') {
                            $data['title'] = $this->session->data['register_user']['name'] . ', Ваша заявка на модерации.';
                        } else {

                            $data['title'] = 'Ваша заявка на участие успешно подана. <br>Инструкции для посещения мы вышлем на указанный e-mail.';
                        }
                    } else {
                        $data['title'] = 'Произошла ошибка. Регистрация не завершена';

                        $error_text .= "REGISTER TYPE: " . $this->session->data['register_event']['type'];
                        $error_text .= "\nCONTACT_ID: " . $contact_id;
                        $error_text .= "\nCOMPANY_ID: " . $contact_info['COMPANY_ID'];

                        switch ($this->session->data['register_event']['type']) {
                            case 'webinar':
                                $error_text .= "\nWEBINAR_ID: " . $this->session->data['register_event']['webinar_id'];
                                break;
                            case 'forum':
                                $error_text .= "\nFORUM_ID: " . $this->session->data['register_event']['forum_id'];
                                break;
                        }
                        $error_text .= "\nRETURN: " . json_encode($deal_info);
                        $this->model_register_register->log($error_text);

                    }
                } else {
                    if ($deal_info['code'] == 200) {
                        $return['old_id'] = $this->session->data['register_user']['user_id'];
                        $return['new_id'] = $contact_id;
                        $return['contact_info'] = $contact_info;
                        $return['deal_info'] = $deal_info;

                        if (isset($deal_info['sum'])) {
                            $data['promocode'] = $deal_info['sum'] > 0 && $hasPromo ? $promo : '';
                            $data['price'] = (float)$deal_info['sum'] > 0 ? $deal_info['sum'] . ' руб.' : '';
                        } else {
                            $data['price'] = !empty($this->session->data['register_event']['price']) ? $this->session->data['register_event']['price'] . ' руб.' : '';
                            $data['promocode'] = '';
                        }

                        $data['type'] = $this->session->data['register_event']['type'];

                        if (!empty($deal_info['message']) && $deal_info['message'] === 'Exists') {
                            $data['title'] = $this->session->data['register_user']['name'] . ', Ваша заявка на модерации.';
                        } else {

                            $data['title'] = 'Ваша заявка на участие успешно подана. <br>Инструкции для посещения мы вышлем на указанный e-mail.';
                        }
                    } else {
                        $data['title'] = 'Произошла ошибка. Регистрация не завершена';

                        $error_text .= "REGISTER TYPE: " . $this->session->data['register_event']['type'];
                        $error_text .= "\nCONTACT_ID: " . $contact_id;
                        $error_text .= "\nCOMPANY_ID: " . $contact_info['COMPANY_ID'];

                        switch ($this->session->data['register_event']['type']) {
                            case 'webinar':
                                $error_text .= "\nWEBINAR_ID: " . $this->session->data['register_event']['webinar_id'];
                                break;
                            case 'forum':
                                $error_text .= "\nFORUM_ID: " . $this->session->data['register_event']['forum_id'];
                                break;
                        }
                        $error_text .= "\nRETURN: " . json_encode($deal_info);
                        $this->model_register_register->log($error_text);

                    }
                }

                $return['template'] = $this->load->view('register/event_success', $data);


                if ($this->write_log) {
                    $log = array(
                        'step' => 'Регистрация -- SUCCESS',
                        'session' => $sid,
                        'browser' => $_SERVER['HTTP_USER_AGENT'],
                        'user_data' => $user_data,
                        'deal_info' => $deal_info,
                        'event_info' => $event_info,
                    );
                    $this->model_register_register->log($log, 'register_info');
                }

            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($return));
    }

    private function companyCheck($data = [])
    {
        $this->load->model('register/register');

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

    }

}
