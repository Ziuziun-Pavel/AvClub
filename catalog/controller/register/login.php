<?php
class ControllerRegisterLogin extends Controller {

	private $b24_hook = 'https://avclub.bitrix24.ru/rest/669/0nvck395h18aqtai/';
	private $b24_contacts = 'https://avclub.bitrix24.ru/rest/669/2yt2mpuav23aqllx/';

	private $write_log = true;
	private $attention = true;
	private $attention_text = 'Личный кабинет работает в тестовом режиме. <br class="d-md-none">Приносим свои извинения за&nbsp;неудобства!';

	public function index() {

		unset($this->session->data['loguser_data']);

		$this->load->model('register/register');

		$expert_id = $this->visitor->getId();
		$contact_id = $this->visitor->getB24id();

		if($this->visitor->isLogged()) {

			header("Location: " . $this->url->link('register/account'));
			exit();


		}else{
			$this->visitor->logout();
		}

		$data = array();

		$data['session'] = session_id();

		$data['attention'] = $this->attention;
		$data['attention_text'] = $this->attention_text;

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('register/login', $data));

	}

	public function authorize() {

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

		if(!empty($this->session->data['loguser']['phone']) && $this->session->data['loguser']['phone'] === $phone && !empty($this->session->data['loguser_data']) ) {
			$exist = true;
		}else{
			unset($this->session->data['loguser_data']);
			unset($this->session->data['loguser']);
			unset($this->session->data['loguser_contact_id']);
		}

		$this->session->data['loguser']['phone'] = $phone;

		if(!$phone) {
			$return['error'] = 'Введите номер телефона';
			$error = true;
		}

		if(!$error) {

			$contact_id = !empty($this->session->data['loguser_contact_id']) ? $this->session->data['loguser_contact_id'] : $this->model_register_register->searchContactByPhone($this->session->data['loguser']['phone']);

			$this->session->data['loguser_contact_id'] = $contact_id;

			if($exist && !empty($this->session->data['loguser_data'])) {
				$user_data = $this->session->data['loguser_data'];
			}else{
				$contact_info = $this->model_register_register->getContactInfo($contact_id);

				if(!empty($contact_info['ID'])) {
					$user_data = array(
						'user_id'					=> $contact_info['ID'],
						'old_user_id'			=> $contact_info['ID'],
						'name'						=> $contact_info['NAME'],
						'lastname'				=> $contact_info['LAST_NAME'],
						'post'						=> $contact_info['POST'],
						'b24_company_id'	=> !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : 0,
						'b24_company_old_id'	=> !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : 0,
						'company'					=> '',
						'company_status'	=> !empty($contact_info['COMPANY_ID']) ? $contact_info['COMPANY_ID'] : '',
						'company_phone'		=> '',
						'company_site'		=> '',
						'company_activity'=> array(),
						'city'						=> '',
						'email'						=> '',
						'avatar'					=> $this->model_themeset_image->original('user_no_avatar.png'),
					);


					if(!empty($contact_info['EMAIL'])) {
						foreach($contact_info['EMAIL'] as $item) {
							$user_data['email'] = $item;
							break;
						}
					}

				}else{
					$user_data = array(
						'user_id'					=> 0,
						'old_user_id'			=> 0,
						'name'						=> '',
						'lastname'				=> '',
						'post'						=> '',
						'b24_company_id'	=> 0,
						'b24_company_old_id'	=> 0,
						'company'					=> '',
						'company_status'	=> '',
						'company_phone'		=> '',
						'company_site'		=> '',
						'company_activity'=> array(),
						'city'						=> '',
						'email'						=> '',
						'avatar'					=> $this->model_themeset_image->original('user_no_avatar.png'),
					);
				}
			}

			if(!empty($user_data['email'])) {
				$email = $user_data['email'];
			}else if(empty($email)){
				$return['error'] = 'Введите адрес электронной почты';
				$return['show_email'] = true;
				$error = true;
			}

			$user_data['email'] = !empty($email) ? $email : '';
			$this->session->data['loguser_data'] = $user_data;

		}


		if(!$error) {

			$request = $this->model_register_register->sendSMS($phone);
			/* $request = $this->model_register_register->sendCall($phone); */
			/* $request = $this->model_register_register->sendTest($phone); */

			if(!empty($request['error'])) {
				$return['error'] = $request['error'];
				$error = true;
			}else{
				$return['success'] = true;
				$code = $request['code'];
			}


		}

		if($error && $this->write_log) {
			$log = array(
				'step'						=>	'Ввод телефона -- ERROR',	
				'session'					=>	$sid,
				'browser'					=>	$_SERVER['HTTP_USER_AGENT'],
				'phone'						=>	$phone, 
				'email'						=>	$email, 
				'error'						=>	$return['error'], 
				'request'					=> !empty($request) ? $request : 'empty'
			);
			$this->model_register_register->log($log, 'login');
		}

		if(!$error && !empty($return['success'])) {
			$this->session->data['loguser']['hash'] = $this->model_register_register->hashCode($code);

			$data['info_text'] = '';
			if(!empty($email)) {
				$email_hide = $this->model_register_register->hideEmail($email);
				$data['info_text'] = 'Проверочный код отправлен на Ваш номер телефона и адрес электронной почты ' . $email_hide;
				$this->model_register_register->sendCodeToEmail($code, $email);

				$this->session->data['loguser']['email'] = $email;
			}

			$return['template'] = $this->load->view('register/code', $data);
		}else{
			unset($this->session->data['loguser']);
		}

		if(!$error && $this->write_log) {
			$log = array(
				'step'						=>	'Ввод телефона -- SUCCESS',	
				'session'					=>	$sid,
				'browser'					=>	$_SERVER['HTTP_USER_AGENT'],
				'phone'						=>	$phone, 
				'email'						=>	$email, 
				'code'						=>	$code, 
				'request'					=> !empty($request) ? $request : 'empty'
			);
			$this->model_register_register->log($log, 'login');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($return));
	}

	public function inputCode() {

		$this->load->model('register/register');
		$this->load->model('themeset/expert');
		$this->load->model('themeset/image');

		$return = array();
		$data = array();
		$error = false;

		$sid = $this->request->post['sid'];
		$data['session'] = $sid;
		$data['attention'] = $this->attention;
		$data['attention_text'] = $this->attention_text;

		$code = !empty($this->request->post['code']) ? $this->request->post['code'] : '';

		// нет хэша
		if(empty($this->session->data['loguser']['hash']) || empty($this->session->data['loguser']['phone']) || empty($this->session->data['loguser_data'])) {
			$error = true;
			$return['reload'] = true;
			$return['error'] = 'Сессия истекла';
		}

		// пустое поле кода
		if(empty($code)) {
			$error = true;
			$return['error'] = 'Введите проверочный код';
		}

		// код неверный
		if(!$error && (!empty($this->session->data['loguser']['hash']) && $this->session->data['hash']['hash'] === $this->model_register_register->hashCode($code))) {
			$error = true;
			$return['error'] = 'Неверный проверочный код';
		}

		if($error && $this->write_log) {
			$log = array(
				'step'						=> 'Проверочный код -- ERROR',	
				'session'					=>	$sid,
				'browser'					=>	$_SERVER['HTTP_USER_AGENT'], 
				'error'						=>	$return['error'], 
			);
			$this->model_register_register->log($log, 'login');
		}

		// все ок, ошибок нет
		if(!$error) {

			$user_data = $this->session->data['loguser_data'];

			if(!empty($user_data['user_id'])) {
				/* КОНТАКТ НАЙДЕН */
				$contact_id = $user_data['user_id'];
				$this->session->data['loguser']['b24id'] = $contact_id;

				// $this->model_themeset_expert->getContactInfo($contact_id, false, true);

				$expert_id = $this->model_register_register->getExpertId($contact_id);

				if($this->visitor->login($expert_id)) {
					$return['redirect'] = $this->url->link('register/account');
				}else{
					$return['error'] = 'Контакт не найден';
				}

				/* # КОНТАКТ НАЙДЕН */
			}else{
				/* НЕТ КОНТАКТА */

				$data['title'] = 'Заполните свои персональные данные для профайла резидента АВ&nbsp;Клуба';
				$data['user'] = $user_data;
				$data['phone'] = $this->session->data['loguser']['phone'];
				$data['show_name'] = false;
				$data['show_notme'] = false;
				$data['show_name_fields'] = true;

				$data['company_template'] = $this->load->view('register/_brand_main', array());

				$this->session->data['loguser']['user'] = $user_data;
				$return['template'] = $this->load->view('register/event_user_change', $data);
				/* # НЕТ КОНТАКТА */
			}

			if($this->write_log) {
				$log = array(
					'step'						=> 'Проверочный код -- SUCCESS',	
					'session'					=>	$sid,
					'browser'					=>	$_SERVER['HTTP_USER_AGENT'], 
					'contact_id'			=>	!empty($contact_id) ? $contact_id : 'NEW CONTACT', 
					'user_data'				=>	$user_data, 
				);
				$this->model_register_register->log($log, 'login');
			}

		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($return));
	}

	public function saveData() {

		$post = $this->request->post;

		$this->load->model('register/register');
		$this->load->model('themeset/expert');
		$this->load->model('themeset/image');

		$return = array();
		$data = array();
		$error = false;

		$sid = $this->request->post['sid'];
		$data['session'] = $sid;
		$data['attention'] = $this->attention;
		$data['attention_text'] = $this->attention_text;

		// нет хэша
		if(empty($this->session->data['loguser']['user']) ) {
			$error = true;
			$return['reload'] = true;
			$return['error'] = 'Сессия истекла';
		}

		if($error && $this->write_log) {
			$log = array(
				'step'						=> 'Сохранение данных -- ERROR',	
				'session'					=>	$sid,
				'browser'					=>	$_SERVER['HTTP_USER_AGENT'], 
				'error'						=>	$return['error'], 
			);
			$this->model_register_register->log($log, 'login');
		}

		// все ок, ошибок нет
		if(!$error) {

			$user_data = array(
				'user_id'					=> $this->session->data['loguser']['user']['user_id'],
				'name'						=> isset($post['name']) ? $post['name'] : $this->session->data['loguser']['user']['name'],
				'lastname'				=> isset($post['lastname']) ? $post['lastname'] : $this->session->data['loguser']['user']['lastname'],
				'phone'						=> $this->session->data['loguser']['phone'],
				'post'						=> isset($post['post']) ? $post['post'] : '',
				'b24_company_id'	=> isset($post['b24_company_id']) ? $post['b24_company_id'] : 0,
				'company'					=> isset($post['company']) ? $post['company'] : '',
				'company_status'	=> isset($post['company_status']) ? $post['company_status'] : '',
				'company_phone'		=> isset($post['company_phone']) ? $post['company_phone'] : '',
				'company_site'		=> isset($post['company_site']) ? $post['company_site'] : '',
				'company_activity'=> isset($post['company_activity']) ? $post['company_activity'] : array(),
				'city'						=> isset($post['city']) ? $post['city'] : '',
				'email'						=> isset($post['email']) ? $post['email'] : '',
				'avatar'					=> $this->session->data['loguser']['user']['avatar'],
			);

			$this->session->data['loguser']['user'] = $user_data;

			$new_contact_info = $this->model_register_register->createContact($user_data);
			$contact_id = !empty($new_contact_info['id']) ? $new_contact_info['id'] : 0;
			$this->session->data['loguser']['b24id'] = $contact_id;

			$return['contact_info'] = $new_contact_info;
			$return['contact_id'] = $contact_id;
			$return['user'] = $user_data;

			if(!empty($new_contact_info['code']) && $new_contact_info['code'] == 200) {
				$data['title'] = 'Ваша заявка на регистрацию успешно подана.';
			}else{
				$data['title'] = 'Произошла ошибка. Регистрация не завершена. <br>Попробуйте повторить попытку позже.';
			}

			$return['template'] = $this->load->view('register/login_success', $data);


			if($this->write_log) {
				$log = array(
					'step'						=> 'Сохранение данных -- SUCCESS',
					'session'					=>	$sid,
					'browser'					=>	$_SERVER['HTTP_USER_AGENT'], 	
					'user_data'				=>	$user_data, 
				);
				$this->model_register_register->log($log, 'login');
			}

		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($return));
	}

}
