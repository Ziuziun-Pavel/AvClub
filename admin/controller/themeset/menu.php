<?php
class ControllerThemesetMenu extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('themeset/menu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('themeset/menu');

		$this->getList();
	}

	public function add() {
		$this->load->language('themeset/menu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('themeset/menu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_themeset_menu->addMenu($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';


			$this->response->redirect($this->url->link('themeset/menu', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('themeset/menu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('themeset/menu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_themeset_menu->editMenu($this->request->get['menu_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('themeset/menu', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}


	public function delete() {
		$this->load->language('themeset/menu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('themeset/menu');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $menu_id) {
				$this->model_themeset_menu->deleteMenu($menu_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('themeset/menu', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('themeset/menu', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('themeset/menu/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('themeset/menu/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['menus'] = array();

		$filter_data = array(
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$menu_total = $this->model_themeset_menu->getTotalMenus();

		$results = $this->model_themeset_menu->getMenus($filter_data);

		foreach ($results as $result) {
			$data['menus'][] = array(
				'menu_id' => $result['menu_id'],
				'name'               => $result['name'],
				'edit'               => $this->url->link('themeset/menu/edit', 'token=' . $this->session->data['token'] . '&menu_id=' . $result['menu_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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



		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('themeset/menu', 'token=' . $this->session->data['token'] . '&sort=agd.name' . $url, true);
		$data['sort_sort_order'] = $this->url->link('themeset/menu', 'token=' . $this->session->data['token'] . '&sort=ag.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $menu_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('themeset/menu', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($menu_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($menu_total - $this->config->get('config_limit_admin'))) ? $menu_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $menu_total, ceil($menu_total / $this->config->get('config_limit_admin')));

		// $data['sort'] = $sort;
		// $data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('themeset/menu_list', $data));
	}


	protected function getForm() {

		$data['heading_title'] = $this->language->get('heading_title');

		$data['token'] = $this->session->data['token'];

		$data['text_form'] = !isset($this->request->get['attribute_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['action'] = $this->url->link('themeset/menu', 'token=' . $this->session->data['token'], true);

		$data['text_category'] = $this->language->get('text_category');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_information'] = $this->language->get('text_information');
		$data['text_standart'] = $this->language->get('text_standart');
		$data['text_href'] = $this->language->get('text_href');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_href'] = $this->language->get('entry_href');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_label'] = $this->language->get('entry_label');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_color'] = $this->language->get('entry_color');

		$data['help_title'] = $this->language->get('help_title');


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('themeset/menu', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['menu_id'])) {
			$data['action'] = $this->url->link('themeset/menu/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('themeset/menu/edit', 'token=' . $this->session->data['token'] . '&menu_id=' . $this->request->get['menu_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);

		if (isset($this->request->get['menu_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$menu_info = $this->model_themeset_menu->getMenu($this->request->get['menu_id']);
		}


		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (isset($menu_info['name'])) {
			$data['name'] = $menu_info['name'];
		} else {
			$data['name'] = '';
		}	
		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (isset($menu_info['title'])) {
			$data['title'] = $menu_info['title'];
		} else {
			$data['title'] = '';
		}	

		if (isset($this->request->post['links'])) {
			$links = $this->request->post['links'];
		} elseif (isset($menu_info['links'])) {
			$links = $menu_info['links'];
		} else {
			$links = array();
		}	



		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['links'] = array();

		if($links) {
			foreach($links as $key=>$link) {

				$data['links'][$key] = $link;

				if($link['image'] && is_file(DIR_IMAGE . $link['image'])) {
					$thumb =$this->model_tool_image->resize($link['image'], 100, 100);
				}else{
					$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
				}

				$data['links'][$key]['thumb'] = $thumb;

			}
		}


		$this->load->model('newsblog/article');
		
		$article_total = $this->model_newsblog_article->getTotalArticles(array());
		$filter_data = array(
			'start'	=> 0,
			'limit'	=> $article_total
		);
		$results = $this->model_newsblog_article->getArticles($filter_data);
		$data['blog_articles'] = array();

		foreach($results as $result) {
			$data['blog_articles'][$result['article_id']] = $result['name'];
		}

		$this->load->model('newsblog/category');

		$filter_data = array(
			'sort'        => 'name',
			'order'       => 'ASC'
		);

		$data['blog_categories'] = $this->model_newsblog_category->getCategories($filter_data);


		$this->load->model('catalog/category');
		$filter_data = array(
			'sort'        => 'name',
			'order'       => 'ASC'
		);
		$data['categories'] = $this->model_catalog_category->getCategories($filter_data);

		$this->load->model('catalog/manufacturer');
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

		
		$this->load->model('catalog/information');
		$information_total = $this->model_catalog_information->getTotalInformations();
		$filter_data = array(
			'sort'  => 'id.title',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $information_total
		);
		$data['informations'] = $this->model_catalog_information->getInformations($filter_data);


		$data['standart'] = array(
			array(
				'value'	=>	'common/home',
				'name'	=>	'Главная'
			),
			array(
				'value'	=>	'product/special',
				'name'	=>	'Акции'
			),
			array(
				'value'	=>	'product/manufacturer',
				'name'	=>	'Производители'
			),
			array(
				'value'	=>	'information/contact',
				'name'	=>	'Контакты'
			),
			array(
				'value'	=>	'information/sitemap',
				'name'	=>	'Карта сайта'
			),
			array(
				'value'	=>	'product/compare',
				'name'	=>	'Сравнение'
			)
		);


		$data['menus'] = array();
		$total_menu = $this->model_themeset_menu->getTotalMenus();

		$filter_data = array(
			'start' => 0,
			'limit' => $total_menu
		);

		$results = $this->model_themeset_menu->getMenus($filter_data);

		foreach ($results as $result) {
			$data['menus'][] = array(
				'menu_id' => $result['menu_id'],
				'name'    => $result['name']
			);
		}






		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('themeset/menu_form', $data));
	}


	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'themeset/menu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'themeset/menu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		return !$this->error;
	}


}
