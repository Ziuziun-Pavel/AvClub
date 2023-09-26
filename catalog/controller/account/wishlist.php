<?php
class ControllerAccountWishList extends Controller {
	public function index() {

		$this->load->language('account/wishlist');

		$this->load->model('account/wishlist');

		$this->load->model('journal/journal');

		$this->load->model('themeset/themeset');

		if (isset($this->request->get['remove'])) {
			// Remove Wishlist
			$this->model_account_wishlist->deleteWishlist($this->request->get['remove']);

			$this->session->data['success'] = $this->language->get('text_remove');

			$this->response->redirect($this->url->link('account/wishlist'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/wishlist')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_stock'] = $this->language->get('column_stock');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['filters'] = array(
			''					=> array('title'=>'Все материалы', 'visible'=>true),
			'news'			=> array('title'=>'Новости', 'visible'=>false),
			'opinion'		=> array('title'=>'Мнения', 'visible'=>false),
			'case'			=> array('title'=>'Кейсы', 'visible'=>false),
			'article'		=> array('title'=>'Статьи', 'visible'=>false),
			'video'			=> array('title'=>'Видео', 'visible'=>false),
			'special'		=> array('title'=>'Спецпроекты', 'visible'=>false),
		);

		$data['journals'] = array();

		$this->load->model('visitor/visitor');

		$results = $this->wishlist->getList();

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
		);

		foreach ($results as $result) {
			$journal = $this->model_journal_journal->getJournal($result['journal_id']);

			if ($journal) {

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
					'wish'					=> true,
					'preview'       => $journal['preview'],
					'href'        	=> ($journal['type'] === 'special' ? $journal['link'] : $this->url->link('journal/'.$journal['type'].'/info', 'journal_id=' . $journal['journal_id'])),
					'blank'       	=> ($journal['type'] === 'special' ? true : false)
				);
			} else {
				$this->model_account_wishlist->deleteWishlist($result['journal_id']);
			}
		}

		$data['continue'] = $this->url->link('common/home', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/wishlist', $data));
	}

	public function add() {

		$json = array();

		if (isset($this->request->post['journal_id'])) {
			$journal_id = $this->request->post['journal_id'];
		} else {
			$journal_id = 0;
		}

		$this->load->model('journal/journal');

		$journal_info = $this->model_journal_journal->getJournal($journal_id);

		if ($journal_info) {
			$this->wishlist->add($journal_id);

			$json['total_count'] = $this->wishlist->count();

		}

		$json['success'] = true;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {

		$json = array();

		if (isset($this->request->post['journal_id'])) {
			$journal_id = $this->request->post['journal_id'];
		} else {
			$journal_id = 0;
		}

		if (isset($this->request->post['url']) && $this->request->post['url']) {
			$json['redirect'] = $this->url->link($this->request->post['url']);
		}

		$this->wishlist->remove($journal_id);

		$json['total_count'] = $this->wishlist->count();
		
		$json['success'] = true;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
