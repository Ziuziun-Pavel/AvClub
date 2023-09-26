<?php
class ControllerAvbannerBanner extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('avbanner/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('avbanner/banner');

		$this->getList();
	}


	public function main() {
		$this->load->language('avbanner/banner');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateMain()) {
			$this->model_setting_setting->editSetting('avmain_banner', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('avmain_banner');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}		

		$heading_title = $this->language->get('heading_title_main');

		$data['heading_title'] = $heading_title;


		$data['action'] = $this->url->link('avbanner/banner/main', 'token=' . $this->session->data['token'], true);


		$error_list = array(
			'warning'		=> '',
			'title'			=> '',
			'link'	=> '',
			'image'	=> '',
		);

		foreach($error_list as $key=>$default) {
			if (isset($this->error[$key])) {
				$data['error_' . $key] = $this->error[$key];
			} else {
				$data['error_' . $key] = $default;
			}
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('avbanner/banner/banner', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);

		$lang_list = array(
			'entry_link',
			'entry_title',
			'entry_target',
			'entry_image',
			'button_save',
			'button_cancel',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$key_list = array(
			'avmain_banner_image'			=> '',
			'avmain_banner_link'			=> '',
			'avmain_banner_title'			=> '',
			'avmain_banner_target'		=> 0
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($setting_info)) {
				$data[$key] = $setting_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['avmain_banner_image']) && is_file(DIR_IMAGE . $this->request->post['avmain_banner_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['avmain_banner_image'], 100, 100);
		} elseif (!empty($setting_info) && is_file(DIR_IMAGE . $setting_info['avmain_banner_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($setting_info['avmain_banner_image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('avbanner/banner_main', $data));
	}

	public function branding() {
		$this->load->language('avbanner/banner');

		$this->document->setTitle($this->language->get('heading_title_branding'));

		$this->load->model('avbanner/banner');

		$this->getList('branding');
	}

	public function stretch() {
		$this->load->language('avbanner/banner');

		$this->document->setTitle($this->language->get('heading_title_stretch'));

		$this->load->model('avbanner/banner');

		$this->getList('stretch');
	}

	public function content() {
		$this->load->language('avbanner/banner');

		$this->document->setTitle($this->language->get('heading_title_content'));

		$this->load->model('avbanner/banner');

		$this->getList('content');
	}

	public function add() {
		$this->load->language('avbanner/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('avbanner/banner');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_avbanner_banner->addBanner($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$type = !empty($this->request->post['type']) ? $this->request->post['type'] : 'content';

			$this->response->redirect($this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('avbanner/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('avbanner/banner');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_avbanner_banner->editBanner($this->request->get['banner_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$type = !empty($this->request->post['type']) ? $this->request->post['type'] : 'content';

			$this->response->redirect($this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('avbanner/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('avbanner/banner');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $banner_id) {
				$this->model_avbanner_banner->deleteBanner($banner_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$type = !empty($this->request->get['type']) ? $this->request->get['type'] : 'content';

			$this->response->redirect($this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList($type);
	}

	public function copy() {
		$this->load->language('avbanner/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('avbanner/banner');

		if (isset($this->request->get['type'])) {
				$type = $this->request->get['type'];
			}else{
				$type = 'content';
			}

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $banner_id) {
				$this->model_avbanner_banner->copyBanner($banner_id);
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

			if (isset($this->request->get['type'])) {
				$url .= '&type=' . $this->request->get['type'];
			}

			$this->response->redirect($this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList($type);
	}

	protected function getList($type = '') {
		if($type) {
			$heading_title = $this->language->get('heading_title_' . $type);
		}else{
			$heading_title = $this->language->get('heading_title');
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'b.date_start';
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

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
			'href' => $this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . $url, true)
		);

		if($type) {
			$url .= '&type=' . $type;
		}

		$data['add'] = $this->url->link('avbanner/banner/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['copy'] = $this->url->link('avbanner/banner/copy', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('avbanner/banner/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['banners'] = array();

		$filter_data = array(
			'sort'  						=> $sort,
			'order' 						=> $order,
			'filter_type' 			=> $type,
			'filter_name' 			=> $filter_name,
			'start' 						=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 						=> $this->config->get('config_limit_admin')
		);


		$banner_total = $this->model_avbanner_banner->getTotalBanners($filter_data);


		$results = $this->model_avbanner_banner->getBanners($filter_data);

		foreach ($results as $result) {
			$date_start = strtotime($result['date_start']);
			$date_stop = strtotime($result['date_stop']);
			$class = '';

			if($date_start > time()) {
				$class = 'warning';
			}else if($date_stop < time()) {
				$class = 'danger';
			}else{
				$class = 'success';
			}

			$data['banners'][] = array(
				'banner_id' 			=> $result['banner_id'],
				'name'          	=> $result['name'],
				'date_start'			=> $result['date_start'],
				'date_stop'				=> $result['date_stop'],
				'count_viewed'		=> $result['count_viewed'],
				'count_click'			=> $result['count_click'],
				'class'						=> $class,
				'future'   	   		=> ($date_start > time() && $date_stop > time()) ? true : false,
				'finished'   	    => $date_stop < time() ? true : false,
				'status'     			=> $result['status'],
				'edit'           	=> $this->url->link('avbanner/banner/edit', 'token=' . $this->session->data['token'] . '&banner_id=' . $result['banner_id'] . $url, true)
			);
		}

		

		$data['heading_title'] = $heading_title;

		$lang_list = array(

			'text_list',
			'text_no_results',
			'text_confirm',
			'text_enabled',
			'text_disabled',
			'text_yes',
			'text_no',

			'column_date_start',
			'column_date_stop',
			'column_name',
			'column_status',
			'column_sort_order',
			'column_action',
			'column_viewed',
			'column_click',

			'button_add',
			'button_edit',
			'button_view',
			'button_preview',
			'button_add',
			'button_delete',
			'button_filter',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}


		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . '&sort=b.name' . $url, true);
		$data['sort_date_start'] = $this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . '&sort=b.date_start' . $url, 'SSL');
		$data['sort_date_stop'] = $this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . '&sort=b.date_stop' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $banner_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($banner_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($banner_total - $this->config->get('config_limit_admin'))) ? $banner_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $banner_total, ceil($banner_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['type'] = $type;
		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('avbanner/banner_list', $data));
	}

	protected function getForm() {

		$type = !empty($this->request->get['type']) ? $this->request->get['type'] : 'content';

		$banner_id = !empty($this->request->get['banner_id']) ? $this->request->get['banner_id'] : 0;

   
		if($type) {
			$heading_title = $this->language->get('heading_title_' . $type);
		}else{
			$heading_title = $this->language->get('heading_title');
		}
		$data['heading_title'] = $heading_title;

		$data['text_form'] = !isset($this->request->get['banner_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$lang_list = array(
			'text_default',
			'text_enabled',
			'text_disabled',
			'text_yes',
			'text_no',
			
			'entry_name',
			'entry_image_pc',
			'entry_image_mob',
			'entry_date_start',
			'entry_date_stop',
			'entry_link',
			'entry_target',
			'entry_status',
			
			'button_save',
			'button_cancel',
			'button_add'
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$error_list = array(
			'warning'			=> '',
			'name'				=> '',
			'link'				=> '',
			'date_start'	=> '',
			'date_stop'		=> '',
		);

		foreach($error_list as $key=>$default) {
			if (isset($this->error[$key])) {
				$data['error_' . $key] = $this->error[$key];
			} else {
				$data['error_' . $key] = $default;
			}
		}


		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$url .= '&type=' . $type; 

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['banner_id'])) {
			$data['action'] = $this->url->link('avbanner/banner/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('avbanner/banner/edit', 'token=' . $this->session->data['token'] . '&banner_id=' . $this->request->get['banner_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('avbanner/banner/' . $type, 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['banner_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$banner_info = $this->model_avbanner_banner->getBanner($this->request->get['banner_id']);
			$type = $banner_info['type'];
		}

		$data['type'] = $type;


		$key_list = array(
			'name'				=> '',
			'status'			=> 1,
			'image_pc'		=> '',
			'image_mob'		=> 1,
			'link'				=> '',
			'target'			=> 0
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (isset($banner_info[$key])) {
				$data[$key] = $banner_info[$key];
			} else {
				$data[$key] = $default;
			}
		}


		$this->load->model('tool/image');

		if (isset($this->request->post['image_pc']) && is_file(DIR_IMAGE . $this->request->post['image_pc'])) {
			$data['thumb_pc'] = $this->model_tool_image->resize($this->request->post['image_pc'], 100, 100);
		} elseif (!empty($banner_info) && is_file(DIR_IMAGE . $banner_info['image_pc'])) {
			$data['thumb_pc'] = $this->model_tool_image->resize($banner_info['image_pc'], 100, 100);
		} else {
			$data['thumb_pc'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['image_mob']) && is_file(DIR_IMAGE . $this->request->post['image_mob'])) {
			$data['thumb_mob'] = $this->model_tool_image->resize($this->request->post['image_mob'], 100, 100);
		} elseif (!empty($banner_info) && is_file(DIR_IMAGE . $banner_info['image_mob'])) {
			$data['thumb_mob'] = $this->model_tool_image->resize($banner_info['image_mob'], 100, 100);
		} else {
			$data['thumb_mob'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['date_start'])) {
			$data['date_start'] = $this->request->post['date_start'];
		} elseif (!empty($banner_info)) {
			$data['date_start'] = ($banner_info['date_start'] != '0000-00-00') ? $banner_info['date_start'] : '';
		} else {
			$data['date_start'] = date('Y-m-d H:i:s');
		}


		if (isset($this->request->post['date_stop'])) {
			$data['date_stop'] = $this->request->post['date_stop'];
		} elseif (!empty($banner_info)) {
			$data['date_stop'] = ($banner_info['date_stop'] != '0000-00-00') ? $banner_info['date_stop'] : '';
		} else {
			$data['date_stop'] = date('Y-m-d H:i:s');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('avbanner/banner_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'avbanner/banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (utf8_strlen($this->request->post['name']) < 1) {
			$this->error['name'] = $this->language->get('error_name');
		}

		$date_start = strtotime($this->request->post['date_start']);
		$date_stop = strtotime($this->request->post['date_stop']);

		if(!$date_start) {
			$this->error['date_start'] = $this->language->get('error_date');
		}

		if(!$date_stop || $date_stop <= $date_start) {
			$this->error['date_stop'] = $this->language->get('error_date');
		}



		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateMain() {
		if (!$this->user->hasPermission('modify', 'avbanner/banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (utf8_strlen($this->request->post['avmain_banner_title']) < 1) {
			$this->error['title'] = $this->language->get('error_title');
		}
		if (utf8_strlen($this->request->post['avmain_banner_link']) < 1) {
			$this->error['link'] = $this->language->get('error_link');
		}
		if (utf8_strlen($this->request->post['avmain_banner_image']) < 1) {
			$this->error['image'] = $this->language->get('error_image');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'avbanner/banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'avbanner/banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}


}