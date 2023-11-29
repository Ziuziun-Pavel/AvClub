<?php
class ControllerExtensionModuleMasterRelated extends Controller {
	public function index() {

		$master_id = isset($this->request->get['master_id']) ? $this->request->get['master_id'] : 0;

		if(!$master_id) {return false;}
		
		$this->load->model('master/master');
        $this->load->model('journal/journal');

        $this->load->model('themeset/themeset');

		$data['masters'] = array();

		$this->load->model('visitor/visitor');

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

		$results = $this->model_master_master->getRelated($master_id);

		$data['heading_title'] = $this->config->get('master_related_title');
		
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
		);

		foreach ($results as $result) {
			$journal = $this->model_journal_journal->getJournal($result['journal_id']);

			if(!$journal) {continue;}

			$data['filters'][$journal['type']]['visible'] = true;

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
				'type_text' 	 	=> ($journal['type'] === 'video' && $journal['master_old']) ? $type_list['video_master'] : $type_list[$journal['type']],
				'author' 	 			=> !empty($author_info['name']) ? $author_info['name'] : '',
				'exp' 	 				=> !empty($author_info['exp']) ? $author_info['exp'] : '',
				'thumb'     	  => $image,
				'case'     	  	=> $case,
				'title'       	=> $journal['title'],
				'date'   				=> date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
				'wish'					=> ($wish_list && in_array($journal['journal_id'], $wish_list)) ? true : false,
				'preview'       => $journal['preview'],
				'href'        	=> $this->url->link('journal/'.$journal['type'].'/info', 'journal_id=' . $journal['journal_id'])
			);
		}



		if(!$data['journals']) {return false;}
		return $this->load->view('extension/module/master_related', $data);
	}
}
