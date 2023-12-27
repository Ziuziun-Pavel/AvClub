<?php
class ControllerAveventEvent extends Controller {
	public function index() {
		$this->load->language('avevent/event');

		$this->load->model('avevent/event');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		$meta_info = $this->config->get('av_meta_event');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $meta_info['bread'],
			'href' => $this->url->link('event/article')
		);


		$this->document->setTitle($meta_info['meta_title']);
		$this->document->setDescription($meta_info['meta_description']);
		$this->document->setKeywords($meta_info['meta_keyword']);

		$data['heading_title'] = $meta_info['meta_h1'];

		$month_list = array(
			1 	=> 'января',
			2 	=> 'февраля',
			3 	=> 'марта',
			4 	=> 'апреля',
			5 	=> 'мая',
			6 	=> 'июня',
			7 	=> 'июля',
			8 	=> 'августа',
			9 	=> 'сентября',
			10 	=> 'октября',
			11 	=> 'ноября',
			12 	=> 'декабря'
		);


		$data['events'] = array();

		$filter_data = array();

		$event_total = $this->model_avevent_event->getTotalEvents($filter_data);


		$size_width = $this->config->get('av_size_article_width');
		$size_height = $this->config->get('av_size_article_height');

		$data['types'] = $this->model_avevent_event->getEventTypes();
		$data['cities'] = $this->model_avevent_event->getEventCities();

		$results = $this->model_avevent_event->getEvents($filter_data);

		foreach ($results as $result) {

			if ($result['image'] && is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height);
			} else {
				$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
			}

			$date = strtotime($result['date']);

            $date_stop = $result['date_stop'] ? strtotime($result['date_stop']) : $date;
            $date_month = $month_list[(int)date('m', $date)];

            $date_stop_month = $month_list[(int)date('m', $date_stop)];
            $this->document->addLink($this->url->link('avevent/event/info', 'event_id=' . $result['event_id']), 'canonical');
			$data['events'][] = array(
				'event_id' 	 		=> $result['event_id'],
				'show_event' 	 	=> $result['show_event'],
				'thumb'     	  => $image,
				'title'       	=> $result['title'],
				'address'      	=> $result['address'],
				'type'       		=> $result['type'],
				'city'       		=> $result['city'],
				'type_id'       => $result['type_id'],
				'city_id'       => $result['city_id'],
				'date'       		=> date('d', $date),
                'date_stop'       		=> date('d', $date_stop),
                'date_month'       		=> $date_month,
                'date_stop_month'       		=> $date_stop_month,
                'time_start'    => date('H:s', strtotime($result['time_start'])),
				'time_end'    	=> date('H:s', strtotime($result['time_end'])),
				'href'        	=> $this->url->link('avevent/event/info', 'event_id=' . $result['event_id'])
			);
		}

//        var_dump($this->url->link('avevent/event/info', 'event_id=279'));

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('avevent/event_list', $data));

	}

	public function info() {

		$this->load->language('avevent/event');

		$this->load->model('avevent/event');

		$this->load->model('themeset/themeset');
		$this->load->model('tool/image');

		if (isset($this->request->get['event_id'])) {
			$event_id = (int)$this->request->get['event_id'];
		} else {
			$event_id = 0;
		}

		$meta_info = $this->config->get('av_meta_event');

		$data['event_id'] = $event_id;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $meta_info['bread'],
			'href' => $this->url->link('avevent/event')
		);



		$event_info = $this->model_avevent_event->getEvent($event_id);

		if ($event_info) {
			$this->document->setTitle($event_info['title']);

			$data['breadcrumbs'][] = array(
				'text' => $event_info['title'],
				'href' => $this->url->link('avevent/event/info', 'event_id=' . $this->request->get['event_id'])
			);

			if ($event_info['meta_title']) {
				$this->document->setTitle($event_info['meta_title']);
			} else {
				$this->document->setTitle($event_info['title']);
			}

			$this->document->setDescription($event_info['meta_description']);
			$this->document->setKeywords($event_info['meta_keyword']);

			if ($event_info['meta_h1']) {
				$data['heading_title'] = $event_info['meta_h1'];
			} else {
				$data['heading_title'] = $event_info['title'];
			}

			if ($event_info['image_full'] && is_file(DIR_IMAGE . $event_info['image_full'])) {
				$data['image'] = $this->model_themeset_themeset->resize_crop($event_info['image_full']);
				$this->document->setOgImage($data['image']);
			} else {
				$data['image'] = '';
			}

			$month_list = array(
				1 	=> 'января',
				2 	=> 'февраля',
				3 	=> 'марта',
				4 	=> 'апреля',
				5 	=> 'мая',
				6 	=> 'июня',
				7 	=> 'июля',
				8 	=> 'августа',
				9 	=> 'сентября',
				10 	=> 'октября',
				11 	=> 'ноября',
				12 	=> 'декабря'
			);


			$data['link'] = $event_info['link'];
			$data['coord'] = $event_info['coord'];
			$data['count'] = $event_info['count'];
			$data['price'] = $event_info['price'];
			$data['count_text'] = $this->model_themeset_themeset->trueText($event_info['count'], 'место', 'места', 'мест');

			$data['event_id'] = $event_info['event_id'];
			$data['address_full'] = $event_info['address_full'];
			$data['type'] = $event_info['type'];
			$data['city'] = $event_info['city'];
//            $data['testparam']  = 'проверка';
			$date = strtotime($event_info['date']);

            $date_stop = $event_info['date_stop'] ? strtotime($event_info['date_stop']) : $date;

            $data['date'] = date('d', $date);
            $data['date_month'] = $month_list[(int)date('m', $date)];
            $data['date_year'] = date('Y', $date);

            $data['date_stop'] = date('d', $date_stop);
            $data['date_stop_month'] = $month_list[(int)date('m', $date_stop)];

			$time_start = strtotime($event_info['time_start']);
			$time_end = strtotime($event_info['time_end']);

			$data['time_start'] = date('H:s', $time_start);
			$data['time_end'] = date('H:s', $time_end);

			$size_suffix = array(
				'b',
				'Kb',
				'Mb',
				'Gb',
				'Tb',
				'Pb',
				'Eb',
				'Zb',
				'Yb'
			);

			$data['video'] = array();
			$data['brand_list'] = array();
			$data['speaker_list'] = array();
			$data['ask_list'] = array();
			$data['prg_list'] = array();
			$data['present_list'] = array();

			$template = $this->model_avevent_event->getTemplate($event_id);

			foreach($template as $key=>$item) {

				if(!$item['status']) {continue;}

				switch($key) {
					case 'video':
					$data['video'] = array(
						'href'	=> $event_info['video'],
						'image'	=> $this->model_themeset_themeset->resize_crop($event_info['video_image'])
					);
					break;

					case 'brand':
					$data['brand_title'] = $event_info['brand_title'];
					$data['brand_template'] = $event_info['brand_template'];
					$brand_list = $this->model_avevent_event->getCompaniesByEvent($event_id);
					if($brand_list) {
						$sort_brand = array();
						foreach($brand_list as $brand) {
							$data['brand_list'][] = array(
								'company_id'	=> $brand['company_id'],
								'title'				=> $brand['title'],
								'image'				=> $this->model_tool_image->resize($brand['image'], 214, 100),
								'href'				=> !empty($brand['status']) ? $this->url->link('company/info', 'company_id=' . $brand['company_id']) : '',
							);
							$sort_brand[] = $brand['title'];
						}
						array_multisort($sort_brand, SORT_ASC, $data['brand_list']);
					}
					break;

					case 'present':
					$data['present_title'] = $event_info['present_title'];
					$present_list = $this->model_avevent_event->getPresentsByEvent($event_id);
					if($present_list) {
						foreach($present_list as $present) {
							$data['present_list'][] = array(
								'present_id'	=> $present['present_id'],
								'title'				=> $present['title'],
								'href'				=> $present['href'],
								'image'				=> $this->model_themeset_themeset->resize($present['image'], 345, 346),
							);
						}
					}
					break;

					case 'speaker':
					$data['speaker_title'] = $event_info['speaker_title'];
					$speaker_list = $this->model_avevent_event->getAuthorsByEvent($event_id);
					if($speaker_list) {
						foreach($speaker_list as $speaker) {
							$data['speaker_list'][] = array(
								'author_id'		=> $speaker['author_id'],
								'name'				=> $speaker['name'],
								'exp'					=> $speaker['exp'],
								'image'				=> $this->model_themeset_themeset->resize($speaker['image'], 220, 220),
								'href'				=> !empty($speaker['expert']) ? $this->url->link('expert/expert', 'expert_id=' . $speaker['author_id']) : '',
							);
						}
					}
					break;

					case 'ask':
					$data['ask_title'] = $event_info['ask_title'];
					$data['ask_list'] = $this->model_avevent_event->getAskByEvent($event_id);
					break;

					case 'plus':
					$plus_list = $this->model_avevent_event->getPlusByEvent($event_id);
					if($plus_list) {
						foreach($plus_list as $plus) {
							$plus_image = $this->model_themeset_themeset->resize($plus['image'], 100, 100);

							$data['plus_list'][] = array(
								'title'				=> $plus['title'],
								'text'				=> $plus['text'],
								'image'				=> $plus_image,
							);
						}
					}
					break;

					case 'prg':
					$data['prg_title'] = $event_info['prg_title'];
					$data['prg_template'] = $event_info['prg_template'];
					$data['prg_file'] = array();
					if($event_info['prg_file_id']) {
						$prg_file = $this->model_themeset_themeset->getDownload($event_info['prg_file_id']);
						if($prg_file && file_exists(DIR_DOWNLOAD . $prg_file['filename'])) {
							$size = filesize(DIR_DOWNLOAD . $prg_file['filename']);

							$mask = basename($prg_file['mask']);

							$i = 0;

							while (($size / 1024) > 1) {
								$size = $size / 1024;
								$i++;
							}

							$data['prg_file'] = array(
								'name'       => $prg_file['name'],
								'ext'       => pathinfo(DIR_DOWNLOAD . $mask, PATHINFO_EXTENSION),
								'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $size_suffix[$i],
								'href'       => $this->url->link('themeset/themeset/download', 'download_id=' . $prg_file['download_id'], true)
							);
						}
					}
					$prg_list = $this->model_avevent_event->getPrgByEvent($event_id);
					if($prg_list) {
						foreach($prg_list as $prg) {
							if($event_info['prg_template'] && $prg['image'] && is_file(DIR_IMAGE . $prg['image'])) {
								$prg_image = $this->model_themeset_themeset->resize_crop($prg['image']);
							}else{
								$prg_image = '';
							}

							$data['prg_list'][] = array(
								'title'				=> $prg['title'],
								'text'				=> $prg['text'],
								'time_start'	=> date('H:i', strtotime($prg['time_start'])),
								'time_end'	=> date('H:i', strtotime($prg['time_end'])),
								'image'				=> $prg_image,
							);
						}
					}
					break;

					case 'insta':
					$data['insta_title'] = $event_info['insta_title'];
					$insta_list = $this->model_avevent_event->getInstaByEvent($event_id);
					if($insta_list) {
						foreach($insta_list as $insta) {
							if($insta['image'] && is_file(DIR_IMAGE . $insta['image'])) {
								$insta_image = $this->model_themeset_themeset->resize($insta['image'], 380, 380);
							}else{
								continue;
							}

							$data['insta_list'][] = array(
								'href'				=> $insta['href'],
								'image'				=> $insta_image,
							);
						}
					}
					break;

					default:
					break;
				}

			}

			$data['template'] = $template;

			$data['description'] = html_entity_decode($event_info['description'], ENT_QUOTES, 'UTF-8');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('avevent/event_info', $data));
		} else {
			$url = '';

			if (isset($this->request->get['event_id'])) {
				$url .= '&event_id=' . $this->request->get['event_id'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('avevent/event/info', $url)
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
