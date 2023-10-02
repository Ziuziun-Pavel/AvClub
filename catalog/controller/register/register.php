<?php
class ControllerRegisterRegister extends Controller {


	public function test() {

		$this->load->model('register/register');

		$phone = '+375336719310';

		$contact_id = $this->model_register_register->searchContactByPhone($phone);

		// $contact_info = $this->model_register_register->getContactInfo($contact_id);

		echo '<pre>';
		print_r($contact_id);
		echo '</pre>';

		/*echo '<pre>';
		print_r($contact_info);
		echo '</pre>';*/

		$ch = curl_init("http://clients.techin.by/avclub/site/api/v1/contact/43913");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);

		$body = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($body, true);

		echo '<pre>';
		print_r($body);
		echo '</pre>';

		echo '<pre>';
		print_r($json);
		echo '</pre>';
	}

	public function autocompleteCompanies() {
		$json = array();

		$filter_disabled = 0;

		if (!empty($this->request->get['filter_disabled'])) {
			$filter_disabled = (int)$this->request->get['filter_disabled'];
		}

		$results = array();

		$this->load->model('register/register');

			// COMPANY
		$filter_name = $this->request->get['filter_company'];
		$filter_limit = !empty($this->request->get['filter_limit']) ? (int)$this->request->get['filter_limit'] : 5; 

		$filter_data = array(
			'filter_name'  			=> $filter_name,
			'start'        			=> 0,
			'limit'       			=> $filter_limit
		);
		$results = $this->model_register_register->getCompanyNames($filter_data);
		foreach ($results as $result) {
			$json[] = array(
				'b24id'  				=> !empty($result['b24id']) ? $result['b24id'] : 0,
				'id'  					=> !empty($result['b24id']) ? $result['b24id'] : 'new',
				'title'    			=> $result['title']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function sendFail() {

		$emails = array(
//			'kolikun@yandex.ru',
//			'mmaksimmilliann@mail.ru'
		);

		$title = 'Ошибка регистрации / авторизации';

		$email_subject = 	$title . ' - ' . $this->config->get('config_name');


		$mail = new Mail();
		$mail->protocol = $this->config->get('av_alert_mail_protocol');
		$mail->parameter = $this->config->get('av_alert_mail_parameter');
		$mail->smtp_hostname = $this->config->get('av_alert_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('av_alert_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('av_alert_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('av_alert_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('av_alert_mail_smtp_timeout');

		$mail->setTo('p.ziuziun@techin.by');
		$mail->setFrom($this->config->get('av_alert_mail_protocol') === 'smtp' ? $this->config->get('av_alert_mail_smtp_username') : $this->config->get('av_alert_email'));
		$mail->setSender('АВ Клуб | AV Club');
		$mail->setSubject($email_subject);

		$text = '';
		$html = '';

		if(isset($this->request->post['name'])){
			$html .=  '<strong>Имя: </strong> '. $this->request->post['name'] . "<br>" ;
			$text .=  'Имя:  '. $this->request->post['name']  . "\n\n" ;
		}

		if(isset($this->request->post['company'])){
			$html .=  '<strong>Компания: </strong> '. $this->request->post['company'] . "<br>" ;
			$text .=  'Компания:  '. $this->request->post['company']  . "\n\n" ;
		}
		if(isset($this->request->post['phone'])){
			$html .=  '<strong>Телефон: </strong> '. $this->request->post['phone'] . "<br>" ;
			$text .=  'Телефон:  '. $this->request->post['phone']  . "\n\n" ;
		}
		if(isset($this->request->post['email'])){
			$html .=  '<strong>E-mail: </strong> '. $this->request->post['email'] . "<br>" ;
			$text .=  'E-mail:  '. $this->request->post['email']  . "\n\n" ;
		}
		if(isset($this->request->post['message'])){
			$html .=  '<strong>Сообщение: </strong> '. $this->request->post['message'] . "<br>" ;
			$text .=  'Сообщение:  '. $this->request->post['message']  . "\n\n" ;
		}
		if(isset($this->request->post['form'])){
			$html .=  '<strong>Форма: </strong> '. $this->request->post['form'] . "<br>" ;
			$text .=  'Форма:  '. $this->request->post['form']  . "\n\n" ;
		}


		if(isset($this->request->post['file_id']) && $this->request->post['file_id']){
			$this->load->model('themeset/themeset');
			$upload_info = '';
			$upload_info = $this->model_themeset_themeset->getUploadByCode($this->request->post['file_id']);
			if ($upload_info) {
				$html .=  '<strong>Файл: </strong> <a href="' . $this->url->link('themeset/themeset/download', 'code=' . $upload_info['code'], 'SSL') . '">'. $upload_info['name'] . "</a><br>" ;
				$text .=  'Файл: '. $this->url->link('themeset/themeset/download', 'code=' . $upload_info['code'], 'SSL') . "\n\n" ;

			}
		}

		$html .= '<br/><strong>Страница:</strong> ' . $_SERVER['HTTP_REFERER'].'<br>';
		$html .= '<br/><strong>Браузер:</strong> ' . $_SERVER['HTTP_USER_AGENT'].'<br>';
		$text .= 'Страница: ' . $_SERVER['HTTP_REFERER']  . "\n\n";
		$text .= 'Браузер: ' . $_SERVER['HTTP_USER_AGENT']  . "\n\n";

		$text .= "<br>" ;
		$mail->setText($text);
		$mail->setHtml($html);
		$mail->send();

		// Send to additional alert emails
		foreach ($emails as $email_item) {
			if ($email_item && preg_match($this->config->get('config_mail_regexp'), $email_item)) {
				$mail->setTo($email_item);
				$mail->send();
			}
		}

		$json['success'] = 'success';


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


}
