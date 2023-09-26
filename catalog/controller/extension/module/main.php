<?php
class ControllerExtensionModuleMain extends Controller {
	public function index() {
		
		$this->load->model('journal/journal');
		$this->load->model('themeset/themeset');

		$data['all_news'] = $this->url->link('journal/news');

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

		$size_width = $this->config->get('av_size_news_width');
		$size_height = $this->config->get('av_size_news_height');

		$filter_data = array(
			'filter_type' 		=> 'news',
			'start'           => 0,
			'limit'           => 4
		);

		$results = $this->model_journal_journal->getJournals($filter_data);

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height);
			} else {
				$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
			}

			$time = strtotime($result['date_available']);

			$data['journals'][] = array(
				'journal_id' 	 	=> $result['journal_id'],
				'type' 	 				=> $result['type'],
				'thumb'     	  => $image,
				'title'       	=> $result['title'],
				'date'   				=> date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
				'wish'					=> ($wish_list && in_array($result['journal_id'], $wish_list)) ? true : false,
				'preview'       => $result['preview'],
				'href'        	=> $this->url->link('journal/news/info', 'journal_id=' . $result['journal_id'])
			);
		}

		// main
		$data['main'] = array();
		$main_image = $this->config->get('avmain_banner_image');
		if($main_image && is_file(DIR_IMAGE . $main_image)) {
			$data['main'] = array(
				'thumb'		=> $this->model_themeset_themeset->resize($main_image, $size_width, $size_height),
				'href'		=> $this->config->get('avmain_banner_link'),
				'title'		=> $this->config->get('avmain_banner_title'),
				'target'	=> $this->config->get('avmain_banner_target')
			);
		}

		// BANNER
		$data['banner'] = array();
		$banner_info = $this->model_themeset_themeset->getBanner('content');
		if($banner_info && $banner_info['image_pc'] && is_file(DIR_IMAGE . $banner_info['image_pc'])) {
	
			$data['banner'] = array(
				'banner_id'		=> $banner_info['banner_id'],
				'link'		=> $banner_info['link'],
				'target'	=> $banner_info['target'],
				'image'		=> $this->model_themeset_themeset->resize_crop($banner_info['image_pc'], 100, 100),
			);
		}

		return $this->load->view('extension/module/main', $data);
	}
}
