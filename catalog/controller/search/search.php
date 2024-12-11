<?php
class ControllerSearchSearch extends Controller {
	public function index() {
		$this->load->language('search/search');

		$this->load->model('search/search');

		$this->load->model('journal/journal');
		$this->load->model('avevent/event');
		$this->load->model('master/master');
		$this->load->model('visitor/visitor');
		$this->load->model('company/company');

		$this->load->model('themeset/themeset');

		$search = isset($this->request->get['search']) ? $this->request->get['search'] : '';
		$search_type = isset($this->request->get['search_type']) ? $this->request->get['search_type'] : '';
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : '';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : '';

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$limit = $this->config->get('av_limit_search');

		$wish_list = $this->wishlist->getKeyList();


		if (isset($this->request->get['search'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);


		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= 'search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['search_type'])) {
			$url .= '&search_type=' . $this->request->get['search_type'];
		}

		
		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => 'По релевантности',
			'value' => '',
			'href'  => $this->url->link('search/search', $url)
		);

		$data['sorts'][] = array(
			'text'  => 'По дате',
			'value' => 'date_available',
			'href'  => $this->url->link('search/search', 'sort=date_available&' . $url)
		);

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['filters'] = array();

		$data['filters'][] = array(
			'text'  => 'Весь сайт',
			'value' => '',
			'href'  => $this->url->link('search/search', $url)
		);

		$data['filters'][] = array(
			'text'  => 'Журнал',
			'value' => 'journal',
			'href'  => $this->url->link('search/search', 'search_type=journal' .  $url)
		);

		$data['filters'][] = array(
			'text'  => 'Онлайн-события',
			'value' => 'master',
			'href'  => $this->url->link('search/search', 'search_type=master' .  $url)
		);

		$data['filters'][] = array(
			'text'  => 'Мероприятия',
			'value' => 'event',
			'href'  => $this->url->link('search/search', 'search_type=event' .  $url)
		);

		$data['filters'][] = array(
			'text'  => 'Компании',
			'value' => 'company',
			'href'  => $this->url->link('search/search', 'search_type=company' .  $url)
		);

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
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

		if (isset($this->request->get['search_type'])) {
			$url .= '&search_type=' . $this->request->get['search_type'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('search/search', $url)
		);

		if (isset($this->request->get['search'])) {
			$data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
		} else {
			$data['heading_title'] = $this->language->get('heading_title');
		}

		$lang_list = array(
			'text_empty',
			'text_search',
			'text_keyword',
			'entry_search',
			
			'button_search',
			'button_cancel',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$data['results'] = array();

		if (isset($this->request->get['search'])) {
			$filter_data = array(
				'filter_search'       => $search,
				'filter_type'         => $search_type,
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);

			$result_total = $this->model_search_search->getTotalResults($filter_data);

			$results = $this->model_search_search->getResults($filter_data);

			$size_news_width = $this->config->get('av_size_news_width');
			$size_news_height = $this->config->get('av_size_news_height');

			$size_opinion_width = $this->config->get('av_size_opinion_width');
			$size_opinion_height = $this->config->get('av_size_opinion_height');

			$type_list = array(
				'news'					=> 'Новость',
				'opinion'				=> 'Мнение',
				'case'					=> 'Кейс',
				'article'				=> 'Статья',
				'video'					=> 'Видео',
				'video_master'	=> 'Прошедшее онлайн-событие',
				'special'				=> 'Спецпроект',
				'event'					=> 'Мероприятие',
				'master'				=> 'Мастер-класс',
			);

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


			foreach ($results as $result) {

				// journal
				if($result['type'] === 'journal') {

					$journal_info = $this->model_journal_journal->getJournal($result['id']);

//                    if ($result['id'] == 5966 || $result['id'] == 5968) {
//                        var_dump($journal_info);
//                    }

					if(!$journal_info) {continue;}

					$author_info = $this->model_visitor_visitor->getVisitor($journal_info['author_id'], $journal_info['author_exp']);

					if($journal_info['type'] === 'opinion') {
						if (!empty($author_info['image'])) {
							$image = $this->model_themeset_themeset->resize($author_info['image'], $size_opinion_width, $size_opinion_height);
						} else {
							$image = $this->model_themeset_themeset->resize('placeholder.png', $size_opinion_width, $size_opinion_height);
						}
					} else if($journal_info['type'] === 'video' && $journal_info['master_old']){
						if ($journal_info['image']) {
							$image = $this->model_themeset_themeset->resize($journal_info['image'], $size_news_width, $size_news_height, '', 'left');
						} else {
							$image = $this->model_themeset_themeset->resize('placeholder.png', $size_news_width, $size_news_height);
						}
					} else{
						if ($journal_info['image']) {
							$image = $this->model_themeset_themeset->resize($journal_info['image'], $size_news_width, $size_news_height);
						} else {
							$image = $this->model_themeset_themeset->resize('placeholder.png', $size_news_width, $size_news_height);
						}
					}
					
					$time = strtotime($journal_info['date_available']);

					$case = $journal_info['type'] === 'case' ? $this->model_journal_journal->getCase($journal_info['journal_id']) : array();

					$data['results'][] = array(
						'journal_id' 	 	=> $journal_info['journal_id'],
						'type' 	 				=> $journal_info['type'],
						'type_text' 	 	=> ($journal_info['type'] === 'video' && $journal_info['master_old']) ? $type_list['video_master'] : $type_list[$journal_info['type']],
						'author' 	 			=> !empty($author_info['name']) ? $author_info['name'] : '',
						'exp' 	 				=> !empty($author_info['exp']) ? $author_info['exp'] : '',
						'thumb'     	  => $image,
						'case'     	  	=> $case,
						'title'       	=> $journal_info['title'],
						'date'   				=> date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
						'wish'					=> ($wish_list && in_array($journal_info['journal_id'], $wish_list)) ? true : false,
						'preview'       => $journal_info['preview'],
						'href'        	=> ($journal_info['type'] === 'special' ? $journal_info['link'] : $this->url->link('journal/'.$journal_info['type'].'/info', 'journal_id=' . $journal_info['journal_id'])),
						'blank'       	=> ($journal_info['type'] === 'special' ? true : false)
					);

				} // # journal
				// master
				else if($result['type'] === 'master') {

					$master_info = $this->model_master_master->getMaster($result['id']);

					if(!$master_info) {continue;}

					$image = $this->model_themeset_themeset->resize($master_info['image'], 400, 400);

					if ($master_info['logo']) {
						$logo = $this->model_themeset_themeset->resize_crop($master_info['logo']);
					} else {
						$logo = '';
					}

					$data['results'][] = array(
						'master_id' 	 	=> $master_info['master_id'],
						'type'					=> 'master',
						'type_text' 	 	=> $type_list['master'],
						'image'     	  => $image,
						'logo'     		  => $logo,
						'title'       	=> $master_info['title'],
						'preview'       => $master_info['preview'],
						'date'       		=> $master_info['date'],
						'time'       		=> $master_info['time'],
						'author'       	=> $master_info['author'],
						'exp'       		=> $master_info['exp'],
						'href'        	=> $this->url->link('master/master/info', 'master_id=' . $master_info['master_id'])
					);

				} // # master
				// event
				else if($result['type'] === 'event') {

					$event_info = $this->model_avevent_event->getEvent($result['id'], true);

					if(!$event_info) {continue;}

					$image = $this->model_themeset_themeset->resize($event_info['image'], $size_news_width, $size_news_height);

					$date = strtotime($event_info['date']);

					$data['results'][] = array(
						'event_id' 	 		=> $event_info['event_id'],
						'show_event' 	 	=> $event_info['show_event'],
						'type'					=> 'event',
						'type_text' 	 	=> $event_info['type'] . ' | ' . $event_info['city'],
						'image'     	  => $image,
						'title'       	=> $event_info['title'],
						'address'      	=> $event_info['address'],
						'city'       		=> $event_info['city'],
						'date'       		=> date('d', $date) . '&nbsp;' . $month_list[(int)date('m', $date)],
						'time_start'    => date('H:s', strtotime($event_info['time_start'])),
						'time_end'    	=> date('H:s', strtotime($event_info['time_end'])),
						'href'        	=> $this->url->link('avevent/event/info', 'event_id=' . $event_info['event_id'])
					);

				} // # event
				// company
				else if($result['type'] === 'company') {

					$company_info = $this->model_company_company->getCompany($result['id'], true);

					if(!$company_info) {continue;}

					$image = $this->model_themeset_themeset->resize($company_info['image'], 220, 85);

					$date = strtotime($company_info['date']);

					$data['results'][] = array(
						'company_id' 	 	=> $company_info['company_id'],
						'type'					=> 'company',
						'image'     	  => $image,
						'thumb'     	  => $image,
						'title'       	=> $company_info['title'],
						'preview'       => mb_strlen($company_info['description']) <= 80 ? $company_info['description'] : utf8_substr(strip_tags(html_entity_decode($company_info['description'], ENT_QUOTES, 'UTF-8')), 0, 80) . '...',
						'href'        	=> $this->url->link('company/info', 'company_id=' . $company_info['company_id'])
					);

				} // # company
				
				
			}

//die();
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['search_type'])) {
				$url .= '&search_type=' . $this->request->get['search_type'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['search_form'] = $this->url->link('search/search', $url);



			$pagination = new Pagination();
			$pagination->total = $result_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('search/search', $url . '&page={page}');

			$data['pagination'] = $pagination->render();


			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('search/search', '', true), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('search/search', '', true), 'prev');
			} else {
			    $this->document->addLink($this->url->link('search/search', $url . '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($result_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('search/search', $url . '&page='. ($page + 1), true), 'next');
			}

		}



		$data['search'] = $search;
		$data['search_type'] = $search_type;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('search/search', $data));
	}
}
