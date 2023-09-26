<?php
class ControllerCompanyContent extends Controller {

	public function content_journal() { return $this->journal();}
	public function content_alljournal() { return $this->alljournal();}
	public function content_news() { return $this->news();}
	public function content_video() { return $this->video();}
	public function content_article() { return $this->article();}
	public function content_case() { return $this->case();}
	public function content_opinion() { return $this->opinion();}

	public function content_event() { return $this->event();}
	public function content_event_new() { return $this->event(false,'event_new');}
	public function content_event_old() { return $this->event(false,'event_old');}

	public function content_master() { return $this->master();}
	public function content_mastertobe() { return $this->master();}
	public function content_mastermaster() { return $this->master(false,false,'master');}
	public function content_mastermeetup() { return $this->master(false,false,'meetup');}
	public function content_masterold() { return $this->master(false,true, 'masterold');}

	public function content_expert() { return $this->expert();}


	public function index() {

		if (isset($this->request->get['type']) && !in_array($this->request->get['type'], array('journal'))) {
			$type = $this->request->get['type'];
		} else {
			$type = '';
		}
		switch($type) {
			
			case 'journal':
			case 'alljournal':
			case 'news':
			case 'video':
			case 'article':
			case 'case':
			case 'opinion':
			$this->journal(true);
			break;

			case 'event':
			case 'event_new':
			$this->event(true, 'event_new');
			break;

			case 'event_old':
			$this->event(true, 'event_old');
			break;

			case 'master':
			case 'mastertobe':
			$this->master(true);
			break;

			case 'mastermaster':
			$this->master(true, false, 'master');
			break;

			case 'mastermeetup':
			$this->master(true, false, 'meetup');
			break;

			case 'masterold':
			$this->master(true, true);
			break;

			case 'expert':
			$this->expert(true);
			break;

			default: break;
		}

	}

	public function alljournal($json = false) {
		return $this->journal($json);
	}
	public function journal($json = false) {
		
		$this->load->model('journal/journal');
		$this->load->model('company/company');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		if (isset($this->request->get['company_id'])) {
			$company_id = (int)$this->request->get['company_id'];
		} else {
			$company_id = 0;
		}

		if (isset($this->request->get['json'])) {
			$json = true;
		}

		$company_info = $this->model_company_company->getCompany($company_id);
		$tag_id = $company_info['tag_id'];

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 18;

		if (isset($this->request->get['type']) && !in_array($this->request->get['type'], array('journal', 'alljournal'))) {
			$type = $this->request->get['type'];
		} else {
			$type = '';
		}

		$data['journals'] = array();

		$data['continue'] = $this->url->link('common/home');

		$this->load->model('visitor/visitor');


		$filter_data = array(
			'filter_type' 		=> $type,
			'filter_tag' 			=> $tag_id,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);

		$journal_total = $this->model_journal_journal->getTotalJournals($filter_data);

		$wish_list = $this->wishlist->getKeyList();

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

		$results = $this->model_journal_journal->getJournals($filter_data);

		$size_news_width = $this->config->get('av_size_news_width');
		$size_news_height = $this->config->get('av_size_news_height');

		$size_opinion_width = $this->config->get('av_size_opinion_width');
		$size_opinion_height = $this->config->get('av_size_opinion_height');

		foreach ($results as $journal) {

			$author_info = $this->model_visitor_visitor->getVisitor($journal['author_id'], $journal['author_exp']);

			if($journal['type'] === 'opinion') {
				if (!empty($author_info['image'])) {
					$image = $this->model_themeset_themeset->resize($author_info['image'], $size_opinion_width, $size_opinion_height);
				} else {
					$image = $this->model_themeset_themeset->resize('placeholder.png', $size_opinion_width, $size_opinion_height);
				}
			} else if($journal['type'] === 'video' && $journal['master_old']){
				if ($journal['image']) {
					$image = $this->model_themeset_themeset->resize($journal['image'], $size_news_width, $size_news_height, '', 'left');
				} else {
					$image = $this->model_themeset_themeset->resize('placeholder.png', $size_news_width, $size_news_height);
				}
			} else{
				if ($journal['image']) {
					$image = $this->model_themeset_themeset->resize($journal['image'], $size_news_width, $size_news_height);
				} else {
					$image = $this->model_themeset_themeset->resize('placeholder.png', $size_news_width, $size_news_height);
				}
			}
			
			$time = strtotime($journal['date_available']);

			$case = $journal['type'] === 'case' ? $this->model_journal_journal->getCase($journal['journal_id']) : array();

			$data['journals'][] = array(
				'journal_id' 	 	=> $journal['journal_id'],
				'type' 	 				=> $journal['type'],
				'filter' 	 			=> 'filter-' . $journal['type'],
				'type_text' 	 	=> '',
				'author' 	 			=> !empty($author_info['name']) ? $author_info['name'] : '',
				'exp' 	 				=> !empty($author_info['exp']) ? $author_info['exp'] : '',
				'thumb'     	  => $image,
				'case'     	  	=> $case,
				'title'       	=> $journal['title'],
				'date'   				=> date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
				'wish'					=> ($wish_list && in_array($journal['journal_id'], $wish_list)) ? true : false,
				'preview'       => $journal['preview'],
				'href'        	=> ($journal['type'] === 'special' ? $journal['link'] : $this->url->link('journal/'.$journal['type'].'/info', 'journal_id=' . $journal['journal_id'])),
				'blank'       	=> ($journal['type'] === 'special' ? true : false)
			);
		}

		$url = '';
		$url .= '&company_id=' . $company_id; 

		$pagination = new Pagination();
		$pagination->total = $journal_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('company/info', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		if($json) {
			$data['template'] = $this->load->view('company/info_journal', $data);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}else{
			return $this->load->view('company/info_journal', $data);
		}
	}

	public function event($json = false, $type = 'all') {
		
		$this->load->language('avevent/event');

		$this->load->model('avevent/event');
		$this->load->model('company/company');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		if (isset($this->request->get['company_id'])) {
			$company_id = (int)$this->request->get['company_id'];
		} else {
			$company_id = 0;
		}

		if (!empty($this->request->get['type'])) {
			switch ($this->request->get['type']) {

				case 'event_new':
				case 'new':
				$type = 'event_new';
				break;

				case 'event_old':
				case 'old':
				$type = 'event_old';
				break;
			}
		}

		if (isset($this->request->get['json'])) {
			$json = true;
		}

		$company_info = $this->model_company_company->getCompany($company_id);
		$tag_id = $company_info['tag_id'];

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 6;

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

		$filter_data = array(
			'filter_company'	=> $company_id,
			'filter_type'			=> $type,
			'sort'						=> 'e.date_available',
			'order'						=> $type === 'event_new' ? 'ASC' : 'DESC',
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);

		$event_total = $this->model_avevent_event->getTotalEvents($filter_data, false);

		$size_width = $this->config->get('av_size_article_width');
		$size_height = $this->config->get('av_size_article_height');

		$data['types'] = $this->model_avevent_event->getEventTypes();
		$data['cities'] = $this->model_avevent_event->getEventCities();

		$results = $this->model_avevent_event->getEvents($filter_data, false);

		foreach ($results as $result) {

			if ($result['image'] && is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height);
			} else {
				$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
			}

			$date = strtotime($result['date']);

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
				'date'       		=> date('d', $date) . '&nbsp;' . $month_list[(int)date('m', $date)],
				'time_start'    => date('H:s', strtotime($result['time_start'])),
				'time_end'    	=> date('H:s', strtotime($result['time_end'])),
				'href'        	=> $this->url->link('avevent/event/info', 'event_id=' . $result['event_id'])
			);
		}

		$url = '';
		$url .= '&company_id=' . $company_id; 

		$pagination = new Pagination();
		$pagination->total = $event_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('company/info', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		if($json) {
			$data['template'] = $this->load->view('company/info_event', $data);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}else{
			return $this->load->view('company/info_event', $data);
		}
	}

	public function master($json = false, $master_old = false, $type = '') {

		$this->load->model('master/master');
		$this->load->model('journal/journal');
		$this->load->model('company/company');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		if (isset($this->request->get['company_id'])) {
			$company_id = (int)$this->request->get['company_id'];
		} else {
			$company_id = 0;
		}


		if (isset($this->request->get['json'])) {
			$json = true;
		}

		if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('masterold'))) {
			$master_old = true;
		}

		$company_info = $this->model_company_company->getCompany($company_id);
		$tag_id = $company_info['tag_id'];

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 15;

		$data['continue'] = $this->url->link('common/home');
		
		if(!$master_old) {
			// masters
			$data['masters'] = array();

			$filter_data = array(
				'filter_company'	=> $company_id,
				'filter_type'			=> $type
			);

			$results = $this->model_master_master->getMasters($filter_data);

			$size_width = $this->config->get('av_size_master_width');
			$size_height = $this->config->get('av_size_master_height');

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height);
				} else {
					$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
				}
				if ($result['logo']) {
					$logo = $this->model_themeset_themeset->resize_crop($result['logo']);
				} else {
					$logo = '';
				}

				$data['masters'][] = array(
					'master_id' 	 	=> $result['master_id'],
					'image'     	  => $image,
					'logo'     	  => $logo,
					'title'       	=> $result['title'],
					'preview'       => $result['preview'],
					'date'       		=> $result['date'],
					'time'       		=> $result['time'],
					'author'       	=> $result['author'],
					'exp'       		=> $result['exp'],
					'href'        	=> $result['link']
				);
			}
		}


		if($master_old) {
			// journals
			$data['journals'] = array();

			$filter_data = array(
				'filter_type' 		=> 'video',
				'filter_tag' 			=> $tag_id,
				'filter_master' 	=> true,
				'start'           => ($page - 1) * $limit,
				'limit'           => $limit
			);

			$journal_total = $this->model_journal_journal->getTotalJournals($filter_data);

			$wish_list = $this->wishlist->getKeyList();

			$results = $this->model_journal_journal->getJournals($filter_data);

			$size_width = $this->config->get('journal_size_news_width');
			$size_height = $this->config->get('journal_size_news_height');

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height, '', 'left');
				} else {
					$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
				}

				$data['journals'][] = array(
					'journal_id' 	 	=> $result['journal_id'],
					'type'       		=> $result['type'],
					'thumb'     	  => $image,
					'title'       	=> $result['title'],
					'wish'					=> ($wish_list && in_array($result['journal_id'], $wish_list)) ? true : false,
					'preview'       => $result['preview'],
					'href'        	=> $this->url->link('journal/video/info', 'journal_id=' . $result['journal_id'])
				);
			}

			$url = '';
			$url .= '&company_id=' . $company_id; 

			$pagination = new Pagination();
			$pagination->total = $journal_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('company/info', $url . '&page={page}');

			$data['pagination'] = $pagination->render();

		}



		if($json) {
			$data['template'] = $this->load->view('company/info_master', $data);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}else{
			return $this->load->view('company/info_master', $data);
		}
	}

	public function expert($json = false) {

		$this->load->model('company/company');

		$this->load->model('themeset/themeset');

		if (isset($this->request->get['company_id'])) {
			$company_id = (int)$this->request->get['company_id'];
		} else {
			$company_id = 0;
		}

		if (isset($this->request->get['json'])) {
			$json = true;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 18;

		$data['continue'] = $this->url->link('common/home');
		
		$data['experts'] = array();


		$filter_data = array(
			'filter_company' 	=> $company_id,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);

		$expert_total = $this->model_company_company->getTotalExperts($filter_data);

		$results = $this->model_company_company->getExperts($filter_data);

		$size_width = $this->config->get('journal_size_opinion_width');
		$size_height = $this->config->get('journal_size_opinion_height');

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height, '', 'left');
			} else {
				$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
			}

			$data['experts'][] = array(
				'thumb'     	  => $image,
				'name'       		=> $result['name'],
				'exp'       		=> $result['exp'],
				'href'					=> !empty($result['expert']) ? $this->url->link('expert/expert', 'expert_id=' . $result['visitor_id']) : '',
			);
		}	

		$url = '';
		$url .= '&company_id=' . $company_id; 

		$pagination = new Pagination();
		$pagination->total = $expert_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('company/info', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		if($json) {
			$data['template'] = $this->load->view('company/info_expert', $data);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}else{
			return $this->load->view('company/info_expert', $data);
		}
	}
}
