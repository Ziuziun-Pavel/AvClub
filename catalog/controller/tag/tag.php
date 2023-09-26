<?php
class ControllerTagTag extends Controller {
	public function index() {

		$this->load->model('tag/tag');
		$this->load->model('journal/journal');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		$meta_info = $this->config->get('av_tag');


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $meta_info['bread'],
			'href' => $this->url->link('master/master')
		);


		$this->document->setTitle($meta_info['meta_title']);
		$this->document->setDescription($meta_info['meta_description']);
		$this->document->setKeywords($meta_info['meta_keyword']);

		$data['heading_title'] = $meta_info['meta_h1'];

		$data['tags'] = array();

		$results = $this->model_tag_tag->getTags();

		foreach($results as $result) {
			$data['tags'][] = array(
				'tag_id'		=> $result['tag_id'],
				'title'			=> $result['title'],
				'href'			=> $this->url->link('tag/tag/info', 'tag_id=' . $result['tag_id'])
			);
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('tag/tag_list', $data));

	}

	public function info() {
		$this->load->language('journal/journal');

		$this->load->model('journal/journal');
		$this->load->model('tag/tag');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		$master_info = $this->config->get('av_master');
		$data['master_info'] = array(
			'title'					=> $master_info['master_title'],
			'description'		=> $master_info['master_description'],
			'link'					=> $master_info['master_link'],
			'button'				=> $master_info['master_button'],
		);

		$meta_info = $this->config->get('av_tag');

		if (isset($this->request->get['tag_id'])) {
			$tag_id = (int)$this->request->get['tag_id'];
		} else {
			$tag_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('av_limit_tag');

		$tag_info = $this->model_tag_tag->getTag($tag_id);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if($tag_info) {

			$data['breadcrumbs'][] = array(
				'text' => $meta_info['bread'],
				'href' => $this->url->link('tag/tag')
			);

			$data['breadcrumbs'][] = array(
				'text' => $tag_info['title'],
				'href' => $this->url->link('tag/tag/info', 'tag_id=' . $tag_id)
			);

			if($tag_info['meta_title']) {
				$this->document->setTitle($tag_info['meta_title']);
			}else{
				$this->document->setTitle('Новости, интервью и кейсы на тему ' . $tag_info['title'] . ' | АВ Клуб');
			}
			
			$this->document->setDescription($tag_info['meta_description']);
			$this->document->setKeywords($tag_info['meta_keyword']);

			$data['heading_title'] = $tag_info['title'];


			$data['journals'] = array();

			$this->load->model('visitor/visitor');

			$filter_data = array(
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

			$type_list = array(
				'news'					=> 'Новость',
				'opinion'				=> 'Мнение',
				'case'					=> 'Кейс',
				'article'				=> 'Статья',
				'video'					=> 'Видео',
				'video_master'	=> 'Прошедшее онлайн-событие',
			);

			

			foreach ($results as $result) {

				$author_info = $this->model_visitor_visitor->getVisitor($result['author_id'], $result['author_exp']);

				if($result['type'] === 'opinion') {
					if (!empty($author_info['image'])) {
						$image = $this->model_themeset_themeset->resize($author_info['image'], $size_opinion_width, $size_opinion_height);
					} else {
						$image = $this->model_themeset_themeset->resize('placeholder.png', $size_opinion_width, $size_opinion_height);
					}
				} else if($result['type'] === 'video' && $result['master_old']){
					if ($result['image']) {
						$image = $this->model_themeset_themeset->resize($result['image'], $size_news_width, $size_news_height, '', 'left');
					} else {
						$image = $this->model_themeset_themeset->resize('placeholder.png', $size_news_width, $size_news_height);
					}
				} else{
					if ($result['image']) {
						$image = $this->model_themeset_themeset->resize($result['image'], $size_news_width, $size_news_height);
					} else {
						$image = $this->model_themeset_themeset->resize('placeholder.png', $size_news_width, $size_news_height);
					}
				}
				
				$time = strtotime($result['date_available']);

				$case = $result['type'] === 'case' ? $this->model_journal_journal->getCase($result['journal_id']) : array();

				$data['journals'][] = array(
					'journal_id' 	 	=> $result['journal_id'],
					'type' 	 				=> $result['type'],
					'type_text' 	 	=> ($result['type'] === 'video' && $result['master_old']) ? $type_list['video_master'] : $type_list[$result['type']],
					'author' 	 			=> !empty($author_info['name']) ? $author_info['name'] : '',
					'exp' 	 				=> !empty($author_info['exp']) ? $author_info['exp'] : '',
					'thumb'     	  => $image,
					'case'     	  => $case,
					'title'       	=> $result['title'],
					'date'   				=> date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
					'wish'					=> ($wish_list && in_array($result['journal_id'], $wish_list)) ? true : false,
					'preview'       => $result['preview'],
					'href'        	=> $this->url->link('journal/'.$result['type'].'/info', 'journal_id=' . $result['journal_id'])
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
						'href'        	=> $this->url->link('master/master', 'master_id=' . $result['master_id']),
						'title'				=> $result['title'],
						'author'			=> $result['author'],
						'exp'					=> $result['exp'],
						'date'				=> $result['date'],
						'time'				=> $result['time'],
					);
				}
			}

			$url = '';

			if (isset($this->request->get['tag_id'])) {
				$url .= '&tag_id=' . $this->request->get['tag_id'];
			}

			$pagination = new Pagination();
			$pagination->total = $journal_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('tag/tag/info', 'tag_id=' . $this->request->get['tag_id'] . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($journal_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($journal_total - $limit)) ? $journal_total : ((($page - 1) * $limit) + $limit), $journal_total, ceil($journal_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
				$this->document->addLink($this->url->link('tag/tag/info', 'tag_id=' . $this->request->get['tag_id'], true), 'canonical');
			} elseif ($page == 2) {
				$this->document->addLink($this->url->link('tag/tag/info', 'tag_id=' . $this->request->get['tag_id'], true), 'prev');
			} else {
				$this->document->addLink($this->url->link('tag/tag/info', 'tag_id=' . $this->request->get['tag_id'] . '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($journal_total / $limit) > $page) {
				$this->document->addLink($this->url->link('tag/tag/info', 'tag_id=' . $this->request->get['tag_id'] . '&page='. ($page + 1), true), 'next');
			}


			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('tag/tag_info', $data));


		}else {
			$url = '';

			if (isset($this->request->get['tag_id'])) {
				$url .= '&tag_id=' . $this->request->get['tag_id'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('tag/tag/info', $url)
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
