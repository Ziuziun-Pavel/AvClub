<?php
class ControllerAveventEvent extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('avevent/event');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('avevent/event');

		$this->getList();
	}

	public function add() {
		$this->load->language('avevent/event');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('avevent/event');
        $this->load->model('master/master');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $logData = [
                'datetime' => date('Y-m-d H:i:s'),
                'from' => 'admin',
                'action' => 'add',
                'request_data' => $this->request->post
            ];
            $this->model_master_master->log($logData, 'add_event');

			$this->model_avevent_event->addEvent($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->response->redirect($this->url->link('avevent/event', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('avevent/event');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('avevent/event');
        $this->load->model('master/master');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $logData = [
                'datetime' => date('Y-m-d H:i:s'),
                'from' => 'admin',
                'action' => 'edit',
                'event_id' => $this->request->get['event_id'],
                'request_data' => $this->request->post
            ];
            $this->model_master_master->log($logData, 'update_event');

			$this->model_avevent_event->editEvent($this->request->get['event_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('avevent/event', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('avevent/event');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('avevent/event');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $event_id) {
				$this->model_avevent_event->deleteEvent($event_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->response->redirect($this->url->link('avevent/event', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function setting() {
		
		$this->load->language('avevent/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_meta()) {
			$this->model_setting_setting->editSetting('av_meta_event', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('avevent/event/setting', 'token=' . $this->session->data['token'], true));
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('av_meta_event');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		//CKEditor
		if ($this->config->get('config_editor_default')) {
			$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
			$this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
		} else {
			$this->document->addScript('view/javascript/summernote/summernote.js');
			$this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
			$this->document->addScript('view/javascript/summernote/opencart.js');
			$this->document->addStyle('view/javascript/summernote/summernote.css');
		}

		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

		
		$heading_title =  $this->language->get('heading_title');

		$data['heading_title'] = $heading_title;

		$lang_list = array(
			'entry_title',
			'entry_description',
			'entry_bread',
			'entry_meta_h1',
			'entry_meta_title',
			'entry_meta_description',
			'entry_meta_keyword',
			'entry_link',
			'entry_button',

			'tab_meta',
			'tab_event',

			'button_save',
			'button_cancel',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$data['action'] = $this->url->link('avevent/event/setting', 'token=' . $this->session->data['token'], true);


		$error_list = array(
			'warning',
			'bread',
			'meta_h1',
		);
		foreach($error_list as $key) {
			if (isset($this->error[$key])) {
				$data['error_' . $key] = $this->error[$key];
			} else {
				$data['error_' . $key] = '';
			}
		}

		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('avevent/event/setting', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);


		/* META */
		$data['title'] = $heading_title;

		$data['meta'] = array();

		if (isset($this->request->post['av_meta_event'])) {
			$meta = $this->request->post['av_meta_event'];
		} elseif (isset($setting_info['av_meta_event'])) {
			$meta = $setting_info['av_meta_event'];
		} else {
			$meta = array(
				'event_title'				=>	'',
				'event_description'				=>	'',
				'event_button'				=>	'',
				'event_link'				=>	'',
				'description'				=>	'',
				'bread'							=>	'',
				'meta_title'				=>	'',
				'meta_h1'						=>	'',
				'meta_description'	=>	'',
				'meta_keyword'			=>	''
			);
		}

		$data['meta'] = $meta;
		/* # META */

		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('avevent/setting_meta', $data));
	}

	protected function getList() {

		$heading_title = $this->language->get('heading_title');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'e.date_available';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('avevent/event', 'token=' . $this->session->data['token'] . $url, true)
		);



		$data['setting'] = $this->url->link('avevent/event/setting', 'token=' . $this->session->data['token'] . $url, true);
		$data['add'] = $this->url->link('avevent/event/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('avevent/event/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['events'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);


		$event_total = $this->model_avevent_event->getTotalEvents($filter_data);


		$results = $this->model_avevent_event->getEvents($filter_data);

		foreach ($results as $result) {
//            var_dump($result['event_id']);

			$time = strtotime($result['date_available']);

			$data['events'][] = array(
				'event_id'					=> $result['event_id'],
				'title'          		=> $result['title'],
				'type'          		=> $result['type'],
				'city'          		=> $result['city'],
				'status'          	=> $result['status'],
				'finished'          => $time < time() ? true : false,
				'date_available'		=> date('Y-m-d H:i', $time),
				'view'           	=> HTTPS_CATALOG . 'index.php?route=avevent/event/info&event_id=' . $result['event_id'],
				'edit'          	 	=> $this->url->link('avevent/event/edit', 'token=' . $this->session->data['token'] . '&event_id=' . $result['event_id'] . $url, true)
			);

		}

		$lang_list = array(
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_enabled',
			'text_disabled',

			'column_date_available',
			'column_title',
			'column_type',
			'column_city',
			'column_status',
			'column_action',

			'button_add',
			'button_edit',
			'button_delete',
			'button_view',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}

		$data['heading_title'] = $heading_title;


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('avevent/event', 'token=' . $this->session->data['token'] . '&sort=ed.title' . $url, true);
		$data['sort_date_available'] = $this->url->link('avevent/event', 'token=' . $this->session->data['token'] . '&sort=e.date_available' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $event_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('avevent/event', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($event_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($event_total - $this->config->get('config_limit_admin'))) ? $event_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $event_total, ceil($event_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('avevent/event_list', $data));
	}

	protected function getForm() {

		
		// $this->document->addScript('view/javascript/jquery.sortable/jquery.sortable.min.js');
		$this->document->addScript('view/javascript/jquery-ui.sortable/jquery-ui.min.js');

    //CKEditor
		if ($this->config->get('config_editor_default')) {
			$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
			$this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
		} else {
			$this->document->addScript('view/javascript/summernote/summernote.js');
			$this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
			$this->document->addScript('view/javascript/summernote/opencart.js');
			$this->document->addStyle('view/javascript/summernote/summernote.css');
		}

		$heading_title = $this->language->get('heading_title');
		$data['heading_title'] = $heading_title;

		$data['text_form'] = !isset($this->request->get['event_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$lang_list = array(
			'text_default',
			'text_enabled',
			'text_disabled',
			'text_none',
			'text_old_none',
			'text_old_link',
			'text_old_page',
			
			'entry_file',
			'entry_file_placeholder',
			'entry_image',
			'entry_image_full',
			'entry_video',
			'entry_video_image',
			'entry_title',
			'entry_description',
			'entry_preview',
			'entry_meta_title',
			'entry_meta_h1',
			'entry_meta_description',
			'entry_meta_keyword',
			'entry_keyword',
			'entry_store',
			'entry_show',
			'entry_status',
			'entry_layout',
			'entry_date',
			'entry_time',
			'entry_time_start',
			'entry_time_end',
			'entry_author',
			'entry_author_placeholder',
			'entry_type',
			'entry_city',
			'entry_price',
			'entry_count',
			'entry_address',
			'entry_address_full',
			'entry_coord',
			'entry_template',
			'entry_template_list',
			'entry_template_slider',
			'entry_template_image',
			'entry_template_image_disable',
			'entry_template_on',
			'entry_template_off',
			'entry_question',
			'entry_answer',
			'entry_sort_order',
			'entry_company',
			'entry_company_placeholder',
			'entry_present',
			'entry_present_placeholder',
			'entry_href',
			'entry_link',

			'entry_old',
			'entry_old_link',

			'confirm_remove',
			
			'tab_template',
			'tab_content',
			'tab_media',
			'tab_brand',
			'tab_prg',
			'tab_speaker',
			'tab_ask',
			'tab_plus',
			'tab_insta',
			'tab_present',

			'template_top',
			'template_video',
			'template_brand',
			'template_plus',
			'template_insta',
			'template_prg',
			'template_speaker',
			'template_present',
			'template_register',
			'template_ask',

			
			'help_sort',
			'help_show',
			'help_keyword',
			'help_coord',
			'help_old',
			
			'button_add',
			'button_remove',
			'button_save',
			'button_cancel',

			'tab_general',
			'tab_data',
			'tab_design',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$error_list = array(
			'warning',
			'title',
			'description',
			'meta_title',
			'keyword',
			'author',
			'link',
			'date_start',
			'date_stop',
		);
		foreach($error_list as $key) {
			if (isset($this->error[$key])) {
				$data['error_' . $key] = $this->error[$key];
			} else {
				$data['error_' . $key] = '';
			}
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('avevent/event', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['event_id'])) {
			$data['action'] = $this->url->link('avevent/event/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('avevent/event/edit', 'token=' . $this->session->data['token'] . '&event_id=' . $this->request->get['event_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('avevent/event', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['event_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$event_info = $this->model_avevent_event->getEvent($this->request->get['event_id']);
		}


		$this->load->model('tool/image');

		$this->load->model('avevent/type');
		$data['types'] = $this->model_avevent_type->getTypes();

		$this->load->model('avevent/city');
		$data['cities'] = $this->model_avevent_city->getCities();

		// template
		$template = array();
		if(isset($this->request->post['template'])) {
			$tpl = $this->request->post['template'];
			foreach($tpl as $key=>$item) {
				$template[$key] = array('status'=>$item);
			}
		}elseif(!empty($event_info)) {
			$template = $this->model_avevent_event->getTemplate($this->request->get['event_id']);
		}else{
			$template = array(
				'top'				=> array('status'=>1),
				'video'			=> array('status'=>0),
				'brand'			=> array('status'=>0),
				'plus'			=> array('status'=>0),
				'insta'			=> array('status'=>0),
				'prg'				=> array('status'=>0),
				'speaker'		=> array('status'=>0),
				'present'		=> array('status'=>0),
				'register'	=> array('status'=>0),
				'ask'				=> array('status'=>0),
			);
		}
		foreach($template as $key=>$item) {
			$template[$key]['title'] = $this->language->get('template_' . $key);
			$template[$key]['disabled'] = $key === 'top' ? true : false;
		}
		$data['template'] = $template;

		// company
		$data['companies'] = array();
		if (isset($this->request->post['company'])) {
			$companies = $this->request->post['company'];
			if($companies) {
				$this->load->model('company/company');
				foreach($companies as $company_id) {
					$company_info = $this->model_company_company->getCompany($company_id);
					if($company_info) {
						$data['companies'][] = array(
							'company_id'	=> $company_id,
							'title'				=> $company_info['title']
						);
					}
				}
			}
		}elseif (!empty($event_info)){
			$data['companies'] = $this->model_avevent_event->getCompaniesByEvent($this->request->get['event_id']);
		}

		// presents
		$data['presents'] = array();
		if (isset($this->request->post['present'])) {
			$presents = $this->request->post['present'];
			if($presents) {
				$this->load->model('present/present');
				foreach($presents as $present_id) {
					$present_info = $this->model_present_present->getPresent($present_id);
					if($present_info) {
						$data['presents'][] = array(
							'present_id'	=> $present_id,
							'title'				=> $present_info['title']
						);
					}
				}
			}
		}elseif (!empty($event_info)){
			$data['presents'] = $this->model_avevent_event->getPresentsByEvent($this->request->get['event_id']);
		}

		// authors
		$data['authors'] = array();
		if (isset($this->request->post['author'])) {
			$authors = $this->request->post['author'];
			if($authors) {
				$this->load->model('visitor/visitor');
				foreach($authors as $author_key=>$author_id) {
					$author_info = $this->model_visitor_visitor->getVisitor($author_id);
					if($author_info) {
						$data['authors'][] = array(
							'author_id'			=> $author_id,
							'name'					=> $author_info['name'],
							'author_exp'		=> !empty($this->request->post['author_exp'][$author_key]) ? $this->request->post['author_exp'][$author_key] : $author_info['author_exp'],
							'exp_list'			=> $this->model_visitor_visitor->getExpByVisitor($author_id)
						);
					}
				}
			}
		}elseif (!empty($event_info)){
			$data['authors'] = $this->model_avevent_event->getAuthorsByEvent($this->request->get['event_id']);
		}
		
		// ask
		$data['ask'] = array();
		if (isset($this->request->post['ask'])) {
			$data['ask'] = $this->request->post['ask'];
		}elseif (!empty($event_info)){
			$data['ask'] = $this->model_avevent_event->getAskByEvent($this->request->get['event_id']);
		}
		
		// plus
		$data['pluses'] = array();
		$plus = array();
		if (isset($this->request->post['plus'])) {
			$plus = $this->request->post['plus'];
		}elseif (!empty($event_info)){
			$plus = $this->model_avevent_event->getPlusByEvent($this->request->get['event_id']);
		}

		foreach($plus as $item) {
			if ($item['image'] && is_file(DIR_IMAGE . $item['image'])) {
				$thumb = $this->model_tool_image->resize($item['image'], 100, 100);
			} else {
				$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
			$item['thumb'] = $thumb;
			$data['pluses'][] = $item;
		}
		
		// insta
		$data['insta_list'] = array();
		$insta = array();
		if (isset($this->request->post['insta'])) {
			$insta = $this->request->post['insta'];
		}elseif (!empty($event_info)){
			$insta = $this->model_avevent_event->getInstaByEvent($this->request->get['event_id']);
		}

		foreach($insta as $item) {
			if ($item['image'] && is_file(DIR_IMAGE . $item['image'])) {
				$thumb = $this->model_tool_image->resize($item['image'], 100, 100);
			} else {
				$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
			$item['thumb'] = $thumb;
			$data['insta_list'][] = $item;
		}
		
		// prg
		$data['prg'] = array();
		$prg = array();
		if (isset($this->request->post['prg'])) {
			$prg = $this->request->post['prg'];
		}elseif (!empty($event_info)){
			$prg = $this->model_avevent_event->getPrgByEvent($this->request->get['event_id']);
		}

		foreach($prg as $item) {
			if ($item['image'] && is_file(DIR_IMAGE . $item['image'])) {
				$thumb = $this->model_tool_image->resize($item['image'], 100, 100);
			} else {
				$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
			$item['thumb'] = $thumb;
			$data['prg'][] = $item;
		}


		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['config_language_id'] = $this->config->get('config_language_id'); // SEO URL Generator

		$data['lang'] = $this->language->get('lang');

		if (isset($this->request->post['event_description'])) {
			$data['event_description'] = $this->request->post['event_description'];
		} elseif (isset($this->request->get['event_id'])) {
			$data['event_description'] = $this->model_avevent_event->getEventDescriptions($this->request->get['event_id']);
		} else {
			$data['event_description'] = array();
		}

		$key_list = array(
			'keyword'				=> '',
			'status'				=> true,
			'show_event'		=> true,
			'link'					=> '',
			'image'					=> '',
			'image_full'		=> '',
			'video'					=> '',
			'video_image'		=> '',
			'price'					=> '',
			'count'					=> '',
			'address'				=> '',
			'address_full'	=> '',
			'coord'					=> '',
			'type_id'					=> '',
			'city_id'					=> '',
			'old_type'					=> 'page',
			'old_link'					=> '',

			'present_title'		=> '',
			'brand_title'			=> 'Бренды участники',
			'brand_template'	=> 0,
			'insta_title'			=> '',
			'prg_title'				=> 'Что будет на мероприятии',
			'prg_template'		=> 0,
			'prg_file_id'			=> '',
			'speaker_title'		=> 'Вы сможете задать вопрос любому из спикеров',
			'ask_title'				=> 'Вопросы и ответы',
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($event_info)) {
				$data[$key] = $event_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		$data['prg_file'] = array();

		if($data['prg_file_id']) {
			$this->load->model('catalog/download');
			$file_info = $this->model_catalog_download->getDownload($data['prg_file_id']);
			if($file_info) {
				$data['prg_file'] = array(
					'download_id'	=> $file_info['download_id'],
					'name'				=> $file_info['name'],
				);
			}
		}


		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($event_info) && is_file(DIR_IMAGE . $event_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($event_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['image_full']) && is_file(DIR_IMAGE . $this->request->post['image_full'])) {
			$data['thumb_full'] = $this->model_tool_image->resize($this->request->post['image_full'], 100, 100);
		} elseif (!empty($event_info) && is_file(DIR_IMAGE . $event_info['image_full'])) {
			$data['thumb_full'] = $this->model_tool_image->resize($event_info['image_full'], 100, 100);
		} else {
			$data['thumb_full'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['video_image']) && is_file(DIR_IMAGE . $this->request->post['video_image'])) {
			$data['thumb_video'] = $this->model_tool_image->resize($this->request->post['video_image'], 100, 100);
		} elseif (!empty($event_info) && is_file(DIR_IMAGE . $event_info['video_image'])) {
			$data['thumb_video'] = $this->model_tool_image->resize($event_info['video_image'], 100, 100);
		} else {
			$data['thumb_video'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}



		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['date'])) {
			$data['date'] = $this->request->post['date'];
		} elseif (!empty($event_info)) {
			$data['date'] = ($event_info['date'] != '0000-00-00') ? $event_info['date'] : '';
		} else {
			$data['date'] = date('Y-m-d');
		}

		if (isset($this->request->post['date_stop'])) {
			$data['date_stop'] = $this->request->post['date_stop'];
		} elseif (!empty($event_info)) {
			$data['date_stop'] = ($event_info['date_stop'] != '0000-00-00') ? $event_info['date_stop'] : '';
		} else {
			$data['date_stop'] = date('Y-m-d');
		}

		$date_start = strtotime($data['date']);
		$date_stop = strtotime($data['date_stop']);

		if($date_stop < $date_start) {
			$data['date_stop'] = $data['date'];
		}

		if (isset($this->request->post['time_start'])) {
			$data['time_start'] = $this->request->post['time_start'];
		} elseif (!empty($event_info)) {
			$data['time_start'] = ($event_info['time_start'] != '0000-00-00') ? $event_info['time_start'] : '';
		} else {
			$data['time_start'] = date('H:i', strtotime('09:00'));
		}

		if (isset($this->request->post['time_end'])) {
			$data['time_end'] = $this->request->post['time_end'];
		} elseif (!empty($event_info)) {
			$data['time_end'] = ($event_info['time_end'] != '0000-00-00') ? $event_info['time_end'] : '';
		} else {
			$data['time_end'] = date('H:i', strtotime('12:00'));
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('avevent/event_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'avevent/event')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$date_start = strtotime($this->request->post['date']);
		$date_stop = strtotime($this->request->post['date_stop']);
		if(!$date_start) {
			$this->error['date_start'] = $this->language->get('error_date');
		}
		if(!$date_stop || $date_stop < $date_start) {
			$this->error['date_stop'] = $this->language->get('error_date');
		}

		foreach ($this->request->post['event_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

		}

		if (utf8_strlen($this->request->post['link']) < 3) {
			$this->error['link'] = $this->language->get('error_link');
		}

		if(empty($this->request->post['keyword'])){
			$a_data = array(
				'name'    => $this->request->post['event_description'][$this->config->get('config_language_id')]['title'],
				'essence' => 'event',
			);

			$this->request->post['keyword'] = $this->load->controller('extension/module/seo_url_generator/getSeoUrl', $a_data); 
		} else {
			$this->load->model('extension/module/seo_url_generator');
			$this->request->post['keyword'] = $this->model_extension_module_seo_url_generator->translit($this->request->post['keyword']);
		}
		// SEO URL Generator . end

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['event_id']) && $url_alias_info['query'] != 'event_id=' . $this->request->get['event_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['event_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'avevent/event')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validate_meta() {
		if (!$this->user->hasPermission('modify', 'avevent/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['av_meta_event']['bread'])) {
			$this->error['bread'] = $this->language->get('error_bread');
		}

		if (empty($this->request->post['av_meta_event']['meta_h1'])) {
			$this->error['meta_h1'] = $this->language->get('error_meta_h1');
		}

		return !$this->error;
	}

	public function existEvent() {
		$json = array();

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];

			$this->load->model('avevent/event');

			$filter_data = array(
				'filter_title'  => $filter_title,
				'start'        => 0,
				'limit'        => 5
			);

			$event_info = $this->model_avevent_event->getEventByName($filter_title);

			if($event_info) {
				$json['exist'] = true;
				$json['event'] = array(
					'event_id'	=> $event_info['event_id'],
					'event'			=> $event_info['date'] . ' / ' . $event_info['type'] . ' / ' . $event_info['city'] . ' / ' . $event_info['title'],
				);
			}else{
				$json['exist'] = false;
			}

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];

			$this->load->model('avevent/event');

			$filter_data = array(
				'filter_title'  => $filter_title,
				'start'        => 0,
				'limit'        => 10
			);

			$results = $this->model_avevent_event->getEvents($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'event_id'   => $result['event_id'],
					'name'         => $result['date'] . ' / ' . $result['type'] . ' / ' . $result['city'] . ' / ' . $result['title']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}