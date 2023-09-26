<?php
class ControllerThemesetSetting extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('themeset/setting');

		$this->document->setTitle($this->language->get('heading_title'));
		
		

		$this->getForm();
	}

	protected function getForm() {

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('themeset', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}



		if($this->request->server['REQUEST_METHOD'] != 'POST'){
			$setting_info = $this->model_setting_setting->getSetting('themeset');
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

		$this->document->addScript('view/javascript/jquery-ui.sortable/jquery-ui.min.js');

		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');


		$data['heading_title'] = $this->language->get('heading_title');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['action'] = $this->url->link('themeset/setting', 'token=' . $this->session->data['token'], true);

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_image'] = $this->language->get('text_image');
		$data['text_preloader'] = $this->language->get('text_preloader');
		$data['text_general'] = $this->language->get('text_general');
		$data['text_standart'] = $this->language->get('text_standart');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_content'] = $this->language->get('text_content');
		$data['text_content_custom'] = $this->language->get('text_content_custom');
		$data['text_content_images'] = $this->language->get('text_content_images');
		$data['text_content_modal'] = $this->language->get('text_content_modal');
		$data['text_content_social'] = $this->language->get('text_content_social');
		$data['text_list'] = $this->language->get('text_list');
		
		$data['text_currency'] = $this->language->get('text_currency');
		$data['entry_currency_show'] = $this->language->get('entry_currency_show');
		$data['entry_currency_main'] = $this->language->get('entry_currency_main');
		$data['entry_currency_status'] = $this->language->get('entry_currency_status');
		$data['entry_currency_icon_1'] = $this->language->get('entry_currency_icon_1');
		$data['entry_currency_icon_2'] = $this->language->get('entry_currency_icon_2');
		$data['entry_currency_status'] = $this->language->get('entry_currency_status');
		$data['entry_on'] = $this->language->get('entry_on');
		$data['entry_off'] = $this->language->get('entry_off');
		
		$data['entry_directory'] = $this->language->get('entry_directory');
		$data['entry_status'] = $this->language->get('entry_status');		
		$data['entry_product_limit'] = $this->language->get('entry_product_limit');
		$data['entry_product_description_length'] = $this->language->get('entry_product_description_length');
		$data['entry_image_category'] = $this->language->get('entry_image_category');
		$data['entry_image_catalog'] = $this->language->get('entry_image_catalog');
		$data['entry_image_scheme'] = $this->language->get('entry_image_scheme');
		$data['entry_image_thumb'] = $this->language->get('entry_image_thumb');
		$data['entry_image_popup'] = $this->language->get('entry_image_popup');
		$data['entry_image_product'] = $this->language->get('entry_image_product');
		$data['entry_image_additional'] = $this->language->get('entry_image_additional');
		$data['entry_image_related'] = $this->language->get('entry_image_related');
		$data['entry_image_compare'] = $this->language->get('entry_image_compare');
		$data['entry_image_wishlist'] = $this->language->get('entry_image_wishlist');
		$data['entry_image_cart'] = $this->language->get('entry_image_cart');
		$data['entry_image_location'] = $this->language->get('entry_image_location');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_preloader'] = $this->language->get('entry_preloader');

		$data['entry_site_title'] = $this->language->get('entry_site_title');
		$data['entry_site_slogan'] = $this->language->get('entry_site_slogan');
		$data['entry_site_email'] = $this->language->get('entry_site_email');
		$data['entry_site_timetable'] = $this->language->get('entry_site_timetable');
		$data['entry_site_phone'] = $this->language->get('entry_site_phone');
		$data['entry_site_address'] = $this->language->get('entry_site_address');
		$data['entry_site_copy'] = $this->language->get('entry_site_copy');
		$data['entry_site_personal'] = $this->language->get('entry_site_personal');
		$data['entry_site_agree'] = $this->language->get('entry_site_agree');
		$data['entry_site_twitter'] = $this->language->get('entry_site_twitter');
		$data['entry_site_facebook'] = $this->language->get('entry_site_facebook');
		$data['entry_site_googleplus'] = $this->language->get('entry_site_googleplus');
		$data['entry_site_logo'] = $this->language->get('entry_site_logo');
		$data['entry_site_logo_footer'] = $this->language->get('entry_site_logo_footer');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_href'] = $this->language->get('entry_href');
		$data['entry_add'] = $this->language->get('entry_add');


		$data['text_page'] = $this->language->get('text_page');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_landing'] = $this->language->get('text_landing');
		$data['text_specials'] = $this->language->get('text_specials');
		$data['text_shop'] = $this->language->get('text_shop');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_all'] = $this->language->get('text_all');
		$data['entry_contact_map'] = $this->language->get('entry_contact_map');
		$data['entry_contact_title'] = $this->language->get('entry_contact_title');
		$data['entry_contact_timetitle'] = $this->language->get('entry_contact_timetitle');
		$data['entry_contact_warning'] = $this->language->get('entry_contact_warning');
		$data['entry_contact_content'] = $this->language->get('entry_contact_content');
		$data['entry_contact_banner'] = $this->language->get('entry_contact_banner');
		$data['entry_contact_scheme'] = $this->language->get('entry_contact_scheme');

		$data['entry_landing_title'] = $this->language->get('entry_landing_title');
		$data['entry_shop_title'] = $this->language->get('entry_shop_title');
		$data['entry_shop_description'] = $this->language->get('entry_shop_description');
		$data['entry_meta_h1'] = $this->language->get('entry_meta_h1');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');





		
		$data['help_product_limit'] = $this->language->get('help_product_limit');
		$data['help_product_description_length'] = $this->language->get('help_product_description_length');
		$data['help_directory'] = $this->language->get('help_directory');
		$data['help_currency'] = $this->language->get('help_currency');


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['image_scheme'])) {
			$data['error_image_scheme'] = $this->error['image_scheme'];
		} else {
			$data['error_image_scheme'] = '';
		}

		if (isset($this->error['image_catalog'])) {
			$data['error_image_catalog'] = $this->error['image_catalog'];
		} else {
			$data['error_image_catalog'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('themeset/setting', 'token=' . $this->session->data['token'], true)
			);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] , true);


		$key_list = array(
			'themeset_site_personal'	=> '',
			'themeset_site_agree'	=> '',

			'themeset_mess_viber'	=> '',
			'themeset_mess_telegram'	=> '',
			'themeset_mess_whatsapp'	=> '',
			'themeset_mess_skype'	=> '',

			'themeset_soc_tg'	=> '',
			'themeset_soc_fb'	=> '',
			'themeset_soc_tw'	=> '',
			'themeset_soc_vk'	=> '',
			'themeset_soc_od'	=> '',
			'themeset_soc_insta'	=> '',
			'themeset_soc_you'	=> '',

			'themeset_phone'	=> '',
			'themeset_email'	=> '',
			'themeset_copy'	=> '',
			'themeset_logo_text'	=> '',

			'themeset_uni_key'			=> '',
			'themeset_uni_status'		=> 0,
			'themeset_uni_account'	=> 0,
			'themeset_uni_main'			=> 0,

			'themeset_bitrix_webhook'	=> '',
			'themeset_bitrix_status'	=> 0,
			'themeset_bitrix_assigned'	=> '',
			'themeset_bitrix_file'	=> '',
			'themeset_bitrix_company'	=> '',
			'themeset_bitrix_company_text'	=> '',

			'themeset_bitrix_url'	=> '',
			'themeset_bitrix_companies_list'	=> '',
			'themeset_bitrix_company_fields'	=> '',
			'themeset_bitrix_company_info'	=> '',
			'themeset_bitrix_company_reseller'	=> 0,
			'themeset_bitrix_company_investor'	=> 0,
			'themeset_bitrix_company_refresh_token'	=> '',
			'themeset_bitrix_company_client_id'	=> '',
			'themeset_bitrix_company_client_secret'	=> '',



			'themeset_contact_content'	=> '',
			'themeset_contact_meta'			=> array(
					'title'				=>	'',
					'meta_title'				=>	'',
					'meta_h1'						=>	'',
					'meta_description'	=>	'',
					'meta_keyword'			=>	''
					),
			'themeset_specials'			=> array(
					'title'				=>	'',
					'meta_title'				=>	'',
					'meta_h1'						=>	'',
					'meta_description'	=>	'',
					'meta_keyword'			=>	''
					),
			'themeset_manufacturer'			=> array(
					'title'				=>	'',
					'meta_title'				=>	'',
					'meta_h1'						=>	'',
					'meta_description'	=>	'',
					'meta_keyword'			=>	''
					),


			'themeset_menu_top'	=> '',
			'themeset_menu_footer'	=> '',

			'themeset_adv'	=> array(
				'title'	=> 'Рекламодателям',
				'href'	=> ''
			)
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (isset($setting_info[$key])) {
				$data[$key] = $setting_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		// companies data
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$store_url = HTTPS_CATALOG;
		} else {
			$store_url = HTTP_CATALOG;
		}

		$data['company_b24'] = array(
			'url'	=> $store_url . 'index.php?route=themeset/companies',
			'install'	=> $store_url . 'index.php?route=themeset/companies/install',
			'refresh'	=> $store_url . 'index.php?route=themeset/companies/refreshToken',
			'redirect_uri'	=> urlencode($store_url . 'index.php?route=themeset/companies'),
		);

		// tags

		$this->load->model('tag/tag');
		$data['themeset_tags'] = array();
		if (isset($this->request->post['themeset_tags'])) {
			$tags = $this->request->post['themeset_tags'];
		} elseif (isset($setting_info['themeset_tags'])) {
			$tags = $setting_info['themeset_tags'];
		} else {
			$tags = array();
		}

		if($tags) {
			foreach($tags as $tag_id) {
				$tag_info = $this->model_tag_tag->getTagById($tag_id);
				if($tag_info) {
					$data['themeset_tags'][] = array(
						'tag_id'	=> $tag_info['tag_id'],
						'tag'			=> $tag_info['title']
					);
				}
			}
		}


		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();

		$this->load->model('themeset/menu');
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


		$this->load->model('tool/image');

		if (!empty($data['themeset_brand_pc']) && is_file(DIR_IMAGE . $data['themeset_brand_pc'])) {
			$data['thumb_brand_pc'] = $this->model_tool_image->resize($data['themeset_brand_pc'], 100, 100);
		} else {
			$data['thumb_brand_pc'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (!empty($data['themeset_brand_mob']) && is_file(DIR_IMAGE . $data['themeset_brand_mob'])) {
			$data['thumb_brand_mob'] = $this->model_tool_image->resize($data['themeset_brand_mob'], 100, 100);
		} else {
			$data['thumb_brand_mob'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);



		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('themeset/setting', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'themeset/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}


}
