<?php
class ControllerCompanyInfoJournal extends Controller {

	public function index() {
		
		$this->load->model('journal/journal');
		$this->load->model('company/company');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		if (isset($this->request->get['company_id'])) {
			$company_id = (int)$this->request->get['company_id'];
		} else {
			$company_id = 0;
		}

		$company_info = $this->model_company_company->getCompany($company_id);
		$tag_id = $company_info['tag_id'];

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 21;

		if (isset($this->request->get['type']) && !in_array($this->request->get['type'], array('journal', 'alljournal'))) {
			$type = $this->request->get['type'];
		} else {
			$type = '';
		}

		$data['journals'] = array();

		$this->load->model('visitor/visitor');


		$filter_data = array(
			'filter_type' 		=> $type,
			'filter_tag' 			=> $tag_id,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);

		$journal_total = $this->model_journal_journal->getTotalJournals($filter_data);

		$wish_list = $this->wishlist->getKeyList();

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
				'wish'					=> true,
				'preview'       => $journal['preview'],
				'href'        	=> ($journal['type'] === 'special' ? $journal['link'] : $this->url->link('journal/'.$journal['type'].'/info', 'journal_id=' . $journal['journal_id'])),
				'blank'       	=> ($journal['type'] === 'special' ? true : false)
			);
		}


		return $this->load->view('company/info_journal', $data);
	}
}
