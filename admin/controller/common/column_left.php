<?php
class ControllerCommonColumnLeft extends Controller {
	public function index() {
		if (isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->load->language('common/column_left');
			
			$this->load->model('user/user');
			
			$this->load->model('tool/image');
			
			$user_info = $this->model_user_user->getUser($this->user->getId());
			
			if ($user_info) {
				$data['firstname'] = $user_info['firstname'];
				$data['lastname'] = $user_info['lastname'];
				$data['username']  = $user_info['username'];
				$data['user_group'] = $user_info['user_group'];
				
				if (is_file(DIR_IMAGE . $user_info['image'])) {
					$data['image'] = $this->model_tool_image->resize($user_info['image'], 45, 45);
				} else {
					$data['image'] = '';
				}
			} else {
				$data['firstname'] = '';
				$data['lastname'] = '';
				$data['username'] = '';
				$data['user_group'] = '';
				$data['image'] = '';
			}			
			
			// Create a 3 level menu array
			// Level 2 can not have children
			
			// Menu
			$data['menus'][] = array(
				'id'       => 'menu-dashboard',
				'icon'	   => 'fa-dashboard',
				'name'	   => $this->language->get('text_dashboard'),
				'href'     => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
				'children' => array()
			);

			// journal
			$journal = array();

			$journal_list = array(
				array('path'=>'journal/journal', 'link'=>'news', 			'title'=>'Новости'),
				array('path'=>'journal/journal', 'link'=>'opinion', 	'title'=>'Мнения'),
				array('path'=>'journal/journal', 'link'=>'case', 			'title'=>'Кейсы'),
				array('path'=>'journal/journal', 'link'=>'article', 		'title'=>'Статьи'),
				array('path'=>'journal/journal', 'link'=>'video', 			'title'=>'Видео'),
				array('path'=>'journal/journal', 'link'=>'special', 		'title'=>'Спецпроекты')
			);

			foreach($journal_list as $item) {
				if ($this->user->hasPermission('access', $item['path'])) {		
					$journal[] = array(
						'name'	   => $item['title'],
						'href'     => $this->url->link($item['path'] . ($item['link'] ? '/' . $item['link'] : ''), 'token=' . $this->session->data['token'], true),
						'children' => array()		
					);					
				}
			}

			/*if ($this->user->hasPermission('access', 'journal/setting')) {
				$journal[] = array(
					'name'	   => 'Баннер',
					'href'     => $this->url->link('journal/setting/banner', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);		
			}*/

			/*if ($journal) {
				$data['menus'][] = array(
					'id'       => 'menu-journal',
					'icon'	   => 'fa-leanpub', 
					'name'	   => 'Журнал',
					'href'     => '',
					'children' => $journal
				);		
			}*/
			
			// Catalog
			$catalog = array();

			if ($journal) {		
				$catalog[] = array(
					'name'	   => 'Журнал',
					'href'     => '',
					'children' => $journal		
				);					
			}

			// journal
			$banners = array();

			$banners_list = array(
				array('path'=>'avbanner/banner', 'link'=>'main',			'title'=>'Главный экран'),
				array('path'=>'avbanner/banner', 'link'=>'branding', 	'title'=>'Брендирование'),
				array('path'=>'avbanner/banner', 'link'=>'stretch',		'title'=>'Растяжка'),
				array('path'=>'avbanner/banner', 'link'=>'content', 	'title'=>'Баннер в правой колонке'),
			);

			foreach($banners_list as $item) {
				if ($this->user->hasPermission('access', $item['path'])) {		
					$banners[] = array(
						'name'	   => $item['title'],
						'href'     => $this->url->link($item['path'] . ($item['link'] ? '/' . $item['link'] : ''), 'token=' . $this->session->data['token'], true),
						'children' => array()		
					);					
				}
			}

			if ($this->user->hasPermission('access', 'banners/setting')) {
				$banners[] = array(
					'name'	   => 'Баннер',
					'href'     => $this->url->link('banners/setting/banner', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);		
			}

			if ($banners) {		
				$catalog[] = array(
					'name'	   => 'Баннеры',
					'href'     => '',
					'children' => $banners		
				);					
			}
			
			/*if ($this->user->hasPermission('access', 'catalog/category')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_category'),
					'href'     => $this->url->link('catalog/category', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/product')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_product'),
					'href'     => $this->url->link('catalog/product', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/recurring')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_recurring'),
					'href'     => $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/filter')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_filter'),
					'href'     => $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			// Attributes
			$attribute = array();
			
			if ($this->user->hasPermission('access', 'catalog/attribute')) {
				$attribute[] = array(
					'name'     => $this->language->get('text_attribute'),
					'href'     => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/attribute_group')) {
				$attribute[] = array(
					'name'	   => $this->language->get('text_attribute_group'),
					'href'     => $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($attribute) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_attribute'),
					'href'     => '',
					'children' => $attribute
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/option')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_option'),
					'href'     => $this->url->link('catalog/option', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/manufacturer')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_manufacturer'),
					'href'     => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/download')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_download'),
					'href'     => $this->url->link('catalog/download', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/review')) {		
				$catalog[] = array(
					'name'	   => $this->language->get('text_review'),
					'href'     => $this->url->link('catalog/review', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);		
			}
			
			*/

			if ($this->user->hasPermission('access', 'tag/tag')) {	
				$catalog[] = array(
					'id'       => 'menu-tag',
					'icon'	   => 'fa-tags', 
					'name'	   => 'Теги',
					'href'     => $this->url->link('tag/tag', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);						
			}

			if ($this->user->hasPermission('access', 'master/master')) {	
				$catalog[] = array(
					'id'       => 'menu-master',
					'icon'	   => 'fa-magic', 
					'name'	   => 'Онлайн-события',
					'href'     => $this->url->link('master/master', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);						
			}

			// events
			$events = array();

			if ($this->user->hasPermission('access', 'avevent/event')) {
				$events[] = array(
					'name'	   => 'Мероприятия',
					'href'     => $this->url->link('avevent/event', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

			if ($this->user->hasPermission('access', 'avevent/type')) {
				$events[] = array(
					'name'	   => 'Категории',
					'href'     => $this->url->link('avevent/type', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

			if ($this->user->hasPermission('access', 'avevent/city')) {
				$events[] = array(
					'name'	   => 'Города',
					'href'     => $this->url->link('avevent/city', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

			if($events) {
				$catalog[] = array(
					'id'       => 'menu-master',
					'icon'	   => 'fa-magic', 
					'name'	   => 'Мероприятия',
					'href'     => '',
					'children' => $events
				);	
			}

			// present
			if ($this->user->hasPermission('access', 'present/present')) {	
				$catalog[] = array(
					'id'       => 'menu-present',
					'icon'	   => 'fa-user', 
					'name'	   => 'Анонсы',
					'href'     => $this->url->link('present/present', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);						
			}

			// companies
			$companies = array();

			if ($this->user->hasPermission('access', 'company/company')) {
				$companies[] = array(
					'name'	   => 'Компании',
					'href'     => $this->url->link('company/company', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

			if ($this->user->hasPermission('access', 'company/category')) {
				$companies[] = array(
					'name'	   => 'Категории',
					'href'     => $this->url->link('company/category', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

			if ($this->user->hasPermission('access', 'company/brand')) {
				$companies[] = array(
					'name'	   => 'Бренды',
					'href'     => $this->url->link('company/brand', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

			if ($this->user->hasPermission('access', 'company/branch')) {
				$companies[] = array(
					'name'	   => 'Отрасли',
					'href'     => $this->url->link('company/branch', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

			if($companies) {
				$catalog[] = array(
					'id'       => 'menu-master',
					'icon'	   => 'fa-magic', 
					'name'	   => 'Компании',
					'href'     => '',
					'children' => $companies
				);	
			}


			if ($this->user->hasPermission('access', 'visitor/visitor')) {	
				$catalog[] = array(
					'id'       => 'menu-author',
					'icon'	   => 'fa-user', 
					'name'	   => 'Авторы / Читатели',
					'href'     => $this->url->link('visitor/visitor', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);						
			}



			if ($catalog) {
				$data['menus'][] = array(
					'id'       => 'menu-catalog',
					'icon'	   => 'fa-tags', 
					'name'	   => $this->language->get('text_catalog'),
					'href'     => '',
					'children' => $catalog
				);		
			}


			if ($this->user->hasPermission('access', 'catalog/information')) {	
				$data['menus'][] = array(
					'id'       => 'menu-information',
					'icon'	   => 'fa-info', 
					'name'	   => 'Информация',
					'href'     => $this->url->link('catalog/information', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);						
			}

			// AV THEME
			$themeset_child = array();
			if ($this->user->hasPermission('access', 'themeset/setting')) {	
				$themeset_child[] = array(
					'name'	   => 'Настройки',
					'href'     => $this->url->link('themeset/setting', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'themeset/menu')) {	
				$themeset_child[] = array(
					'name'	   => 'Меню',
					'href'     => $this->url->link('themeset/menu', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'themeset/size')) {	
				$themeset_child[] = array(
					'name'	   => 'Размеры',
					'href'     => $this->url->link('themeset/size', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'themeset/limit')) {	
				$themeset_child[] = array(
					'name'	   => 'Лимиты вывода',
					'href'     => $this->url->link('themeset/limit', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'themeset/import')) {	
				$themeset_child[] = array(
					'name'	   => 'Импорт',
					'href'     => $this->url->link('themeset/import', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

			if ($this->user->hasPermission('access', 'octeam_tools/seo_manager')) {	
				$themeset_child[] = array(
					'name'	   => 'SEO URL',
					'href'     => $this->url->link('octeam_tools/seo_manager', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if($themeset_child){	
				$data['menus'][] = array(
					'id'       => 'menu-catalog',
					'icon'	   => 'fa-gears', 
					'name'	   => 'Настройки AV Club',
					'href'     => '',
					'children' => $themeset_child
				);	
			}
			// # AV THEME
			
			
			// Extension
			$extension = array();
			/*
			if ($this->user->hasPermission('access', 'extension/store')) {		
				$extension[] = array(
					'name'	   => $this->language->get('text_store'),
					'href'     => $this->url->link('extension/store', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);					
			}
			*/
			if ($this->user->hasPermission('access', 'extension/installer')) {		
				$extension[] = array(
					'name'	   => $this->language->get('text_installer'),
					'href'     => $this->url->link('extension/installer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);					
			}	
			
			if ($this->user->hasPermission('access', 'extension/extension')) {		
				$extension[] = array(
					'name'	   => $this->language->get('text_extension'),
					'href'     => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'extension/modification')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_modification'),
					'href'     => $this->url->link('extension/modification', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'extension/event')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_event'),
					'href'     => $this->url->link('extension/event', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($extension) {					
				$data['menus'][] = array(
					'id'       => 'menu-extension',
					'icon'	   => 'fa-puzzle-piece', 
					'name'	   => $this->language->get('text_extension'),
					'href'     => '',
					'children' => $extension
				);		
			}
			
			// Design
			$design = array();
			
			if ($this->user->hasPermission('access', 'design/layout')) {
				$design[] = array(
					'name'	   => $this->language->get('text_layout'),
					'href'     => $this->url->link('design/layout', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			/*
			if ($this->user->hasPermission('access', 'design/menu')) {
				$design[] = array(
					'name'	   => $this->language->get('text_menu'),
					'href'     => $this->url->link('design/menu', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			*/	
			/*	
			if ($this->user->hasPermission('access', 'design/theme')) {	
				$design[] = array(
					'name'	   => $this->language->get('text_theme'),
					'href'     => $this->url->link('design/theme', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'design/language')) {
				$design[] = array(
					'name'	   => $this->language->get('text_translation'),
					'href'     => $this->url->link('design/language', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			*/	
			if ($this->user->hasPermission('access', 'design/banner')) {
				$design[] = array(
					'name'	   => $this->language->get('text_banner'),
					'href'     => $this->url->link('design/banner', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($design) {
				$data['menus'][] = array(
					'id'       => 'menu-design',
					'icon'	   => 'fa-television', 
					'name'	   => $this->language->get('text_design'),
					'href'     => '',
					'children' => $design
				);	
			}
			
			// Sales
			$sale = array();
			
			if ($this->user->hasPermission('access', 'sale/order')) {
				$sale[] = array(
					'name'	   => $this->language->get('text_order'),
					'href'     => $this->url->link('sale/order', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'sale/recurring')) {	
				$sale[] = array(
					'name'	   => $this->language->get('text_recurring'),
					'href'     => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'sale/return')) {
				$sale[] = array(
					'name'	   => $this->language->get('text_return'),
					'href'     => $this->url->link('sale/return', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			// Voucher
			$voucher = array();
			
			if ($this->user->hasPermission('access', 'sale/voucher')) {
				$voucher[] = array(
					'name'	   => $this->language->get('text_voucher'),
					'href'     => $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'sale/voucher_theme')) {
				$voucher[] = array(
					'name'	   => $this->language->get('text_voucher_theme'),
					'href'     => $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($voucher) {
				$sale[] = array(
					'name'	   => $this->language->get('text_voucher'),
					'href'     => '',
					'children' => $voucher		
				);		
			}
			
			if ($sale) {
				$data['menus'][] = array(
					'id'       => 'menu-sale',
					'icon'	   => 'fa-shopping-cart', 
					'name'	   => $this->language->get('text_sale'),
					'href'     => '',
					'children' => $sale
				);
			}
			
			// Customer
			$customer = array();
			
			if ($this->user->hasPermission('access', 'customer/customer')) {
				$customer[] = array(
					'name'	   => $this->language->get('text_customer'),
					'href'     => $this->url->link('customer/customer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'customer/customer_group')) {
				$customer[] = array(
					'name'	   => $this->language->get('text_customer_group'),
					'href'     => $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'customer/custom_field')) {		
				$customer[] = array(
					'name'	   => $this->language->get('text_custom_field'),
					'href'     => $this->url->link('customer/custom_field', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($customer) {
				$data['menus'][] = array(
					'id'       => 'menu-customer',
					'icon'	   => 'fa-user', 
					'name'	   => $this->language->get('text_customer'),
					'href'     => '',
					'children' => $customer
				);	
			}
			
			// Marketing
			$marketing = array();
			
			if ($this->user->hasPermission('access', 'marketing/marketing')) {
				$marketing[] = array(
					'name'	   => $this->language->get('text_marketing'),
					'href'     => $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'marketing/affiliate')) {
				$marketing[] = array(
					'name'	   => $this->language->get('text_affiliate'),
					'href'     => $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'marketing/coupon')) {	
				$marketing[] = array(
					'name'	   => $this->language->get('text_coupon'),
					'href'     => $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'marketing/contact')) {
				$marketing[] = array(
					'name'	   => $this->language->get('text_contact'),
					'href'     => $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($marketing) {
				$data['menus'][] = array(
					'id'       => 'menu-marketing',
					'icon'	   => 'fa-share-alt', 
					'name'	   => $this->language->get('text_marketing'),
					'href'     => '',
					'children' => $marketing
				);	
			}
			
			// System
			$system = array();
			
			if ($this->user->hasPermission('access', 'setting/setting')) {
				$system[] = array(
					'name'	   => $this->language->get('text_setting'),
					'href'     => $this->url->link('setting/store', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			// Users
			$user = array();
			
			if ($this->user->hasPermission('access', 'user/user')) {
				$user[] = array(
					'name'	   => $this->language->get('text_users'),
					'href'     => $this->url->link('user/user', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'user/user_permission')) {	
				$user[] = array(
					'name'	   => $this->language->get('text_user_group'),
					'href'     => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'user/api')) {		
				$user[] = array(
					'name'	   => $this->language->get('text_api'),
					'href'     => $this->url->link('user/api', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($user) {
				$system[] = array(
					'name'	   => $this->language->get('text_users'),
					'href'     => '',
					'children' => $user		
				);
			}
			
			// Localisation
			$localisation = array();
			
			if ($this->user->hasPermission('access', 'localisation/location')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_location'),
					'href'     => $this->url->link('localisation/location', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'localisation/language')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_language'),
					'href'     => $this->url->link('localisation/language', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/currency')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_currency'),
					'href'     => $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/stock_status')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_stock_status'),
					'href'     => $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/order_status')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_order_status'),
					'href'     => $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			// Returns
			$return = array();
			
			if ($this->user->hasPermission('access', 'localisation/return_status')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_status'),
					'href'     => $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/return_action')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_action'),
					'href'     => $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);		
			}
			
			if ($this->user->hasPermission('access', 'localisation/return_reason')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_reason'),
					'href'     => $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($return) {	
				$localisation[] = array(
					'name'	   => $this->language->get('text_return'),
					'href'     => '',
					'children' => $return		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/country')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_country'),
					'href'     => $this->url->link('localisation/country', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/zone')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_zone'),
					'href'     => $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/geo_zone')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_geo_zone'),
					'href'     => $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			// Tax		
			$tax = array();
			
			if ($this->user->hasPermission('access', 'localisation/tax_class')) {
				$tax[] = array(
					'name'	   => $this->language->get('text_tax_class'),
					'href'     => $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/tax_rate')) {
				$tax[] = array(
					'name'	   => $this->language->get('text_tax_rate'),
					'href'     => $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($tax) {	
				$localisation[] = array(
					'name'	   => $this->language->get('text_tax'),
					'href'     => '',
					'children' => $tax		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/length_class')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_length_class'),
					'href'     => $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/weight_class')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_weight_class'),
					'href'     => $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($localisation) {																
				$system[] = array(
					'name'	   => $this->language->get('text_localisation'),
					'href'     => '',
					'children' => $localisation	
				);
			}
			
			// Tools	
			$tool = array();
			
			if ($this->user->hasPermission('access', 'tool/upload')) {
				$tool[] = array(
					'name'	   => $this->language->get('text_upload'),
					'href'     => $this->url->link('tool/upload', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'tool/backup')) {
				$tool[] = array(
					'name'	   => $this->language->get('text_backup'),
					'href'     => $this->url->link('tool/backup', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'tool/log')) {
				$tool[] = array(
					'name'	   => $this->language->get('text_log'),
					'href'     => $this->url->link('tool/log', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

			/* octeam */
			if ($this->user->hasPermission('access', 'octeam/toolset')) {
				$tool[] = array(
					'name'	   => $this->language->get('text_octeam_toolset'),
					'href'     => $this->url->link('octeam/toolset', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

			if ($tool) {
				$system[] = array(
					'name'	   => $this->language->get('text_tools'),
					'href'     => '',
					'children' => $tool	
				);
			}
			
			if ($system) {
				$data['menus'][] = array(
					'id'       => 'menu-system',
					'icon'	   => 'fa-cog', 
					'name'	   => $this->language->get('text_system'),
					'href'     => '',
					'children' => $system
				);
			}
			
			// Report
			$report = array();
			
			// Report Sales
			$report_sale = array();	
			
			if ($this->user->hasPermission('access', 'report/sale_order')) {
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_order'),
					'href'     => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/sale_tax')) {
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_tax'),
					'href'     => $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($this->user->hasPermission('access', 'report/sale_shipping')) {
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_shipping'),
					'href'     => $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($this->user->hasPermission('access', 'report/sale_return')) {	
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_return'),
					'href'     => $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);	
			}
			
			if ($this->user->hasPermission('access', 'report/sale_coupon')) {		
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_coupon'),
					'href'     => $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($report_sale) {
				$report[] = array(
					'name'	   => $this->language->get('text_report_sale'),
					'href'     => '',
					'children' => $report_sale
				);			
			}
			
			// Report Products			
			$report_product = array();	
			
			if ($this->user->hasPermission('access', 'report/product_viewed')) {
				$report_product[] = array(
					'name'	   => $this->language->get('text_report_product_viewed'),
					'href'     => $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($this->user->hasPermission('access', 'report/product_purchased')) {
				$report_product[] = array(
					'name'	   => $this->language->get('text_report_product_purchased'),
					'href'     => $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($report_product) {	
				$report[] = array(
					'name'	   => $this->language->get('text_report_product'),
					'href'     => '',
					'children' => $report_product	
				);		
			}
			
			// Report Customers				
			$report_customer = array();
			
			if ($this->user->hasPermission('access', 'report/customer_online')) {	
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_online'),
					'href'     => $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/customer_activity')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_activity'),
					'href'     => $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/customer_search')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_search'),
					'href'     => $this->url->link('report/customer_search', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/customer_order')) {	
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_order'),
					'href'     => $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/customer_reward')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_reward'),
					'href'     => $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/customer_credit')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_credit'),
					'href'     => $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($report_customer) {	
				$report[] = array(
					'name'	   => $this->language->get('text_report_customer'),
					'href'     => '',
					'children' => $report_customer	
				);
			}
			
			// Report Marketing			
			$report_marketing = array();			
			
			if ($this->user->hasPermission('access', 'report/marketing')) {
				$report_marketing[] = array(
					'name'	   => $this->language->get('text_report_marketing'),
					'href'     => $this->url->link('report/marketing', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/affiliate')) {
				$report_marketing[] = array(
					'name'	   => $this->language->get('text_report_affiliate'),
					'href'     => $this->url->link('report/affiliate', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);		
			}
			
			if ($this->user->hasPermission('access', 'report/affiliate_activity')) {
				$report_marketing[] = array(
					'name'	   => $this->language->get('text_report_affiliate_activity'),
					'href'     => $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);		
			}
			
			if ($report_marketing) {	
				$report[] = array(
					'name'	   => $this->language->get('text_report_marketing'),
					'href'     => '',
					'children' => $report_marketing	
				);		
			}
			
			if ($report) {	
				$data['menus'][] = array(
					'id'       => 'menu-report',
					'icon'	   => 'fa-bar-chart-o', 
					'name'	   => $this->language->get('text_reports'),
					'href'     => '',
					'children' => $report
				);	
			}		
			
			// Stats
			$data['text_complete_status'] = $this->language->get('text_complete_status');
			$data['text_processing_status'] = $this->language->get('text_processing_status');
			$data['text_other_status'] = $this->language->get('text_other_status');
			
			$this->load->model('sale/order');
			
			$order_total = $this->model_sale_order->getTotalOrders();
			
			$complete_total = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_complete_status'))));
			
			if ($complete_total) {
				$data['complete_status'] = round(($complete_total / $order_total) * 100);
			} else {
				$data['complete_status'] = 0;
			}
			
			$processing_total = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_processing_status'))));
			
			if ($processing_total) {
				$data['processing_status'] = round(($processing_total / $order_total) * 100);
			} else {
				$data['processing_status'] = 0;
			}
			
			$this->load->model('localisation/order_status');
			
			$order_status_data = array();
			
			$results = $this->model_localisation_order_status->getOrderStatuses();
			
			foreach ($results as $result) {
				if (!in_array($result['order_status_id'], array_merge($this->config->get('config_complete_status'), $this->config->get('config_processing_status')))) {
					$order_status_data[] = $result['order_status_id'];
				}
			}
			
			$other_total = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $order_status_data)));
			
			if ($other_total) {
				$data['other_status'] = round(($other_total / $order_total) * 100);
			} else {
				$data['other_status'] = 0;
			}
			
			return $this->load->view('common/column_left', $data);
		}
	}
}