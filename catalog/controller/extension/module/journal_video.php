<?php
class ControllerExtensionModuleJournalVideo extends Controller {
	public function index() {
		
		$this->load->model('journal/journal');
		$this->load->model('themeset/themeset');

		$data['heading_title'] = $this->config->get('journal_video_title');

		$data['all_video'] = $this->url->link('journal/video');

		$wish_list = $this->wishlist->getKeyList();

		$data['journals'] = array();

		$size_width = $this->config->get('av_size_video_width');
		$size_height = $this->config->get('av_size_video_height');

		$filter_data = array(
			'filter_type' 		=> 'video',
			'start'           => 0,
			'limit'           => 3
		);

		$results = $this->model_journal_journal->getJournals($filter_data);

		foreach ($results as $result) {

			if ($result['image']) {
				if($result['master_old']) {
					$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height, '', 'left');
				}else{
					$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height);
				}
				
			} else {
				$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
			}

			$data['journals'][] = array(
				'journal_id' 	 	=> $result['journal_id'],
				'type' 	 				=> $result['type'],
				'thumb'     	  => $image,
				'title'       	=> $result['title'],
				'wish'					=> ($wish_list && in_array($result['journal_id'], $wish_list)) ? true : false,
				'preview'       => $result['preview'],
				'href'        	=> $this->url->link('journal/video/info', 'journal_id=' . $result['journal_id'])
			);
		}

		if(!$data['journals']) {return false;}
		return $this->load->view('extension/module/journal_video', $data);
	}
}
