<?php
class ControllerJournalJournal extends Controller {

	public function index() {
		
		$this->load->language('journal/journal');

		$this->load->model('journal/journal');

		$this->load->model('themeset/themeset');
		$this->load->model('tool/image');

		$master_info = $this->config->get('av_master');
		$data['master_info'] = array(
			'title'					=> $master_info['master_title'],
			'description'		=> $master_info['master_description'],
			'link'					=> $master_info['master_link'],
			'button'				=> $master_info['master_button'],
		);

		if (isset($this->request->get['journal_id'])) {
			$journal_id = (int)$this->request->get['journal_id'];
		} else {
			$journal_id = 0;
		}

		$data['journal_id'] = $journal_id;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		

		$journal_info = $this->model_journal_journal->getJournal($journal_id);

		if ($journal_info) {

			$type = $journal_info['type'];

			$meta_info = $this->config->get('journal_meta_' . $type);

			$data['breadcrumbs'][] = array(
				'text' => $meta_info['meta_h1'],
				'href' => $this->url->link('journal/' . $type)
			);

			$this->document->setTitle($journal_info['title']);

			$data['breadcrumbs'][] = array(
				'text' => $journal_info['title'],
				'href' => $this->url->link('journal/journal', 'journal_id=' . $this->request->get['journal_id'])
			);

			if ($journal_info['meta_title']) {
				$this->document->setTitle($journal_info['meta_title']);
			} else {
				$this->document->setTitle($journal_info['title']);
			}

			$this->document->setDescription($journal_info['meta_description']);
			$this->document->setKeywords($journal_info['meta_keyword']);

			if ($journal_info['meta_h1']) {
				$data['heading_title'] = $journal_info['meta_h1'];
			} else {
				$data['heading_title'] = $journal_info['title'];
			}

			if ($journal_info['image']) {
				$data['thumb'] = $this->model_themeset_themeset->resize_crop($journal_info['image']);
				$this->document->setOgImage($data['thumb']);
			} else {
				$data['thumb'] = '';
			}

			$data['author'] = array();

			if($journal_info['author_id']) {
				$this->load->model('visitor/visitor');
				$author_info = $this->model_visitor_visitor->getVisitor($journal_info['author_id'], $journal_info['author_exp']);
				if($author_info) {
					$data['author'] = array(
						'name'		=>	$author_info['name'],
						'exp'			=>	$author_info['exp'],
						'avatar'	=>	$this->model_themeset_themeset->resize($author_info['image'],220,220)
					);
				}
			}

			$case = $type === 'case' ? $this->model_journal_journal->getCase($journal_id) : array();
			if($case) {
				if ($case['logo']) {
					$logo = $this->model_themeset_themeset->resize_crop($case['logo']);
				} else {
					$logo = '';
				}
				$case['logo'] = $logo;
			}
			$data['case'] = $case;

			$data['type'] = $journal_info['type'];

			$data['video'] = $journal_info['video'];

			$data['copies'] = $this->model_journal_journal->getCopies($journal_id);

			$data['description'] = html_entity_decode($journal_info['description'], ENT_QUOTES, 'UTF-8');

			$wish_list = $this->wishlist->getKeyList();
			$data['wish_active'] = ($wish_list && in_array($journal_id, $wish_list)) ? true : false;

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('journal/journal_info', $data));
		} else {
			$url = '';

			if (isset($this->request->get['journal_id'])) {
				$url .= '&journal_id=' . $this->request->get['journal_id'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/manufacturer/info', $url)
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
