<?php
class ControllerThemesetThemeset extends Controller {

	
	private $error = array();

	public function getUniList() {
		
		$apikey = $this->config->get('themeset_uni_key');

		if($apikey) {
			require_once(DIR_APPLICATION . 'controller/themeset/UnisenderApi.php');
			$platform = $this->config->get('config_name');

			$uni = new \Unisender\ApiWrapper\UnisenderApi($apikey, 'UTF-8', 4, null, false, $platform);

			$result = $uni->getLists();
			$answer = json_decode($result, true);

			echo '<pre>';
			print_r($answer);
			echo '</pre>';
		}
		
	}


	public function send() {

		$title = 'Новая заявка';

		$email_subject = 	$title . ' - ' . $this->config->get('config_name');


		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($email_subject);

		$text = '';
		$html = '';

		$lead = array(
			'title'		=> 'Новая заявка',
			'name'		=> '',
			'email'		=> '',
			'phone'		=> '',
			'company'	=> '',
			'message'	=> '',
			'company_text'	=> '',
			'file'		=> '',
		);

		if(isset($this->request->post['name'])){
			$html .=  '<strong>Имя: </strong> '. $this->request->post['name'] . "<br>" ;
			$text .=  'Имя:  '. $this->request->post['name']  . "\n\n" ;
			$lead['name']	= $this->request->post['name'];
		}

		if(isset($this->request->post['company'])){
			$html .=  '<strong>Компания: </strong> '. $this->request->post['company'] . "<br>" ;
			$text .=  'Компания:  '. $this->request->post['company']  . "\n\n" ;
			$lead['company']	= $this->request->post['company'];
		}
		if(isset($this->request->post['phone'])){
			$html .=  '<strong>Телефон: </strong> '. $this->request->post['phone'] . "<br>" ;
			$text .=  'Телефон:  '. $this->request->post['phone']  . "\n\n" ;
			$lead['phone']	= $this->request->post['phone'];
		}
		if(isset($this->request->post['email'])){
			$html .=  '<strong>E-mail: </strong> '. $this->request->post['email'] . "<br>" ;
			$text .=  'E-mail:  '. $this->request->post['email']  . "\n\n" ;
			$lead['email']	= $this->request->post['email'];
		}
		if(isset($this->request->post['web'])){
			$html .=  '<strong>Сайт: </strong> '. $this->request->post['web'] . "<br>" ;
			$text .=  'Сайт:  '. $this->request->post['web']  . "\n\n" ;
			$lead['web']	= $this->request->post['web'];
		}
		if(isset($this->request->post['message'])){
			$html .=  '<strong>Сообщение: </strong> '. $this->request->post['message'] . "<br>" ;
			$text .=  'Сообщение:  '. $this->request->post['message']  . "\n\n" ;
			$lead['message']	= $this->request->post['message'];
		}
		if(isset($this->request->post['company_text'])){
			$html .=  '<strong>Краткое описание компании: </strong> '. $this->request->post['company_text'] . "<br>" ;
			$text .=  'Краткое описание компании:  '. $this->request->post['company_text']  . "\n\n" ;
			$lead['company_text']	= $this->request->post['company_text'];
		}
		if(isset($this->request->post['form'])){
			$html .=  '<strong>Форма: </strong> '. $this->request->post['form'] . "<br>" ;
			$text .=  'Форма:  '. $this->request->post['form']  . "\n\n" ;
			$lead['title'] = $this->request->post['form'];
		}


		if(isset($this->request->post['file_id']) && $this->request->post['file_id']){
			$this->load->model('themeset/themeset');
			$upload_info = '';
			$upload_info = $this->model_themeset_themeset->getUploadByCode($this->request->post['file_id']);
			if ($upload_info) {
				$html .=  '<strong>Файл: </strong> <a href="' . $this->url->link('themeset/themeset/download', 'code=' . $upload_info['code'], 'SSL') . '">'. $upload_info['name'] . "</a><br>" ;
				$text .=  'Файл: '. $this->url->link('themeset/themeset/download', 'code=' . $upload_info['code'], 'SSL') . "\n\n" ;

				$lead['file']	= [
					"fileData" => [
						basename($upload_info['name']),
						base64_encode(file_get_contents(DIR_UPLOAD . $upload_info['filename']))
					]
				];

			}
		}

		$html .= '<br/><strong>Страница:</strong> ' . $_SERVER['HTTP_REFERER'].'<br>';
		$text .= 'Страница: ' . $_SERVER['HTTP_REFERER']  . "\n\n";

		$text .= "<br>" ;
		$mail->setText($text);
		$mail->setHtml($html);
		$mail->send();

		// Send to additional alert emails
		$emails = explode(',', $this->config->get('config_alert_email'));

		foreach ($emails as $email_item) {
			if ($email_item && preg_match($this->config->get('config_mail_regexp'), $email_item)) {
				$mail->setTo($email_item);
				$mail->send();
			}
		}

		$bitrixWebHook = $this->config->get('themeset_bitrix_webhook');
		$bitrixStatus = $this->config->get('themeset_bitrix_status');

		if($bitrixStatus && $bitrixWebHook) {

			$bitrixCustomFile = $this->config->get('themeset_bitrix_file');
			$bitrixCustomCompany = $this->config->get('themeset_bitrix_company');
			$bitrixCompanyText = $this->config->get('themeset_bitrix_company_text');
			$bitrixAssigned = $this->config->get('themeset_bitrix_assigned');

			$fields = [
				'TITLE' 							=> $lead['title'] . ' - ' . $this->config->get('config_name'),
				'NAME' 								=> trim(htmlspecialchars(stripcslashes($lead['name']))),
				'COMMENTS' 						=> nl2br($lead['message']),
				
				'PHONE' 							=> array(array('VALUE' => trim(htmlspecialchars(stripcslashes($lead['phone']))), 'VALUE_TYPE' => 'WORK')),
				'EMAIL' 							=> array(array('VALUE' => $lead['email'], 'VALUE_TYPE' => 'WORK')),
			];
			
			if($lead['web']) {
				$fields['WEB'] = array(array('VALUE' => $lead['web'], 'VALUE_TYPE' => 'WORK'));
			}
			if($bitrixAssigned) { $fields['ASSIGNED_BY_ID'] = $bitrixAssigned;	}
			if($lead['company']) { $fields['COMPANY_TITLE'] = $lead['company'];	}
			if($lead['company_text'] && $bitrixCompanyText) { $fields[$bitrixCompanyText] = $lead['company_text'];	}
			if($lead['file'] && $bitrixCustomFile) { 	$fields[$bitrixCustomFile] =  $lead['file'];	}


			require_once(DIR_SYSTEM . 'library/crest/crest.php');
			CRest::call('crm.lead.add',	[	'fields' => $fields ]);
		}





		$json['success'] = 'success';


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function letter() {

		$email = !empty($this->request->post['email']) ? $this->request->post['email'] : '';

		if(!$email) {
			$json['error'] = 'E-mail не указан';
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		// unisender
		$apikey = $this->config->get('themeset_uni_key');
		$list_id = $this->config->get('themeset_uni_main'); 
		if(!empty($this->request->post['type']) && $this->request->post['type'] === 'account') {
			$list_id = $this->config->get('themeset_uni_account'); 
		}

		if($this->config->get('themeset_uni_status') && $apikey && $list_id) {
			require_once(DIR_APPLICATION . 'controller/themeset/UnisenderApi.php');
			$platform = $this->config->get('config_name');

			$uni = new \Unisender\ApiWrapper\UnisenderApi($apikey, 'UTF-8', 4, null, false, $platform);

			$exist = $uni->subscribe(array(
				'list_ids'=>$list_id,
				'fields'=>array(
					'email'=>$email
				)
			));

			$answer = json_decode($exist, true);

		}
		// # unisender

		$title = 'Новый подписчик';

		$email_subject = 	$title . ' - ' . $this->config->get('config_name');


		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($email_subject);

		$text = '';
		$html = '';

		if(isset($this->request->post['email'])){
			$html .=  '<strong>E-mail: </strong> '. $this->request->post['email'] . "<br>" ;
			$text .=  'E-mail:  '. $this->request->post['email']  . "\n\n" ;
		}

		$html .= '<br/><strong>Страница:</strong> ' . $_SERVER['HTTP_REFERER'].'<br>';
		$text .= 'Страница: ' . $_SERVER['HTTP_REFERER']  . "\n\n";

		$text .= "<br>" ;
		$mail->setText($text);
		$mail->setHtml($html);
		$mail->send();

		// Send to additional alert emails
		$emails = explode(',', $this->config->get('config_alert_email'));

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


	public function updateBannerView(){
		$banner_id = $this->request->post['banner_id'];
		if($banner_id){
			$this->load->model('themeset/themeset');
			$this->model_themeset_themeset->updateBannerView($banner_id);
		}
	}
	public function updateBannerClick(){
		$banner_id = $this->request->post['banner_id'];
		if($banner_id){
			$this->load->model('themeset/themeset');
			$this->model_themeset_themeset->updateBannerClick($banner_id);
		}
	}

	public function addNewsletter(){
		$this->load->model('themeset/themeset');

		$this->createNewsletterTables();

		$data['email'] = $this->request->post['email'];

		if(!$this->model_themeset_themeset->hasNewsletter($data['email'])){

			$this->model_themeset_themeset->addNewsletter($data);
			$json['success'] = 'Вы успешно подписались!';

		}else{
			$json['error'] = 'Вы уже подписаны на рассылку';
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function rus2translit($string) {
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',
			'г' => 'g',   'д' => 'd',   'е' => 'e',
			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
			'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',
			'о' => 'o',   'п' => 'p',   'р' => 'r',
			'с' => 's',   'т' => 't',   'у' => 'u',
			'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

			'А' => 'A',   'Б' => 'B',   'В' => 'V',
			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
			'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			'О' => 'O',   'П' => 'P',   'Р' => 'R',
			'С' => 'S',   'Т' => 'T',   'У' => 'U',
			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);
		$text = strtolower(strtr($string, $converter));
		$disallow_symbols = array(
			' ' => '-', 
			'	' => '-', 
			'\\' => '-', 
			'/' => '-', 
			':' => '-', 
			'*' => '',
			'?' => '', 
			',' => '', 
			'"' => '', 
			'\'' => '', 
			'<' => '', 
			'>' => '', 
			'|' => '',
			'«'=>'', 
			'»'=>'', 
			'„'=>'', 
			'“'=>'', 
			'“'=>'', 
			'”'=>'',
			'–'=>''
		);
		return trim(strip_tags(str_replace(array_keys($disallow_symbols), array_values($disallow_symbols), trim(html_entity_decode($text, ENT_QUOTES, 'UTF-8')))), '-');
	}

	public function upload() {
		$this->load->language('tool/upload');

		$json = array();

		if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
			// Sanitize the filename

			$translit = $this->rus2translit($this->request->files['file']['name']);

			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($translit, ENT_QUOTES, 'UTF-8')));

			// Validate the filename length
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			$allowed = array();

			$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

			$filetypes = explode("\n", $extension_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Allowed file mime types
			$allowed = array();

			$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

			$filetypes = explode("\n", $mime_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Check to see if any PHP files are trying to be uploaded
			$content = file_get_contents($this->request->files['file']['tmp_name']);

			if (preg_match('/\<\?php/i', $content)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Return any upload error
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = 'Ошибка загрузки файла';
		}

		if (!$json) {
			$file = $filename . '.' . token(32);

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

			// Hide the uploaded file name so people can not link to it directly.
			$this->load->model('tool/upload');

			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);
			$json['filename'] = $filename;
			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function download() {
		$this->load->model('themeset/themeset');

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = 0;
		}

		if (isset($this->request->get['file_id'])) {
			$file_id = $this->request->get['file_id'];
		} else {
			$file_id = 0;
		}

		if (isset($this->request->get['download_id'])) {
			$download_id = $this->request->get['download_id'];
		} else {
			$download_id = 0;
		}

		$upload_info = '';

		if($code) {
			$upload_info = $this->model_themeset_themeset->getUploadByCode($code);
		}else if($file_id) {
			$upload_info = $this->model_themeset_themeset->getDownloadById($file_id);
		}else if($download_id) {
			$upload_info = $this->model_themeset_themeset->getDownloadById($download_id);
		}


		if ($upload_info) {
			if($code) {
				$file = DIR_UPLOAD . $upload_info['filename'];
				$mask = basename($upload_info['name']);
			}else if($file_id) {
				$file = DIR_DOWNLOAD . $upload_info['filename'];
				$mask = basename($upload_info['mask']);
			}else if($download_id) {
				$file = DIR_DOWNLOAD . $upload_info['filename'];
				$mask = basename($upload_info['mask']);
			}

			if (!headers_sent()) {
				if (is_file($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Description: File Transfer');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					readfile($file, 'rb');
					exit;
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_not_found'] = $this->language->get('text_not_found');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], true)
			);

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}



}
