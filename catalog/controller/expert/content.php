<?php
class ControllerExpertContent extends Controller {

	private $limit = 7;

	public function index() {
		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			$type = 'all';
		}
		$this->items(true, $type);
	}

	public function content_all() {	return $this->items(false, 'all'); }
	public function content_news() {	return $this->items(false, 'news'); }
	public function content_article() {	return $this->items(false, 'article'); }
	public function content_opinion() {	return $this->items(false, 'opinion'); }
	public function content_video() {	return $this->items(false, 'video'); }
	public function content_case() {	return $this->items(false, 'case'); }

	public function content_online() {	return $this->items(false, 'online'); }
	public function content_master() {	return $this->items(false, 'master'); }
	public function content_master_all() {	return $this->items(false, 'master'); }
	public function content_master_master() {	return $this->items(false, 'master_master'); }
	public function content_master_meetup() {	return $this->items(false, 'master_meetup'); }
	public function content_master_old() {	return $this->items(false, 'master_old'); }

	public function content_avevent() {	return $this->items(false, 'avevent'); }
	public function content_event() {	return $this->items(false, 'event'); }
	public function content_event_new() {	return $this->items(false, 'event_new'); }
	public function content_event_old() {	return $this->items(false, 'event_old'); }

	public function json_all() {	$this->items(true, 'all'); }
	public function json_news() {	$this->items(true, 'news'); }
	public function json_article() {	$this->items(true, 'article'); }
	public function json_opinion() {	$this->items(true, 'opinion'); }
	public function json_video() {	$this->items(true, 'video'); }
	public function json_case() {	$this->items(true, 'case'); }

	public function json_online() {	$this->items(true, 'online'); }
	public function json_master() {	$this->items(true, 'master'); }
	public function json_master_all() {	$this->items(true, 'master'); }
	public function json_master_master() {	$this->items(true, 'master_master'); }
	public function json_master_meetup() {	$this->items(true, 'master_meetup'); }
	public function json_master_old() {	$this->items(true, 'master_old'); }

	public function json_avevent() {	$this->items(true, 'avevent'); }
	public function json_event() {	$this->items(true, 'event'); }
	public function json_event_old() {	$this->items(true, 'event_old'); }
	public function json_event_new() {	$this->items(true, 'event_new'); }

	public function items($json = false, $type = '') {
		
		$this->load->model('visitor/visitor');
		$this->load->model('visitor/expert');
		$this->load->model('journal/journal');
		$this->load->model('company/company');
		$this->load->model('avevent/event');
		$this->load->model('master/master');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');
		$this->load->model('themeset/image');

		if (isset($this->request->get['expert_id'])) {
			$expert_id = (int)$this->request->get['expert_id'];
		} else {
			$expert_id = 0;
		}

		$expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);

		if (isset($this->request->get['json'])) {
			$json = true;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		/* bio */
		$data['bio'] = array();
		$bio_keys = array(
			'field_useful' => 'В чем могу быть полезен',
			'field_regalia' => 'Заслуги и регалии',
		);
		foreach($bio_keys as $key=>$title) {
			if(!empty($expert_info[$key])) {
				$data['bio'][] = array(
					'title'	=> $title,
					'text'	=> $expert_info[$key]
				);
			}
		}

		$data['continue'] = $this->url->link('common/home');

		$type_list = array(
			'news'					=> 'Новость',
			'opinion'				=> 'Мнение',
			'case'					=> 'Кейс',
			'article'				=> 'Статья',
			'video'					=> 'Видео',
			'video_master'	=> 'Прошедшее событие',
			'event'					=> 'Мероприятие',
			'master'				=> 'Онлайн-событие',
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


		$filter_data = array(
			'filter_type' 		=> $type,
			'filter_expert' 	=> $expert_id,
			'start'           => ($page - 1) * $this->limit,
			'limit'           => $this->limit
		);

		$items_total = $this->model_visitor_expert->getTotalItems($filter_data);

		$results = $this->model_visitor_expert->getItems($filter_data);

		$data['results'] = array();

		$wish_list = $this->wishlist->getKeyList();

		$size_news_width = $this->config->get('av_size_news_width');
		$size_news_height = $this->config->get('av_size_news_height');

		$size_opinion_width = $this->config->get('av_size_opinion_width');
		$size_opinion_height = $this->config->get('av_size_opinion_height');


		foreach($results as $result) {

			// journal
			if($result['type'] === 'journal') {

				$journal_info = $this->model_journal_journal->getJournal($result['id']);

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
						$image = $this->model_themeset_image->original($journal_info['image'], $size_news_width, $size_news_height);
					} else {
						$image = $this->model_themeset_themeset->resize('placeholder.png', $size_news_width, $size_news_height);
					}
				}

				$time = strtotime($journal_info['date_available']);

				$case = $journal_info['type'] === 'case' ? $this->model_journal_journal->getCase($journal_info['journal_id']) : array();

				$data['results'][] = array(
					'journal_id' 	 	=> $journal_info['journal_id'],
					'type' 	 				=> $journal_info['type'],
					'type' 	 				=> $journal_info['type'],
					'type_text' 	 	=> ($journal_info['type'] === 'video' && $journal_info['master_old']) ? $type_list['video_master'] : $type_list[$journal_info['type']],
					'author' 	 			=> !empty($author_info['name']) ? $author_info['name'] : '',
					'exp' 	 				=> !empty($author_info['exp']) ? $author_info['exp'] : '',
					'thumb'     	  => $image,
					'case'     	  	=> $case,
					'title'       	=> $journal_info['title'],
					'date'   				=> date('d.m.Y', $time),
					'wish'					=> ($wish_list && in_array($journal_info['journal_id'], $wish_list)) ? true : false,
					'preview'       => $journal_info['preview'],
					'href'        	=> ($journal_info['type'] === 'special' ? $journal_info['link'] : $this->url->link('journal/'.$journal_info['type'].'/info', 'journal_id=' . $journal_info['journal_id'])),
					'blank'       	=> ($journal_info['type'] === 'special' ? true : false)
				);
			} 
			// # journal
			// master
			else if($result['type'] === 'master') {

				$master_info = $this->model_master_master->getMaster($result['id']);

				if(!$master_info) {continue;}

				$image = $this->model_themeset_image->original($master_info['image']);

				if ($master_info['logo']) {
					$logo = $this->model_themeset_themeset->resize_crop($master_info['logo']);
				} else {
					$logo = '';
				}

				$time = strtotime($master_info['date_available']);

				$data['results'][] = array(
					'master_id' 	 	=> $master_info['master_id'],
					'type'					=> 'online',
					'type_text' 	 	=> $type_list['master'],
					'thumb'     	  => $image,
					'logo'     		  => $logo,
					'title'       	=> $master_info['title'],
					'preview'       => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . ', ' . $master_info['time'],
					'date'       		=> $master_info['date'],
					'time'       		=> $master_info['time'],
					'author'       	=> $master_info['author'],
					'exp'       		=> $master_info['exp'],
					'href'        	=> $master_info['link']
				);

			} 
			// # master
			// event
			else if($result['type'] === 'event') {

				$event_info = $this->model_avevent_event->getEvent($result['id'], false);

				if(!$event_info) {continue;}

				$image = $this->model_themeset_image->original($event_info['image']);

				$date = strtotime($event_info['date']);

				$data['results'][] = array(
					'event_id' 	 		=> $event_info['event_id'],
					'show_event' 	 	=> $event_info['show_event'],
					'type'					=> 'event',
					'type_text' 	 	=> $event_info['type'] . ' | ' . $event_info['city'],
					'type_text' 	 	=> $type_list['event'],
					'thumb'     	  => $image,
					'title'       	=> $event_info['title'],
					'address'      	=> $event_info['address'],
					'city'       		=> $event_info['city'],
					'date'       		=> date('d.m.Y', $date),
					'time_start'    => date('H:s', strtotime($event_info['time_start'])),
					'time_end'    	=> date('H:s', strtotime($event_info['time_end'])),
					'href'        	=> $this->url->link('avevent/event/info', 'event_id=' . $event_info['event_id'])
				);

			} 
			// # event

		}


		$url = '';
		$url .= '&expert_id=' . $expert_id; 

		$pagination = new Pagination();
		$pagination->total = $items_total;
		$pagination->page = $page;
		$pagination->limit = $this->limit;
		$pagination->url = $this->url->link('company/info', $url . '&page={page}');

		$data['pagination'] = $pagination->render();


		if($json) {

			$data['type'] = $type;
			$data['page'] = $page;
			$data['template'] = $this->load->view('expert/content', $data);

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}else{
			return $this->load->view('expert/content', $data);
		}
	}

}
