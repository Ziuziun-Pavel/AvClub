<?php
class ControllerCompanyInfo extends Controller {

	public function index() {
		$this->load->language('company/company');

		$this->load->model('company/company');
		$this->load->model('company/company');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		$meta_info = $this->config->get('av_company');

		if (isset($this->request->get['company_id'])) {
			$company_id = (int)$this->request->get['company_id'];
		} else {
			$company_id = 0;
		}

		$data['company_id'] = $company_id; 

		$company_info = $this->model_company_company->getCompany($company_id);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if($company_info) {

			$data['breadcrumbs'][] = array(
				'text' => $meta_info['bread'],
				'href' => $this->url->link('company/company')
			);

			$data['breadcrumbs'][] = array(
				'text' => $company_info['title'],
				'href' => $this->url->link('company/info', 'company_id=' . $company_id)
			);

			if($company_info['meta_title']) {
				$this->document->setTitle($company_info['meta_title']);
			}else{
				$this->document->setTitle($company_info['title']);
			}
			
			$this->document->setDescription($company_info['meta_description']);
			$this->document->setKeywords($company_info['meta_keyword']);

			if ($company_info['meta_h1']) {
				$data['heading_title'] = $company_info['meta_h1'];
			} else {
				$data['heading_title'] = $company_info['title'];
			}



			if($company_info['image'] && is_file(DIR_IMAGE . $company_info['image'])) {
				$data['image'] = $this->model_tool_image->resize($company_info['image'], 220, 85);
			}else{
				$data['image'] = '';
			}

			$data['activity'] = $company_info['activity'];
			$data['preview'] = mb_strlen($company_info['description']) <= 80 ? $company_info['description'] : utf8_substr(strip_tags(html_entity_decode($company_info['description'], ENT_QUOTES, 'UTF-8')), 0, 80) . '...';
			$data['description'] = html_entity_decode($company_info['description'], ENT_QUOTES, 'UTF-8');

			// gallery
			$data['gallery'] = array();
			$gallery = $this->model_company_company->getGalleryByCompany($company_id);
			if($gallery) {

				foreach($gallery as $image) {
					if ($image && is_file(DIR_IMAGE . $image)) {
						$data['gallery'][] = array(
							'image'	=> $this->model_themeset_themeset->resize_crop($image, 855, 425),
							'thumb'	=> $this->model_themeset_themeset->resize($image, 280, 280),
						);
					}
				}

			}

			// brands
			$data['tags'] = $this->model_company_company->getTagsByCompany($company_id);
			$data['brands'] = $this->model_company_company->getBrandsByCompany($company_id);
			$data['branches'] = $this->model_company_company->getBranchesByCompany($company_id);

			$data['phones'] = array();
			if($company_info['phone_1']) {$data['phones'][] = $company_info['phone_1'];}
			if($company_info['phone_2']) {$data['phones'][] = $company_info['phone_2'];}

			$data['links'] = array();
			if($company_info['site']) {
				$link = '';
				if(stripos($company_info['site'], 'https://') === false && stripos($company_info['site'], 'https://') === false) {
					$link = 'http://' . $company_info['site'];
				}else{
					$link = $company_info['site'];
				}
				$data['links'][] = array(
					'type'	=>	'link',
					'href'		=>	$link,
					'link'		=>	$company_info['site']
				);
			}
			if($company_info['email']) {
				$data['links'][] = array(
					'type'	=>	'mail',
					'href'	=>	$company_info['email'],
					'link'	=>	$company_info['email']
				);
			}

			$data['social'] = array();
			$social = $this->model_company_company->getCompanySocial($company_id);
			if($social) {
				foreach($social as $key=>$item) {
					switch($item['type']) {
						case 'FACEBOOK': $icon = 'fb';break;
						case 'TELEGRAM': $icon = 'tg';break;
						case 'VK': $icon = 'vk';break;
						case 'SKYPE': $icon = 'skype';break;
						case 'VIBER': $icon = 'viber';break;
						case 'INSTAGRAM': $icon = 'insta';break;
						case 'BITRIX24': $icon = 'b24_net';break;
						case 'OPENLINE': $icon = 'online_chat';break;
						case 'IMOL': $icon = 'imol';break;
						case 'ICQ': $icon = 'icq';break;
						case 'MSN': $icon = 'msn_live';break;
						case 'JABBER'	: $icon = 'jabber';break;
						default: $icon = 'other';
					}
					$data['social'][] = array(
						'link'	=> $item['link'],
						'href'	=> $item['link'],
						'icon'	=> $icon
					);
				}
			}

			if(!empty($this->request->get['tab'])) {
				switch(true) {
					
					case in_array($this->request->get['tab'], array('online')):
					$show_tab = 'master';
					break;
					
					case in_array($this->request->get['tab'], array('event', 'master', 'expert', 'info')):
					$show_tab = $this->request->get['tab'];
					break;

					default: $show_tab = '';
				}
			}else{
				$show_tab = '';
			}

			$data['tabs'] = array();


			$active_tab = true;
			$tag_id = $company_info['tag_id'] ? $company_info['tag_id'] : 0;
			$tabs = $this->model_company_company->getTabsCompany($company_id, $company_info['tag_id']);
			$content_key = $show_key = $show_tab;

			
			if($tabs) {

				if($show_tab) {
					foreach($tabs as $key=>$tab) {
						if($key === $show_tab) {
							$content_key = $show_key = $key;
						}
					}
				}

				foreach($tabs as $key=>$tab) {
					$children = array();

					switch($key) {
						case 'journal':	$title = 'Публикации';	break;
						case 'event':	$title = 'Мероприятия';	break;
						case 'master':	$title = 'Онлайн-события';	break;
						case 'expert':	$title = 'Эксперты';	break;
						default:	$title = ''; break;	
					}

					if($key === 'journal') {
						$active_child = true;

						if(!$content_key) {$content_key = $show_key = $key;}

						if($active_child && $content_key === $key) {$show_key = 'alljournal';}
						$children[] = array(
							'key'			=> 'alljournal',
							'title'		=> 'все материалы',
							'active'	=> ($active_child ? 'active ' : '') . ($show_key === 'alljournal' ? ' load ' : ''),
							'isactive'	=> ($active_child ? true : false)
						);
						$active_child = false;

						foreach($tab as $type) {
							switch($type) {
								case 'news':	$child_title = 'новости';	break;
								case 'video':	$child_title = 'видео';	break;
								case 'article':	$child_title = 'статьи';	break;
								case 'case':	$child_title = 'кейсы';	break;
								case 'opinion':	$child_title = 'мнения';	break;
								default:	$child_title = ''; break;	
							}
							if($active_child && $show_tab === $key) {$show_key = $type;}
							$children[] = array(
								'key'			=> $type,
								'title'		=> $child_title,
								'active'	=> ($active_child ? 'active' : '') . ($show_key === $type ? ' load ' : ''),
								'isactive'	=> ($active_child ? true : false),
							);
							$active_child = false;

						}
					}

					if($key === 'master') {
						$active_child = true;

						if(!$content_key) {$content_key = $show_key = $key;}
						foreach($tab as $type) {
							switch($type) {
								case 'mastertobe':	$child_title = 'Все события';	break;
								case 'mastermaster':	$child_title = 'Мастер-классы';	break;
								case 'mastermeetup':	$child_title = 'Митапы';	break;
								case 'masterold':	$child_title = 'Прошедшие';	break;
								default:	$child_title = ''; break;	
							}
							if($active_child && $content_key === $key) {$show_key = $type;}
							$children[] = array(
								'key'			=> $type,
								'title'		=> $child_title,
								'active'	=> ($active_child ? 'active' : '') . ($show_key === $type ? ' load ' : ''),
								'isactive'	=> ($active_child ? true : false),
							);
							$active_child = false;

						}
					}

					if(in_array($key, array('evevent', 'event')) && is_array($tab)) {
						$active_child = true;

						if(!$content_key) {$content_key = $show_key = $key;}
						foreach($tab as $type) {
							switch($type) {
								case 'event_new':	$child_title = 'Ближайшие';	break;
								case 'event_old':	$child_title = 'Прошедшие';	break;
								default:	$child_title = ''; break;	
							}
							$children[] = array(
								'key'			=> $type,
								'title'		=> $child_title,
								'active'	=> ($active_child ? 'active' : ''),
								'isactive'	=> ($active_child ? true : false),
							);
							if($active_child && ($content_key === $key)) {$show_key = $type;}
							$active_child = false;

						}

					}

					if(!$content_key) {$content_key = $show_key = $key;}

					$data['tabs'][] = array(
						'key'				=> $key,
						'title'			=> $title,
						'active'		=> ($content_key === $key ? 'active load' : ''),
						'isactive'	=> ($content_key === $key ? true : false),
						'children'	=> $children,
					);
					if($content_key === $key) {
						$active_tab = false;
					}

				}
			}

			$data['info_status'] = false;
			if($data['description'] || $data['brands'] || $data['tags'] || $data['gallery']) {

				$data['tabs'][] = array(
					'key'				=> 'info',
					'title'			=> 'Информация',
					'active'		=> ($active_tab ? 'active load' : ''),
					'isactive'		=> ($active_tab ? true : false),
					'children'	=> array(),
				);
				$data['info_status'] = true;
			}

			// master add
			$meta_info = $this->config->get('av_master');

			$data['master_info'] = array(
				'title'					=> $meta_info['master_title'],
				'description'		=> $meta_info['master_description'],
				'link'					=> $meta_info['master_link'],
				'button'				=> $meta_info['master_button'],
			);

			$data['content'] = $show_key ? $this->load->controller('company/content/content_' . $show_key, 'company_id=' . $company_id . '&type=' . $show_key) : '';


			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('company/company_info', $data));


		}else {
			$url = '';

			if (isset($this->request->get['company_id'])) {
				$url .= '&company_id=' . $this->request->get['company_id'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('company/info', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['header'] = $this->load->controller('common/header');
			$data['footer'] = $this->load->controller('common/footer');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}


	}
}
