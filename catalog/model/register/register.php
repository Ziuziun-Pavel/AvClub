<?php

class ModelRegisterRegister extends Model
{

    // private $b24_list = 'https://avclub.bitrix24.ru/rest/669/2yt2mpuav23aqllx/';
    // private $sms_api = "9CD797DD-D632-769A-E825-91F5FC8F6F3D";
    private $sms_api = "8D9F0DF5-1661-4BC4-EC3C-34809F02114D";
    private $hash_key = "av_c1b";

    private $url_search = "http://clients.techin.by/avclub/site/api/v1/contact/search";
    private $url_contact = "http://clients.techin.by/avclub/site/api/v1/contact/";
    private $url_contact_visit = "http://clients.techin.by/avclub/site/api/v1/deal/getEventList";
    private $url_contact_create = "http://clients.techin.by/avclub/site/api/v1/contact/create";
    private $url_contact_update = "http://clients.techin.by/avclub/site/api/v1/contact/{id}/update";
    private $url_company_create = "http://clients.techin.by/avclub/site/api/v1/company/create";
    private $url_company_update = "http://clients.techin.by/avclub/site/api/v1/company/{id}/update";
    private $url_confirm_participation = "http://clients.techin.by/avclub/site/api/v1/deal/{id}/confirmParticipation";
    private $url_get_paid_publications = "http://clients.techin.by/avclub/site/api/v1/deal/get-paid-publication";
    private $url_get_pub_applications = "http://clients.techin.by/avclub/site/api/v1/deal/get-publication-list";
    private $url_check_registration = "http://clients.techin.by/avclub/site/api/v1/deal/check-registration";

    private $url_create_smart_proccess = "http://clients.techin.by/avclub/site/api/v1/smart-process/create";

    private $url_deal = "http://clients.techin.by/avclub/site/api/v1/deal/create";

    private $debug = 0;

    public function sendSMS($phone = '')
    {
        $return = array();

        $code = mt_rand(1000, 9999);

        /*require_once(DIR_SYSTEM . 'library/sms.ru.php');

        $smsru = new SMSRU($this->sms_api);
        $data_sms = new stdClass();
        $data_sms->to = preg_replace('/[^0-9]/', '', $phone);
        $data_sms->text = $code;
        $sms = $smsru->send_one($data_sms); */

        $message = 'Код для входа в личный кабинет: ';

        $message .= $code . ' ' . date('(d/m/y H:i)');
        $message .= ' www.avclub.pro';

        $ch = curl_init("https://sms.ru/sms/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "api_id" => $this->sms_api,
            "to" => preg_replace('/[^0-9]/', '', $phone),
            "msg" => $message,
            "json" => 1
        )));
        $body = curl_exec($ch);
        curl_close($ch);

        $sms = json_decode($body);


        if ($sms->status == "OK") {
            $return['success'] = true;
            $return['code'] = $code;
        } else if ($sms->status_code == 202) {
            $return['error_text'] = $sms->status_text;
            $return['error_code'] = $sms->status_code;
            $return['error'] = 'Укажите корректный номер телефона';

        } else {
            $return['success'] = true;
            $return['code'] = $code;

            $this->load->model('themeset/themeset');
            $alert_data = array(
                'status_text' => $sms->status_text,
                'status_code' => $sms->status_code,
            );
            $this->model_themeset_themeset->alert($alert_data, 'Ошибка sms.ru');
        }

        return $return;
    }

    public function sendCall($phone = '')
    {
        $return = array();

        $ch = curl_init("https://sms.ru/code/call");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "phone" => preg_replace('/[^0-9]/', '', $phone),
            "ip" => $_SERVER["REMOTE_ADDR"],
            "api_id" => $this->sms_api
        )));
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body);
        if ($json) {

            if ($json->status === "OK") {
                $return['success'] = true;
                $return['code'] = $json->code;
            } else {
                $return['error_text'] = $json->status_text;
                $return['error_code'] = $json->status_code;


                if ($json->status_code == 202) {
                    $return['error'] = 'Укажите корректный номер телефона';
                } else {
                    $return['error'] = 'Что-то пошло не так. Проверьте правильность номера и попробуйте еще раз.';
                }

            }

        } else {
            $return['error'] = "Запрос не выполнен. Не удалось установить связь с сервером";
        }

        return $return;
    }

    public function sendTest($phone = '')
    {
        $return['code'] = 1111;
        $return['success'] = true;

        return $return;
    }

    public function sendCodeToEmail($code = '', $email = '')
    {

        $data = array();

        $email_subject = 'Код подтверждения на avclub.pro';
        // $text = 'Проверочный код для входа: ' . $code;

        $smtp = array(
            'status' => true,
            'host' => 'ssl://smtp.timeweb.ru',
            'user' => 'test@avclub.pro',
            'password' => 'mc6l3ike79',
            'port' => 465,
        );

        $data['code'] = $code;

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }
        $data['name'] = $this->config->get('config_name');

        $data['logo'] = $server . 'image/mail/logo.png';
        $data['hello'] = $server . 'image/mail/hello2.png';

        $data['home'] = $this->url->link('common/home');
        $data['companies'] = $this->url->link('company/company');

        $data['themeset_soc'] = array(
            'tg' => array(
                'alt' => 'Telegram',
                'link' => $this->config->get('themeset_soc_tg'),
                'image' => $server . 'image/mail/tg-gray.png'
            ),
            /*'fb'		=> array(
                'alt'		=> 'Facebook',
                'link'	=> $this->config->get('themeset_soc_fb'),
                'image'	=> $server . 'image/mail/fb.png'
            ),*/
            'vk' => array(
                'alt' => 'VK',
                'link' => $this->config->get('themeset_soc_vk'),
                'image' => $server . 'image/mail/vk-gray.png'
            ),
            'you' => array(
                'alt' => 'YouTube',
                'link' => $this->config->get('themeset_soc_you'),
                'image' => $server . 'image/mail/you-gray.png'
            ),
        );

        $mail = new Mail();
        $mail->protocol = $this->config->get('av_alert_mail_protocol');
        $mail->parameter = $this->config->get('av_alert_mail_parameter');
        $mail->smtp_hostname = $this->config->get('av_alert_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('av_alert_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('av_alert_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('av_alert_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('av_alert_mail_smtp_timeout');

        $mail->setTo($email);
        $mail->setFrom($this->config->get('av_alert_mail_protocol') === 'smtp' ? $this->config->get('av_alert_mail_smtp_username') : $this->config->get('av_alert_email'));
        $mail->setSender('АВ Клуб | AV Club');
        $mail->setSubject($email_subject);

        $mail->setHtml($this->load->view('register/mail_code', $data));
        // $mail->setText($text);
        $mail->send();
    }

    public function getCompanyNameByB24id($b24_company_id = 0)
    {
        $company_name = '';

        $query_company = $this->db->query("SELECT DISTINCT c.title 
			FROM " . DB_PREFIX . "company_names c
			WHERE 
			c.b24id = '" . (int)$b24_company_id . "' 
			AND c.archive = '0'
			");
        if ($query_company->num_rows) {
            $company_name = $query_company->row['title'];
        }

        return $company_name;
    }

    public function getCompanyByB24id($b24_company_id = 0)
    {
        $company_data = array();

        $query_company = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "company_names c
        WHERE 
        c.b24id = '" . (int)$b24_company_id . "' 
        AND c.archive = '0'
        ");

        if ($query_company->num_rows) {
            $company_data = $query_company->row;
        }

        return $company_data;
    }

    public function getCompanyNames($data = array())
    {
        $show_disabled = !empty($data['filter_disabled']) ? true : false;

        $sql = "SELECT * FROM " . DB_PREFIX . "company_names c ";

        $sql .= " WHERE c.archive = '0' ";

        if (!empty($data['filter_b24id'])) {
            $sql .= " AND c.b24id = '" . (int)$data['filter_b24id'] . "'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND (";

            $implode = array();

            $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

            foreach ($words as $word) {
                $word = htmlspecialchars_decode($word);
                $implode[] = "(c.title LIKE '%" . $this->db->escape($word) . "%' OR c.alternate LIKE '%" . $this->db->escape($word) . "%')";
            }

            if ($implode) {
                $sql .= " " . implode(" AND ", $implode) . "";
            }

            $sql .= ")";
        }

        if (!empty($data['filter_address'])) {
            $city = $this->db->escape($data['filter_address']);
            $sql .= " AND ('" . $city . "' LIKE CONCAT('%', c.city, '%'))";
        }

        if (!empty($data['filter_inn'])) {
            $sql .= " AND c.inn = '" . (int)$data['filter_inn'] . "'";
        }

        if (!empty($data['filter_site'])) {
            $sql .= " AND c.web = '" . $data['filter_site'] . "'";
        }

        $sql .= " GROUP BY c.b24id";

        $sql .= " ORDER BY LCASE(c.title) ASC";


        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        foreach ($query->rows as &$row) {
            $name = explode('/', $row['title']);

            $row['name'] = $name[0];

            $company_data[] = $row;
        }

        return $company_data;
    }

    public function validateCode($code = 0)
    {
        return !empty($this->session->data['register_hash']) && $this->session->data['register_hash'] === $this->hashCode($code) ? true : false;
    }

    public function hashCode($number = 0)
    {

        return sha1($number . sha1(sha1($number)));
    }

    public function hashId($number = 0)
    {
        return sha1($this->hash_key . $number . $this->hash_key . sha1($this->hash_key . sha1($number)));
    }

    public function hideEmail($email = '')
    {
        $em = explode("@", $email);
        $name = implode('@', array_slice($em, 0, count($em) - 1));
        $half = floor(strlen($name) / 2);
        $plus = floor($half / 2);

        return substr($name, 0, $half - $plus) . str_repeat('*', $half) . substr($name, $half - $plus + $half, strlen($name)) . "@" . end($em);
    }

    public function searchContactByPhone($phone = '')
    {

        $ch = curl_init($this->url_search);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "phone" => $phone
        )));
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body, true);

        $contact_id = !empty($json['id']) ? $json['id'] : 0;


        $log = new Log('expert_search.log');
        $message = "\n------------------\n";
        if (is_array($json) && $json) {
            foreach ($json as $key => $value) {
                $message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
            }
        } else if ($json) {
            $message .= $json;
        }
        $log->write($message);


        return $contact_id;
    }

    public function getEventList($contact_id = 0, $type)
    {
        $return_list = array();

        $fields = array(
            'contact_id' => $contact_id,
            'type' => $type
        );

        $ch = curl_init($this->url_contact_visit);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body, true);

        if (!empty($json['forumList'])) {
            foreach ($json['forumList'] as &$event_item) {
                $event_item['type_event'] = 'forum';
                $return_list[] = $event_item;
            }
        }

        if (!empty($json['webinarList'])) {
            foreach ($json['webinarList'] as &$event_item) {
                $event_item['type_event'] = 'webinar';
                $return_list[] = $event_item;
            }
        }

        return $return_list;
    }

    public function checkRegistration($dealType, $eventId, $master_class_forum_id = 0, $contact_ids = array(), $contact_fields = array())
    {

        if ($master_class_forum_id) {
            if($contact_ids[0]) {
                $fields = array(
                    'dealType' => $dealType,
                    'master_class_id' => $eventId,
                    'forum_id' => $master_class_forum_id,
                    'contact_ids' => $contact_ids,
                );
            } else {
                $fields = array(
                    'dealType' => $dealType,
                    'master_class_id' => $eventId,
                    'forum_id' => $master_class_forum_id,
                    'contact_name' => $contact_fields['contact_name'],
                    'contact_last_name' => $contact_fields['contact_last_name'],
                    'contact_phone' => $contact_fields['contact_phone'],
                    'contact_email' => $contact_fields['contact_email'],
                );
            }
        } else {
            if($contact_ids[0]) {
                $fields = array(
                    'dealType' => $dealType,
                    'event_id' => $eventId,
                    'contact_ids' => $contact_ids,
                );
            } else {
                $fields = array(
                    'dealType' => $dealType,
                    'event_id' => $eventId,
                    'contact_name' => $contact_fields['contact_name'],
                    'contact_last_name' => $contact_fields['contact_last_name'],
                    'contact_phone' => $contact_fields['contact_phone'],
                    'contact_email' => $contact_fields['contact_email'],
                );
            }
        }

        $ch = curl_init($this->url_check_registration);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body, true);
        if ($master_class_forum_id) {
            return [
                'deal_id' => $json['deal_id'],
                'registration_id' => $json['registration_id'],
            ];
        } else {
            return $json['exist'];
        }
    }

    public function getPaidPublications($contact_id = 0)
    {
        $return_list = array();

        $fields = array(
            'contact_id' => $contact_id,
        );
        $ch = curl_init($this->url_get_paid_publications);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body, true);

        if (!empty($json['list'])) {
            foreach ($json['list'] as &$item) {
                $return_list[] = $item;
            }
        }

        return $return_list;
    }

    public function getPubApplications($contact_id = 0)
    {
        $return_list = array();

        $fields = array(
            'contact_id' => $contact_id,
        );
        $ch = curl_init($this->url_get_pub_applications);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body, true);

        if (!empty($json['list'])) {
            foreach ($json['list'] as &$item) {
                $return_list[] = $item;
            }
        }

        return $return_list;
    }

    public function confirmParticipation($deal_id = 0, $type)
    {
        $fields = array(
            'type' => $type
        );

        $id_to_update = (int)$deal_id;

        $url = str_replace('{id}', $id_to_update, $this->url_confirm_participation);

        $ch_deal = curl_init($url);
        curl_setopt($ch_deal, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_deal, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch_deal, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch_deal, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch_deal, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $body = curl_exec($ch_deal);

        curl_close($ch_deal);

        $json = json_decode($body, true);

        return $json;
    }

    public function getContactInfo($id = 0)
    {

        $contact_info = array();

        $ch = curl_init($this->url_contact . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body, true);

        $contact_info = !empty($json['contact']) ? $json['contact'] : array();

        if (!empty($json['contact'])) {
            $this->load->model('themeset/expert');
            $this->model_themeset_expert->getContactInfo($id, false, true, array('user_data' => $json['contact']));
        }

        return $contact_info;
    }

    public function getAlternateUsers($contact_id = 0)
    {
        $result_arr = [];

        $vis_id = $this->getExpertId($contact_id);

        $result_arr[] = $contact_id;

        $query_expert = $this->db->query("SELECT DISTINCT va.b24id	 FROM " . DB_PREFIX . "visitor_alternate va 
			WHERE va.visitor_id = '" . (int)$vis_id . "' AND va.b24id <> ''  AND va.b24id <> '0' ");

        if ($query_expert->num_rows) {
            foreach ($query_expert->rows as $val) {
                $result_arr[] = (int)$val['b24id'];
            }
        }

        return $result_arr;
    }

    public function updateExpertID($old_id = 0, $new_id = 0)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "visitor SET 
			b24id = '" . (int)$new_id . "' 
			WHERE 
			b24id = '" . (int)$old_id . "' ");
    }

    public function updateContact($data = array())
    {
        return $this->updateContactInfo($data, false);
    }

    public function createContact($data = array())
    {
        return $this->updateContactInfo($data, false);
    }

    private function updateContactInfo($data = array(), $new = false)
    {

        /*{
            'old_id' : <старый id контакта, если данные поменялись>,
            'name' : <имя>,
            'last_name' : <фамилия>,
            'post' : <должность>,
            'email' : <email>,
            'phone' : <телефон>,
            'company_id' : <id компании>,
            'company_name': <название компании>,
            'company_city': <город компании>,
            'company_phone': <телефон компании>,
             'company_site': <сайт компании>,
             'company_activity': <массив активностей в proAV>,

         }*/

        $contact_info = array(
            'name' => $data['name'],
            'last_name' => $data['lastname'],
            'post' => $data['post'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'userFieldsChanged' => $data["userFieldsChanged"] ?? false,
            'isCompanyChanged' => $data["isCompanyChanged"] ?? false,
            'IsCompanyEdit' => $data["IsCompanyEdit"] ?? false,
            'company_city' => $data['city'],
            'company_name' => $data['company'],
            'company_phone' => $data['company_phone'],
            'company_site' => $data['company_site'],
            'company_country' => $data['company_country'],
            'company_inn' => $data['company_inn'],
            'company_activity' => array($data['company_activity']),
            'b24_company_old_id' => $data['b24_company_old_id'],
        );

        if (!empty($data['old_user_id'])) {
            $contact_info['old_id'] = $data['old_user_id'];
            $new = false;
        }

        if (!empty($data['b24_company_id'])) {
            $contact_info['company_id'] = $data['b24_company_id'];
        } else {
            if ($contact_info['IsCompanyEdit']) {
                /* # EDIT COMPANY */
                $company_id_to_update = $contact_info['b24_company_old_id'];

                $company_fields = array(
                    'company_name' => $contact_info['company_name'],
                    'company_city' => $contact_info['company_city'],
                    'company_phone' => $contact_info['company_phone'],
                    'company_site' => $contact_info['company_site'],
                    'company_country' => $contact_info['company_country'],
                    'company_inn' => $contact_info['company_inn'],
                    'company_activity' => $contact_info['company_activity'],
                );

                if (!empty($contact_info['b24_company_old_id'])) {
                    $company_fields['old_id'] = $contact_info['b24_company_old_id'];
                }

                $url = str_replace('{id}', $company_id_to_update, $this->url_company_update);

                $ch_company = curl_init($url);
                curl_setopt($ch_company, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch_company, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch_company, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch_company, CURLOPT_POSTFIELDS, json_encode($company_fields));
                curl_setopt($ch_company, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

                $body_company = curl_exec($ch_company);

                curl_close($ch_company);

                $json_company = json_decode($body_company, true);

                if (!empty($json_company['code']) && $json_company['code'] == 200) {
                    $contact_info['company_id'] = $json_company['id'];
                } else {
                    $error_text = "Обновление компании";
                    $error_text .= "\nINPUT: " . json_encode($company_fields);
                    $error_text .= "\nRETURN: " . json_encode($json_company);
                    $this->log($error_text);
                }
                /* # EDIT COMPANY */
            } else {

                /* CREATE NEW COMPANY */
                $company_fields = array(
                    'company_id' => $contact_info['company_name'],
                    'company_name' => htmlspecialchars_decode($contact_info['company_name']),
                    'company_city' => $contact_info['company_city'],
                    'company_phone' => $contact_info['company_phone'],
                    'company_site' => $contact_info['company_site'],
                    'company_country' => $contact_info['company_country'],
                    'company_inn' => $contact_info['company_inn'],
                    'company_activity' => $contact_info['company_activity'],
                );

                if (!empty($contact_info['b24_company_old_id'])) {
                    $company_fields['old_id'] = $contact_info['b24_company_old_id'];
                }

                $ch_company = curl_init($this->url_company_create);
                curl_setopt($ch_company, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch_company, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch_company, CURLOPT_POSTFIELDS, http_build_query($company_fields));
                $body_company = curl_exec($ch_company);
                curl_close($ch_company);

                $json_company = json_decode($body_company, true);

                if (!empty($json_company['code']) && $json_company['code'] == 200) {
                    $contact_info['company_id'] = $json_company['id'];
                } else {
                    $error_text = "Создание компании";
                    $error_text .= "\nINPUT: " . json_encode($company_fields);
                    $error_text .= "\nRETURN: " . json_encode($json_company);
                    $this->log($error_text);
                }
                /* # CREATE NEW COMPANY */
            }
        }

        $more_fields = array(
            'expertise' => 'expertise',
            'useful' => 'useful',
            'regalia' => 'merit',
            'photo' => 'photo',
        );

        foreach ($more_fields as $key => $send_key) {
            if (isset($data[$key])) {
                $contact_info[$send_key] = $data[$key];
            }
        }

        // $this->log($contact_info, 'info');

        /*if(!empty($data['b24_company_id'])) {
            $contact_info['company_id'] = $data['b24_company_id'];
        }else{
            $contact_info['company_name'] = $data['company'];
        }*/
        if ($contact_info['userFieldsChanged']) {
            $new = false;
        }
        if ($contact_info['isCompanyChanged']) {
            $new = true;
        }

        if ($contact_info['userFieldsChanged'] || $contact_info['isCompanyChanged'] || $contact_info['IsCompanyEdit']) {
            if ($new) {
                $url = $this->url_contact_create;
            } else {
                $url = str_replace('{id}', $data['old_user_id'], $this->url_contact_update);
            }

            if ($this->debug) {
                $contact_info['debug'] = 1;
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($contact_info));
            $body = curl_exec($ch);
            curl_close($ch);

            $json = json_decode($body, true);

            if (empty($json['code']) || $json['code'] != 200) {
                $error_text = $url === $this->url_contact_create ? "Создание контакта" : "Обновление контакта";
                $error_text .= "\nINPUT: " . json_encode($contact_info);
                $error_text .= "\nRETURN: " . json_encode($json);
                $this->log($error_text);
            }

            $json['COMPANY_ID'] = !empty($contact_info['company_id']) ? $contact_info['company_id'] : 0;

            $contact_id = !empty($json['id']) ? $json['id'] : 0;

            return $json;
        } else {
            return array(
                "message" => "Ok",
                "code" => 200,
                "id" => $data['old_user_id'],
                "company_id" => $contact_info['company_id'],
                "COMPANY_ID" => !empty($contact_info['company_id']) ? $contact_info['company_id'] : 0
            );
        }
    }

    public function addAlternateId($main_id, $new_id)
    {

        if (empty($main_id) || empty($new_id)) {
            return;
        }

        $visitor_id = $this->getExpertId($main_id);

        if (!$visitor_id) {
            return;
        }

        $existingRecord = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor_alternate WHERE visitor_id = '" . (int)$visitor_id . "' AND b24id = '" . (int)$new_id . "'");

        if ($existingRecord->num_rows) {
            return;
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "visitor_alternate SET 
			visitor_id = '" . (int)$visitor_id . "', 
			b24id = '" . (int)$new_id . "' 
			");
    }

    public function createDeal($data = array())
    {

        /*{
         'company_id' : <id компании>,
         'contact_id' : <id контакта>,
         'participation_type' : <тип участия (делегат/спикер)>,
         'forum_id': <id форума>,
         'promocode': <промокод>,
        }*/

        if ($this->debug) {
            $data['debug'] = 1;
        }

        $ch = curl_init($this->url_deal);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body, true);


        if (empty($json['code']) || $json['code'] != 200) {
            $error_text = "Создание сделки";
            $error_text .= "\nINPUT: " . json_encode($data);
            $error_text .= "\nRETURN: " . json_encode($json);
            $this->log($error_text);
        }

        return $json;
    }

    public function createSmartProccess($data = array())
    {

        /*{
            spType: <master_class>
            contact_id
            company_id
            forum_id
            master_class_id
            deal_id
        }*/

        $fields = [
            'spType' => 'master_class',
            'contact_id' => $data['contact_id'],
            'company_id' => $data['company_id'],
            'forum_id' => $data['forum_id'],
            'master_class_id' => $data['master_class_id'],
            'deal_id' => $data['deal_id']
        ];

        if ($this->debug) {
            $data['debug'] = 1;
        }

        $ch = curl_init($this->url_create_smart_proccess);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body, true);


        if (empty($json['code']) || $json['code'] != 200) {
            $error_text = "Создание смарт-процесса";
            $error_text .= "\nINPUT: " . json_encode($data);
            $error_text .= "\nRETURN: " . json_encode($json);
            $this->log($error_text);
        }

        return $json;
    }


    public function getExpertId($b24id = 0)
    {
        $expert_id = 0;

        $query_expert = $this->db->query("SELECT DISTINCT v.visitor_id FROM " . DB_PREFIX . "visitor v 
			WHERE v.b24id = '" . (int)$b24id . "' AND v.b24id <> ''  AND v.b24id <> '0' ");

        if ($query_expert->num_rows) {
            $expert_id = $query_expert->row['visitor_id'];
        }

        return $expert_id;
    }

    public function getB24Id($contact_id = 0)
    {
        $b24id = 0;

        $query_expert = $this->db->query("SELECT DISTINCT v.b24id FROM " . DB_PREFIX . "visitor v 
			WHERE v.visitor_id = '" . (int)$contact_id . "' AND v.b24id <> ''  AND v.b24id <> '0' ");

        if ($query_expert->num_rows) {
            $b24id = $query_expert->row['b24id'];
        }

        return $b24id;
    }

    public function log($data, $type = 'error')
    {

        switch ($type) {
            case 'error':
                $file = 'register_error1.log';
                break;
            case 'login':
                $file = 'login.log';
                break;

            default:
                $file = 'register1.log';
        }


        $log = new Log($file);

        $message = "\n------------------\n";

        if (is_array($data) && $data) {
            foreach ($data as $key => $value) {
                $message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
            }
        } else if ($data) {
            $message .= $data;
        }

        $log->write($message);

    }

}