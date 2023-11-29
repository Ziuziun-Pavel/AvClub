<?php
class ControllerJournalJournal extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('journal/journal');

		$this->getList();
	}

	public function news() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title_news'));

		$this->load->model('journal/journal');

		$this->getList('news');
	}

	public function video() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title_video'));

		$this->load->model('journal/journal');

		$this->getList('video');
	}

	public function opinion() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title_opinion'));

		$this->load->model('journal/journal');

		$this->getList('opinion');
	}

	public function case() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title_case'));

		$this->load->model('journal/journal');

		$this->getList('case');
	}

	public function special() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title_special'));

		$this->load->model('journal/journal');

		$this->getList('special');
	}


	public function article() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title_article'));

		$this->load->model('journal/journal');

		$this->getList('article');
	}

	public function add() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('journal/journal');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_journal_journal->addJournal($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_master_old'])) {
			$url .= '&filter_master_old=' . $this->request->get['filter_master_old'];
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

			$type = !empty($this->request->post['type']) ? $this->request->post['type'] : 'article';

			$this->response->redirect($this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('journal/journal');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            var_dump( $this->request->post);

            $this->model_journal_journal->editJournal($this->request->get['journal_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_master_old'])) {
			$url .= '&filter_master_old=' . $this->request->get['filter_master_old'];
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

			$type = !empty($this->request->post['type']) ? $this->request->post['type'] : 'article';

			$this->response->redirect($this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('journal/journal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('journal/journal');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $journal_id) {
				$this->model_journal_journal->deleteJournal($journal_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_master_old'])) {
			$url .= '&filter_master_old=' . $this->request->get['filter_master_old'];
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

			$type = !empty($this->request->get['type']) ? $this->request->get['type'] : 'article';

			$this->response->redirect($this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList($type = '') {

		$data['public_status'] = $this->user->hasPermission('modify', 'journal/status');
		
		if($type) {
			$heading_title = $this->language->get('heading_title_' . $type);
		}else{
			$heading_title = $this->language->get('heading_title');
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		
		if (isset($this->request->get['filter_master_old'])) {
			$filter_master_old = $this->request->get['filter_master_old'];
		} else {
			$filter_master_old = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'j.date_available';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_master_old'])) {
			$url .= '&filter_master_old=' . $this->request->get['filter_master_old'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . $url, true)
		);

		if($type) {
			$url .= '&type=' . $type;
		}


		$data['meta'] = $this->url->link('journal/setting/' . $type, 'token=' . $this->session->data['token'] . $url, true);
		$data['add'] = $this->url->link('journal/journal/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('journal/journal/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['journals'] = array();

		$filter_data = array(
			'sort'  						=> $sort,
			'order' 						=> $order,
			'filter_type' 			=> $type,
			'filter_name' 			=> $filter_name,
			'filter_master_old' => $filter_master_old,
			'start' 						=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 						=> $this->config->get('config_limit_admin')
		);


		$journal_total = $this->model_journal_journal->getTotalJournals($filter_data);


		$results = $this->model_journal_journal->getJournals($filter_data);

		foreach ($results as $result) {
			$data['journals'][] = array(
				'journal_id' 			=> $result['journal_id'],
				'title'          	=> $result['title'],
				'date_available'	=> $result['date_available'],
				'started'   	    => strtotime($result['date_available']) < time() ? true : false,
				'sort_order'     	=> $result['sort_order'],
				'status'     			=> $result['status'],
				'master_old'     	=> $type === 'video' && $result['master_old'] ? true : false,
				'view'           	=> HTTPS_CATALOG . 'index.php?route=journal/' . $result['type'] . '/info&journal_id=' . $result['journal_id'],
				'preview'           	=> HTTPS_CATALOG . 'index.php?route=journal/' . $result['type'] . '/info&journal_id=' . $result['journal_id'] . '&preview=1',
				'public'           	=> $this->url->link('journal/status', 'token=' . $this->session->data['token'] . '&journal_id=' . $result['journal_id'] . $url, true),
				'edit'           	=> $this->url->link('journal/journal/edit', 'token=' . $this->session->data['token'] . '&journal_id=' . $result['journal_id'] . $url, true)
			);
		}

		

		$data['heading_title'] = $heading_title;

		$lang_list = array(
			'entry_master_old',

			'text_list',
			'text_no_results',
			'text_confirm',
			'text_enabled',
			'text_disabled',
			'text_yes',
			'text_no',

			'column_date_available',
			'column_title',
			'column_status',
			'column_sort_order',
			'column_action',
			'column_master_old',

			'button_add',
			'button_edit',
			'button_view',
			'button_preview',
			'button_status',
			'button_add',
			'button_delete',
			'button_filter',
		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_master_old'])) {
			$url .= '&filter_master_old=' . $this->request->get['filter_master_old'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . '&sort=jd.title' . $url, true);
		$data['sort_date_available'] = $this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . '&sort=j.date_available' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . '&sort=j.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_master_old'])) {
			$url .= '&filter_master_old=' . $this->request->get['filter_master_old'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $journal_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($journal_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($journal_total - $this->config->get('config_limit_admin'))) ? $journal_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $journal_total, ceil($journal_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_master_old'] = $filter_master_old;
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['type'] = $type;
		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('journal/journal_list', $data));
	}

	protected function getForm() {

		$type = !empty($this->request->get['type']) ? $this->request->get['type'] : 'article';

		$journal_id = !empty($this->request->get['journal_id']) ? $this->request->get['journal_id'] : 0;

    //CKEditor
		if ($this->config->get('config_editor_default')) {
			$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
			$this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
		} else {
			$this->document->addScript('view/javascript/summernote/summernote.js');
			$this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
			$this->document->addScript('view/javascript/summernote/opencart.js');
			$this->document->addStyle('view/javascript/summernote/summernote.css');
		}

		$this->document->addScript('view/javascript/jquery-ui.sortable/jquery-ui.min.js');

		if($type) {
			$heading_title = $this->language->get('heading_title_' . $type);
		}else{
			$heading_title = $this->language->get('heading_title');
		}
		$data['heading_title'] = $heading_title;

		$data['text_form'] = !isset($this->request->get['journal_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$lang_list = array(
			'text_default',
			'text_enabled',
			'text_disabled',
			
			'entry_image',
			'entry_image_show',
			'entry_title',
			'entry_description',
			'entry_preview',
			'entry_meta_title',
			'entry_meta_h1',
			'entry_meta_description',
			'entry_meta_keyword',
			'entry_keyword',
			'entry_sort_order',
			'entry_status',
			'entry_layout',
			'entry_date_available',
			'entry_master',
			'entry_master_default',
			'entry_tag',
			'entry_tag_placeholder',
			'entry_copy',
			'entry_copy_remove',
			'entry_author',
			'entry_author_placeholder',
			'entry_experts',
			'entry_experts_placeholder',
			'entry_video',
			'entry_master_old',
			
			'help_keyword',
			'help_input_master',
			
			'button_save',
			'button_cancel',
			'button_add',

			'tab_general',
			'tab_data',
			'tab_gallery',
			'tab_column',
			'tab_design',
			'tab_copy',

			'tag_new',
			'tag_exist',
			'tag_remove',

			'tab_case',
			'case_title',
			'case_description',
			'case_logo',
			'case_bottom',
			'case_template',
			'case_template_simple',
			'case_template_full',
			'case_catalog',
			'text_yes',
			'text_no',
			'case_attr',
			'case_text',
			'case_remove',


		);
		foreach($lang_list as $key) {
			$data[$key] = $this->language->get($key);
		}


		$error_list = array(
			'warning'				=> '',
			'title'					=> array(),
			'description'		=> array(),
			'meta_title'		=> array(),
			'keyword'				=> '',
			'link'					=> '',
			'case'					=> '',
			'author'				=> '',
		);

		foreach($error_list as $key=>$default) {
			if (isset($this->error[$key])) {
				$data['error_' . $key] = $this->error[$key];
			} else {
				$data['error_' . $key] = $default;
			}
		}


		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_master_old'])) {
			$url .= '&filter_master_old=' . $this->request->get['filter_master_old'];
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

		$url .= '&type=' . $type; 

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('journal/journal/' . $type, 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['journal_id'])) {
			$data['action'] = $this->url->link('journal/journal/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('journal/journal/edit', 'token=' . $this->session->data['token'] . '&journal_id=' . $this->request->get['journal_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('journal/journal', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['journal_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$journal_info = $this->model_journal_journal->getJournal($this->request->get['journal_id']);
			$type = $journal_info['type'];
		}

		$data['type'] = $type;

		$data['public_status'] = $this->user->hasPermission('modify', 'journal/status');


		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['config_language_id'] = $this->config->get('config_language_id'); // SEO URL Generator

		$data['lang'] = $this->language->get('lang');

		if (isset($this->request->post['journal_description'])) {
			$data['journal_description'] = $this->request->post['journal_description'];
		} elseif (isset($this->request->get['journal_id'])) {
			$data['journal_description'] = $this->model_journal_journal->getJournalDescriptions($this->request->get['journal_id']);
		} else {
			$data['journal_description'] = array();
		}

		$key_list = array(
			'keyword'				=> '',
			'link'					=> '',
			'status'				=> 1,
			'master_id'			=> '',
			'sort_order'		=> '',
			'image'					=> '',
			'image_show'		=> 1,
			'video'					=> '',
			'master_old'		=> 0,
			'author_id'			=> '',
			'author_exp'		=> '',
		);

		foreach($key_list as $key=>$default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (isset($journal_info[$key])) {
				$data[$key] = $journal_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		$data['author'] = array();
		$this->load->model('visitor/visitor');

		if($data['author_id']) {
			$author_info = $this->model_visitor_visitor->getVisitor($data['author_id']);
			if($author_info) {
				$data['author'] = array(
					'author_id'	=> $author_info['visitor_id'],
					'name'			=> $author_info['name'],
					'exp'				=> $author_info['exp'],
					'exp_list'	=> $this->model_visitor_visitor->getExpByVisitor($data['author_id'])

				);
			}
		}

		$data['experts'] = array();
		if(!empty($this->request->post['experts'])) {
			$experts = $this->request->post['experts'];
		}else if(isset($journal_info)) {
			$experts = $this->model_journal_journal->getJournalExperts($this->request->get['journal_id']);
		}else{
			$experts = array();
		}

		foreach($experts as $expert) {
			$author_info = $this->model_visitor_visitor->getVisitor($expert['author_id']);
			$exp_list = $this->model_visitor_visitor->getExpByVisitor($expert['author_id']);
			$exp_default = !empty($exp_list) ? $exp_list[0]['exp_id'] : 0;

			if(isset($expert['exp_id'])) {
				$exp_id = $expert['exp_id'];
			}else if(isset($expert['author_exp'])) {
				$exp_id = $expert['author_exp'];
			}else{
				$exp_id = $exp_default;
			}

			if($author_info) {
				$data['experts'][] = array(
					'author_id'	=> $author_info['visitor_id'],
					'name'			=> $author_info['name'],
					'exp_id'		=> $exp_id,
					'exp_list'	=> $exp_list

				);
			}
		}




		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($journal_info) && is_file(DIR_IMAGE . $journal_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($journal_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['date_available'])) {
			$data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($journal_info)) {
			$data['date_available'] = ($journal_info['date_available'] != '0000-00-00') ? $journal_info['date_available'] : '';
		} else {
			$data['date_available'] = date('Y-m-d H:i:s');
		}

		if (isset($this->request->post['tags'])) {
			$data['tags'] = $this->request->post['tags'];
		} elseif (isset($this->request->get['journal_id'])) {
			$data['tags'] = $this->model_journal_journal->getJournalTags($this->request->get['journal_id']);
		} else {
			$data['tags'] = array();
		}

		// galleries
		$gall_keys = array(1,2,3,4,5);
		foreach($gall_keys as $key) {
			$data['gallery_' . $key] = array();
			if (isset($this->request->post['gallery_' . $key])) {
				$gallery = $this->request->post['gallery_' . $key];
			}else{
				$gallery = $this->model_journal_journal->getGallery($journal_id, $key);
			}
			if($gallery) {

				foreach($gallery as $image) {
					if ($image && is_file(DIR_IMAGE . $image)) {
						$thumb = $this->model_tool_image->resize($image, 100, 100);
					} else {
						$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
					}

					$data['gallery_' . $key][] = array(
						'thumb'	=> $thumb,
						'image'	=> $image,
					);
				}
				
			}
		}

		// column
		$data['column_list'] = array();

		if (isset($this->request->post['column'])) {
			$columns = $this->request->post['column'];
		}else{
			$columns = $this->model_journal_journal->getColumn($journal_id);
		}

		if($columns) {
			foreach($columns as $column) {
				$data['column_list'][] = array(
					'column_id'		=> $column['column_id'],
					'text_left'		=> $column['text_left'],
					'text_right'	=> $column['text_right'],
					'size'				=> json_decode($column['size'], true)
				);
			}
		}


		// Case
		$data['case'] = array();

		if (isset($this->request->post['case'])) {
			$case = $this->request->post['case'];
		}else{
			$case = $this->model_journal_journal->getCase($journal_id);
		}
		if($case) {
			if (isset($case['logo']) && is_file(DIR_IMAGE . $case['logo'])) {
				$case['thumb'] = $this->model_tool_image->resize($case['logo'], 100, 100);
			} else {
				$case['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			}

			$data['case'] = $case;
		}else{
			$data['case'] = array(
				'title'						=> '',
				'description'			=> '',
				'logo'						=> '',
				'thumb'						=> $this->model_tool_image->resize('no_image.png', 100, 100),
				'template'				=> 0,
				'bottom'					=> 0,
				'attr'						=> array()
			);
		}

		// copies
		$data['copies'] = $this->model_journal_journal->getCopies($journal_id);

		// master
		$this->load->model('master/master');
		$filter_data = array(
			'filter_actual' => true
		);
		$data['master_list'] = $this->model_master_master->getMasters($filter_data);


		if (isset($this->request->post['journal_layout'])) {
			$data['journal_layout'] = $this->request->post['journal_layout'];
		} elseif (isset($this->request->get['journal_id'])) {
			$data['journal_layout'] = $this->model_journal_journal->getJournalLayouts($this->request->get['journal_id']);
		} else {
			$data['journal_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('journal/journal_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'journal/journal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['journal_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 128)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			/*if (utf8_strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}*/
		}

		if(empty($this->request->post['keyword'])){
			$a_data = array(
				'name'    => $this->request->post['journal_description'][$this->config->get('config_language_id')]['title'],
				'essence' => 'journal',
			);

			$this->request->post['keyword'] = $this->load->controller('extension/module/seo_url_generator/getSeoUrl', $a_data); 
		} else {
			$this->load->model('extension/module/seo_url_generator');
			$this->request->post['keyword'] = $this->model_extension_module_seo_url_generator->translit($this->request->post['keyword']);
		}
											// SEO URL Generator . end

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['journal_id']) && $url_alias_info['query'] != 'journal_id=' . $this->request->get['journal_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['journal_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		// case title
		if($this->request->post['type'] === 'case') {
			if (empty($this->request->post['case']['title'])) {
				$this->error['case'] = $this->language->get('error_case');
			}
		}

		// special link
		if($this->request->post['type'] === 'special') {
			if (utf8_strlen($this->request->post['link']) < 3) {
				$this->error['link'] = $this->language->get('error_link');
			}
		}

		if($this->request->post['type'] === 'opinion') {
			if(isset($this->request->post['author_id'])) {
				$this->load->model('visitor/visitor');
				$author_info = $this->model_visitor_visitor->getVisitor($this->request->post['author_id']);
				if(!$author_info) {
					$this->error['author'] = $this->language->get('error_author');
				}
			}else{
				$this->error['author'] = $this->language->get('error_author');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'journal/journal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_type'])) {
				$filter_type = $this->request->get['filter_type'];
			} else {
				$filter_type = '';
			}

			$this->load->model('journal/journal');

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_type' => $filter_type,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_journal_journal->getJournals($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'journal_id'      => $result['journal_id'],
					'name'            => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}