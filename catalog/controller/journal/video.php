<?php
class ControllerJournalVideo extends Controller {
	public function index() {
		$this->load->language('journal/journal');

		$this->load->model('journal/journal');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		$master_info = $this->config->get('av_master');
		$data['master_info'] = array(
			'title'					=> $master_info['master_title'],
			'description'		=> $master_info['master_description'],
			'link'					=> $master_info['master_link'],
			'button'				=> $master_info['master_button'],
		);

		$meta_info = $this->config->get('av_meta_video');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('av_limit_video');


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $meta_info['bread'],
			'href' => $this->url->link('journal/video')
		);


		$this->document->setTitle($meta_info['meta_title']);
		$this->document->setDescription($meta_info['meta_description']);
		$this->document->setKeywords($meta_info['meta_keyword']);

		$data['heading_title'] = $meta_info['meta_h1'];


		$data['journals'] = array();

		$filter_data = array(
			'filter_type' 		=> 'video',
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

		$size_width = $this->config->get('av_size_video_width');
		$size_height = $this->config->get('av_size_video_height');

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
			
			$time = strtotime($result['date_available']);

			$data['journals'][] = array(
				'journal_id' 	 	=> $result['journal_id'],
				'type' 	 				=> $result['type'],
				'thumb'     	  => $image,
				'title'       	=> $result['title'],
				'date'   				=> date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
				'wish'					=> ($wish_list && in_array($result['journal_id'], $wish_list)) ? true : false,
				'preview'       => $result['preview'],
				'href'        	=> $this->url->link('journal/video/info', 'journal_id=' . $result['journal_id'])
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

		// telegram
		$data['telegram'] = array(
			'status'	=> $this->config->get('tgram_status'),
			'link'	=> $this->config->get('tgram_link'),
			'text'	=> $this->config->get('tgram_text'),
		);


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

		$pagination = new Pagination();
		$pagination->total = $journal_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('journal/video', 'page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($journal_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($journal_total - $limit)) ? $journal_total : ((($page - 1) * $limit) + $limit), $journal_total, ceil($journal_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
			$this->document->addLink($this->url->link('journal/video', '', true), 'canonical');
		} elseif ($page == 2) {
			$this->document->addLink($this->url->link('journal/video', '', true), 'prev');
		} else {
			$this->document->addLink($this->url->link('journal/video', 'page='. ($page - 1), true), 'prev');
		}

		if ($limit && ceil($journal_total / $limit) > $page) {
			$this->document->addLink($this->url->link('journal/video', 'page='. ($page + 1), true), 'next');
		}


		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('journal/video', $data));

	}

	public function info() {
		require_once(DIR_APPLICATION . 'controller/journal/journal-info.php');
	}
}
