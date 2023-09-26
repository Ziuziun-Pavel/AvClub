<?php
class ModelThemesetThemeset extends Model {

	public function getUploadByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "upload WHERE code = '" . $this->db->escape($code) . "'");
		return $query->row;
	}

	public function addNewsletter($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET email = '" . $data['email'] . "', `group` = 'newsletter', date_added = NOW()");
	}

	public function hasNewsletter($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter WHERE email = '" . $email . "' ");
		if($query->num_rows > 0){
			return true;
		}else{
			return false;
		}
	}


	public function getMenuData($menu_id = 0, $title = true) {
		$menu_data = array();
		$links_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "thememenu WHERE menu_id = '" . (int)$menu_id . "'");

		$query_links = $this->db->query("SELECT * FROM " . DB_PREFIX . "thememenu_values WHERE menu_id = '" . (int)$menu_id . "' ORDER BY sort_order, title ASC");

		if($query_links->rows) {
			foreach($query_links->rows as $link) {

				if(!$link['status']){continue;}

				$links_data[] = $this->getLinkData($link);
			}
			
		}

		if($query->rows) {
			$menu_data = array(
				'name'	=> $query->row['name'],
				'title'	=> $query->row['title'],
				'links'	=> $links_data
			);
		}


		return $title ? $menu_data : $links_data;
		// return $menu_data;
	}


	private function getLinkData($data = array()) {
		$menu_data = array();

		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/information');
		$this->load->model('catalog/information');
		$this->load->model('newsblog/category');
		$this->load->model('newsblog/article');
		$this->load->model('tool/image');


		$route = isset($this->request->get['route']) ? $this->request->get['route'] : '';
		$product_id = isset($this->request->get['product_id']) ? $this->request->get['product_id'] : 0;
		if(isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = (int)array_pop($parts);
		}else{
			$parts = array();
			$category_id = 0;
		}
		if(isset($this->request->get['newsblog_path'])) {
			$news_parts = explode('_', (string)$this->request->get['newsblog_path']);
			$news_category_id = (int)array_pop($news_parts);
		}else{
			$news_parts = array();
			$news_category_id = 0;
		}
		$article_id = isset($this->request->get['newsblog_article_id']) ? $this->request->get['newsblog_article_id'] : 0;
		$manufacturer_id = isset($this->request->get['manufacturer_id']) ? $this->request->get['manufacturer_id'] : 0;
		$information_id = isset($this->request->get['information_id']) ? $this->request->get['information_id'] : 0;



		$sort = array();


		$menu_title = '';
		$menu_href = '';
		$menu_children = array();
		$active = false;
		$active_parent = false;



		$flag = true;
		switch($data['type']) {
			case 'category' : 
			if($data['category_id'] == $category_id) {
				$active = true;
			}else if(in_array($data['category_id'], $parts)) {
				$active_parent = true;
			}
			$category_info = $this->model_catalog_category->getCategory($data['category_id']);
			if ($category_info) {
				$menu_title = $category_info['name'];
				$menu_href = $this->url->link('product/category', 'path=' . $data['category_id']);
			}else{
				$flag = false;
			}
			break;

			case 'category_with_children' : 
			case 'category_type' : 
			if($data['category_id'] == $category_id) {
				$active = true;
			}else if(in_array($data['category_id'], $parts)) {
				$active_parent = true;
			}
			$category_info = $this->model_catalog_category->getCategory($data['category_id']);
			if ($category_info) {
				$menu_title = $category_info['name'];
				$menu_href = $this->url->link('product/category', 'path=' . $data['category_id']);
			}else{
				$flag = false;
			}
			$categories = $this->model_catalog_category->getCategories($data['category_id']);
			if($categories) {
				foreach($categories as $category) {
					$menu_children[] = array(
						'title'	=> $category['name'],
						'href'	=> $this->url->link('product/category', 'path=' . $category['category_id']),
						'type'	=> $category['type'],
						'label'	=> false,
						'active'	=> false,
						'active_parent'	=> false,
						'children'	=> array()
					);
				}
			}
			break;

			case 'manufacturer' : 
			if($data['manufacturer_id'] == $manufacturer_id) {
				$active = true;
			}
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($data['manufacturer_id']);
			if ($manufacturer_info) {
				$menu_title = $manufacturer_info['name'];
				$menu_href = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $data['manufacturer_id']);
			}else{
				$flag = false;
			}
			break;
			case 'information' : 
			if($data['information_id'] == $information_id) {
				$active = true;
			}
			$information_info = $this->model_catalog_information->getInformation($data['information_id']);
			if ($information_info) {
				$menu_title = $information_info['title'];
				$menu_href = $this->url->link('information/information', 'information_id=' . $data['information_id']);
			}else{
				$flag = false;
			}
			break;
			case 'news_article' : 
			if($data['news_article'] == $article_id) {
				$active = true;
			}
			$article_info = $this->model_newsblog_article->getArticle($data['news_article']);
			if ($article_info) {
				$menu_title = $article_info['name'];
				$menu_href = $this->url->link('newsblog/article', 'newsblog_article_id=' . $data['news_article']);
			}else{
				$flag = false;
			}
			break;
			case 'news_category' : 
			if($data['news_category_id'] == $news_category_id) {
				$active = true;
			}else if(in_array($data['news_category_id'], $news_parts)) {
				$active_parent = true;
			}
			$category_info = $this->model_newsblog_category->getCategory($data['news_category_id']);
			if ($category_info) {
				$menu_title = $category_info['name'];
				$menu_href = $this->url->link('newsblog/category', 'newsblog_path=' . $data['news_category_id']);
			}else{
				$flag = false;
			}
			break;
			case 'standart' : 
			switch($data['standart']){
				case 'common/home' :
				$menu_title = 'Главная';
				break;
				case 'product/catalog' :
				$menu_title = 'Каталог';
				break;
				case 'product/special' :
				$menu_title = 'Акции';
				break;
				case 'product/manufacturer' :
				$menu_title = 'Производители';
				if($manufacturer_id) {$active_parent = true;}
				break;
				case 'information/contact' :
				$menu_title = 'Контакты';
				break;
				case 'information/sitemap' :
				$menu_title = 'Карта сайта';
				break;
				case 'product/compare' :
				$menu_title = 'Сравнение';
				break;
				default:
				$flag = false;
			}
			if($data['standart'] == $route) {$active = true;}
			$menu_href = $this->url->link($data['standart']);
			break;
			case 'href' : 
			$menu_title = $data['title'];
			$menu_href = $data['href'];
			break;

			default:
			$flag = false;

		}

		if(!$flag){return array();}

		if($data['submenu'] && $data['type'] !== 'category_type') {
			$menu_children = $this->getMenuData($data['submenu'], false);
		}

		$image = '';

		if($data['image'] && is_file(DIR_IMAGE . $data['image'])) {
			$image_info = getimagesize(DIR_IMAGE . $data['image']);

			$width  = $image_info[0];
			$height = $image_info[1];

			$image = $this->model_tool_image->resize($data['image'], $width, $height);
		}

		$menu_data = array(
			'type'	=> $data['type'],
			'title'	=> $data['title'] ? $data['title'] : $menu_title,
			'href'	=> $menu_href,
			'label'	=> $data['label'] ? true : false,
			'active'	=> $active,
			'image'	=> $image,
			'active_parent'	=> $active_parent,
			'children'	=> $menu_children
		);


		return $menu_data;
	}

	public function resize_light($filename, $width, $height) {
		if (!is_file(DIR_IMAGE . $filename)) {
			if (is_file(DIR_IMAGE . 'no_image.jpg')) {
				$filename = 'no_image.jpg';
			} elseif (is_file(DIR_IMAGE . 'no_image.png')) {
				$filename = 'no_image.png';
			} else {
				return;
			}
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$proportion = 1;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-auto_width_' . (int)$height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
				return DIR_IMAGE . $image_old;
			}
			$proportion = $height/$height_orig;
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}
			
			$new_width = round($width_orig * $proportion,2);
			
			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $image_old);
				$image->resize($new_width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}

		$imagepath_parts = explode('/', $image_new);
		$new_image = implode('/', array_map('rawurlencode', $imagepath_parts));

		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $new_image;
		} else {
			return $this->config->get('config_url') . 'image/' . $new_image;
		}
	}

	
	public function resize_crop($filename, $width=100, $height=100, $type='') {
		return $this->resize($filename, $width, $height, 'original');
	}
	public function resize($filename, $width, $height, $type = '', $position = "center") {
		if (!is_file(DIR_IMAGE . $filename)) {
			if (is_file(DIR_IMAGE . 'no_image.jpg')) {
				$filename = 'no_image.jpg';
			} elseif (is_file(DIR_IMAGE . 'no_image.png')) {
				$filename = 'no_image.png';
			} else {
				return;
			}
		}


		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		if($extension === 'svg') {
			$image_old = $filename;
			$image_new = 'cache/' . $filename;
			if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
				copy(DIR_IMAGE . $filename, DIR_IMAGE . $image_new);
			} 

			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				return $this->config->get('config_ssl') . 'image/' . $image_new;
			} else {
				return $this->config->get('config_url') . 'image/' . $image_new;
			}
		}

		$image_old = $filename;

		if($type === 'original') {
			list($width, $height, $image_type) = getimagesize(DIR_IMAGE . $image_old);
		}

		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;


		if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
				return DIR_IMAGE . $image_old;
			}

			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $image_old);

				$scaleW = $width_orig/$width;
				$scaleH = $height_orig/$height;


				if ($scaleH > $scaleW) {
					$_height = $height * $scaleW;

					$top_x = 0;
					$top_y = ($height_orig - $_height) / 2;

					$bottom_x = $width_orig;
					$bottom_y = $top_y + $_height;

					$image->crop($top_x, $top_y, $bottom_x, $bottom_y);
				} elseif ($scaleH < $scaleW) {
					$_width = $width * $scaleH;

					$top_x = ($width_orig - $_width) / 2;
					$top_y = 0;

					if($position === 'left') {
						$top_x = 0;
					}

					$bottom_x = $top_x + $_width;
					$bottom_y = $height_orig;

					$image->crop($top_x, $top_y, $bottom_x, $bottom_y);
				}

				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new, 100);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}

		$imagepath_parts = explode('/', $image_new);
		$new_image = implode('/', array_map('rawurlencode', $imagepath_parts));

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . 'image/' . $new_image;
		} else {
			return $this->config->get('config_url') . 'image/' . $new_image;
		}
	}

	public function getModule($module_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "' AND position = '" . $this->db->escape($position) . "' ORDER BY sort_order");
		
		return $query->rows;
	}

	public function editSetting($code, $data, $store_id = 0) {
		// $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($data as $key => $value) {
			if (substr($key, 0, strlen($code)) == $code) {

				$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "'");

				if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value, true)) . "', serialized = '1'");
				}
			}
		}
	}

	public function getTabsByCategory($category_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_tab pt LEFT JOIN " . DB_PREFIX . "product_tab_description ptd ON (pt.tab_id = ptd.tab_id) LEFT JOIN " . DB_PREFIX . "product_tab_to_category pt2c ON (pt.tab_id = pt2c.tab_id) WHERE ptd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pt2c.category_id = '" . (int)$category_id . "' AND pt.status = '1' ORDER BY pt.sort_order, LCASE(ptd.title) ASC");

		return $query->rows;
	}

	public function trueText($count, $text1 = '', $text24 = '', $text5 = ''){
		$true_text = '';

		$true_text = $text1;

		if($count > 4 && $count < 20){
			$true_text = $text5;
		}else{
			$n = $count % 10;
			if($n > 1 && $n < 5){
				$true_text = $text24;
			}else{
				$true_text = $text5;
			}
			if($n == 1){$true_text = $text1;}
		}

		return $true_text;
	}

	public function getDownload($download_id = 0) {
		
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE d.download_id = '" . (int)$download_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getBanner($type = '') {
		$banner_data = array();
		if($type) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "avbanner b WHERE b.type = '" . $this->db->escape($type) . "' AND b.date_start < NOW() AND b.date_stop > NOW() AND b.status = '1' ORDER BY RAND(), b.date_start DESC LIMIT 1");
			if($query->num_rows) {
				if($type !== 'branding') {
					$this->db->query("UPDATE " . DB_PREFIX . "avbanner SET count_viewed = (count_viewed + 1) WHERE banner_id = '" . (int)$query->row['banner_id'] . "'");
				}
				$banner_data = $query->row;
			}
		}

		return $banner_data;
	}

	public function updateBannerView($banner_id = 0) {
		if($banner_id) {
			$this->db->query("UPDATE " . DB_PREFIX . "avbanner SET count_viewed = (count_viewed + 1) WHERE banner_id = '" . (int)$banner_id . "'");
		}
	}

	public function updateBannerClick($banner_id = 0) {
		$banner_data = array();
		if($banner_id) {
			$this->db->query("UPDATE " . DB_PREFIX . "avbanner SET count_click = (count_click + 1) WHERE banner_id = '" . (int)$banner_id . "'");
		}
	}


	public function alert($data, $title = '') {
		$message = "\n";

		if(is_array($data)) {
			foreach($data as $key=>$value) {
				$message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
			}
		}else {
			$message .= $data;
		}


		/* MAIL ALERT */

		$mail_title = $title ? $title : 'Сообщение об ошибке';

		$email_subject = 	$mail_title . ' - ' . $this->config->get('config_name');


		$mail = new Mail();
		$mail->protocol = $this->config->get('av_alert_mail_protocol');
		$mail->parameter = $this->config->get('av_alert_mail_parameter');
		$mail->smtp_hostname = $this->config->get('av_alert_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('av_alert_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('av_alert_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('av_alert_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('av_alert_mail_smtp_timeout');

		$mail->setTo('bu.babasik@gmail.com');
		$mail->setFrom($this->config->get('av_alert_mail_protocol') === 'smtp' ? $this->config->get('av_alert_mail_smtp_username') : $this->config->get('av_alert_email'));
		$mail->setSender('АВ Клуб | AV Club');
		$mail->setSubject($email_subject);

		$mail->setText($message);
		$mail->send();
	}

}