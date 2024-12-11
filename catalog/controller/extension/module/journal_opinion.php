<?php
class ControllerExtensionModuleJournalOpinion extends Controller {
	public function index() {
		
		$this->load->model('journal/journal');
		$this->load->model('themeset/themeset');
		$this->load->model('visitor/visitor');

		if (isset($_GET['deb'])) {
			//var_dump($this);
		}

        if ($this->visitor->getId()) {
            $master_info = $this->config->get('av_master');
            $data['master_info'] = array(
                'title' => $master_info['master_title'],
                'description' => $master_info['master_description'],
                'link' => $master_info['master_link'],
                'button' => $master_info['master_button'],
            );
        }

		$data['heading_title'] = $this->config->get('journal_opinion_title');

		$data['all_opinion'] = $this->url->link('journal/opinion');

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

		$data['journals'] = array();

		$size_width = $this->config->get('av_size_opinion_width');
		$size_height = $this->config->get('av_size_opinion_height');

		$filter_data = array(
			'filter_type' 		=> 'opinion',
			'start'           => 0,
			'limit'           => 3
		);

		$results = $this->model_journal_journal->getJournals($filter_data);

		foreach ($results as $result) {
			$author_info = $this->model_visitor_visitor->getVisitor($result['author_id'], $result['author_exp']);

			if(!$author_info) {continue;}

			if ($author_info['image']) {
				$image = $this->model_themeset_themeset->resize($author_info['image'], $size_width, $size_height);
			} else {
				$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
			}


			$data['journals'][] = array(
				'journal_id' 	 	=> $result['journal_id'],
				'type' 	 				=> $result['type'],
				'author' 	 			=> $author_info['name'],
				'exp' 	 				=> $author_info['exp'],
				'thumb'     	  => $image,
				'title'       	=> $result['title'],
				'wish'					=> ($wish_list && in_array($result['journal_id'], $wish_list)) ? true : false,
				'preview'       => $result['preview'],
				'href'        	=> $this->url->link('journal/opinion/info', 'journal_id=' . $result['journal_id'])
			);
		}

		// master
		$data['master_all'] = $this->url->link('master/master');
		$this->load->model('master/master');
		$data['master_list'] = array();

		$filter_data = array(
			'start'	=> 0,
			'limit'	=> 3
		);

		$results = $this->model_master_master->getMasters($filter_data);
		if($results) {
			foreach($results as $result) {
				$data['master_list'][] = array(
					'master_id'		=> $result['master_id'],
					'href'        => $this->url->link('master/master/info', 'master_id=' . $result['master_id']),
					'title'				=> $result['title'],
					'author'			=> $result['author'],
					'exp'					=> $result['exp'],
					'date'				=> $result['date'],
					'time'				=> $result['time'],
				);
			}
		}

		if(!$data['journals']) {return false;}
		return $this->load->view('extension/module/journal_opinion', $data);
	}
}
