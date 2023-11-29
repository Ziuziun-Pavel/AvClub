<?php
class ControllerMasterMaster extends Controller {
	public function index() {
		// $this->load->language('product/category');

		$this->load->model('master/master');
		$this->load->model('journal/journal');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		$meta_info = $this->config->get('av_master');

		$data['master_info'] = array(
			'title'					=> $meta_info['master_title'],
			'description'		=> $meta_info['master_description'],
			'link'					=> $meta_info['master_link'],
			'button'				=> $meta_info['master_button'],
		);


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('master_limit') ? $this->config->get('master_limit') : $this->config->get('journal_news_limit');


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $meta_info['meta_h1'],
			'href' => $this->url->link('master/master')
		);


		$this->document->setTitle($meta_info['meta_title']);
		$this->document->setDescription($meta_info['meta_description']);
		$this->document->setKeywords($meta_info['meta_keyword']);

		$data['heading_title'] = $meta_info['meta_h1'];


		// masters
		$data['masters'] = array();

		$results = $this->model_master_master->getMasters();

		$size_width = $this->config->get('av_size_master_width');
		$size_height = $this->config->get('av_size_master_height');

		$types = array(
			'master'	=> 0,
			'meetup'	=> 0
		);

		foreach ($results as $result) {

			$types[$result['type']] += 1;

			if ($result['image']) {
				$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height);
			} else {
				$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
			}
			if ($result['logo']) {
				$logo = $this->model_themeset_themeset->resize_crop($result['logo']);
			} else {
				$logo = '';
			}

			$data['masters'][] = array(
				'master_id' 	 	=> $result['master_id'],
				'image'     	  => $image,
				'logo'     	  	=> $logo,
				'title'       	=> $result['title'],
				'preview'       => $result['preview'],
				'date'       		=> $result['date'],
				'time'       		=> $result['time'],
				'author'       	=> $result['author'],
				'exp'       		=> $result['exp'],
				'href'        	=> $result['link'],
                'href_to_landing'        	=> $this->url->link('master/master/info', 'master_id=' . $result['master_id']),
                'type'        	=> $result['type'],
			);
		}

		$data['types'] = array(
			''	=> 'Все события'
		);
		if($types) {
			foreach($types as $type=>$qty) {
				if(!$qty) {continue;}
				switch($type) {
					case 'meetup': $name = 'Митапы';break;
					default: $name = 'Мастер-классы';
				}
				$data['types'][$type] = $name;
			}
		}


		// journals
		$data['journals'] = array();

		$filter_data = array(
			'filter_type' 		=> 'video',
			'filter_master' 	=> true,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);

		$journal_total = $this->model_journal_journal->getTotalJournals($filter_data);

		$wish_list = $this->wishlist->getKeyList();

		$results = $this->model_journal_journal->getJournals($filter_data);

		$size_width = $this->config->get('journal_size_news_width');
		$size_height = $this->config->get('journal_size_news_height');

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height, '', 'left');
			} else {
				$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
			}

			$data['journals'][] = array(
				'journal_id' 	 	=> $result['journal_id'],
				'type'       		=> $result['type'],
				'thumb'     	  => $image,
				'title'       	=> $result['title'],
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
			'status'	=> $this->config->get('journal_telegram_status'),
			'link'	=> $this->config->get('journal_telegram_link'),
			'text'	=> $this->config->get('journal_telegram_text'),
		);

		$pagination = new Pagination();
		$pagination->total = $journal_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('master/master', 'page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($journal_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($journal_total - $limit)) ? $journal_total : ((($page - 1) * $limit) + $limit), $journal_total, ceil($journal_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
			$this->document->addLink($this->url->link('master/master', '', true), 'canonical');
		} elseif ($page == 2) {
			$this->document->addLink($this->url->link('master/master', '', true), 'prev');
		} else {
			$this->document->addLink($this->url->link('master/master', 'page='. ($page - 1), true), 'prev');
		}

		if ($limit && ceil($journal_total / $limit) > $page) {
			$this->document->addLink($this->url->link('master/master', 'page='. ($page + 1), true), 'next');
		}


		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('master/master', $data));

	}

    public function info() {
        $this->load->language('master/master');

        $this->load->model('master/master');

        $this->load->model('themeset/themeset');
        $this->load->model('tool/image');

        if (isset($this->request->get['master_id'])) {
            $master_id = (int)$this->request->get['master_id'];
        } else {
            $master_id = 0;
        }

        $meta_info = $this->config->get('av_master');

        $data['master_id'] = $master_id;

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $meta_info['bread'],
            'href' => $this->url->link('master/master')
        );

        $master_info = $this->model_master_master->getMaster($master_id);

        if ($master_info) {
            $this->document->setTitle($master_info['title']);

            $data['breadcrumbs'][] = array(
                'text' => $master_info['title'],
                'href' => $this->url->link('master/master/info', 'master_id=' . $this->request->get['master_id'])
            );

            if ($master_info['meta_title']) {
                $this->document->setTitle($master_info['meta_title']);
            } else {
                $this->document->setTitle($master_info['title']);
            }

            $this->document->setDescription($master_info['meta_description']);
            $this->document->setKeywords($master_info['meta_keyword']);

            if ($master_info['meta_h1']) {
                $data['heading_title'] = $master_info['meta_h1'];
            } else {
                $data['heading_title'] = $master_info['title'];
            }

            if ($master_info['image'] && is_file(DIR_IMAGE . $master_info['image'])) {
                $data['image'] = $this->model_themeset_themeset->resize_crop($master_info['image']);
                $this->document->setOgImage($data['image']);
            } else {
                $data['image'] = '';
            }

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

            $data['author'] = $master_info['author'];

            $data['link'] = $master_info['link'];
            $data['master_id'] = $master_info['master_id'];
            $data['type'] = $master_info['type'];

            $date = strtotime($master_info['date']);
            $data['date'] = date('d', $date) . '&nbsp;' . $month_list[(int)date('m', $date)] . '&nbsp;' . date('Y', $date);

            $time_start = strtotime($master_info['time']);
            $time_end = strtotime('+1 hour', $time_start);

            $data['time_start'] = date('H:s', $time_start);
            $data['time_end'] = date('H:s', $time_end);

            $data['description'] = html_entity_decode($master_info['description'], ENT_QUOTES, 'UTF-8');
            $data['preview'] = html_entity_decode($master_info['preview'], ENT_QUOTES, 'UTF-8');

            $data['brand_list'] = array();

            $brand_list = $this->model_master_master->getCompaniesByMaster($master_id);

            if($brand_list) {
                $sort_brand = array();
                foreach($brand_list as $brand) {
                    $data['brand_list'][] = array(
                        'company_id'	=> $brand['company_id'],
                        'title'				=> $brand['title'],
                        'activity'				=> $brand['activity'],
                        'description'				=> $brand['description'],
                        'image'				=> $this->model_tool_image->resize($brand['image'], 214, 100),
                        'href'				=> !empty($brand['status']) ? $this->url->link('company/info', 'company_id=' . $brand['company_id']) : '',
                    );
                    $sort_brand[] = $brand['title'];
                }
                array_multisort($sort_brand, SORT_ASC, $data['brand_list']);
            }

            $expert_list = $this->model_master_master->getAuthorsByMaster($master_id);
            $speakers = $this->model_master_master->getSpeakerByMaster($master_id);



            if($speakers[0]["author_id"]) {
                foreach($speakers as $speaker) {
                    $data['speaker_list'][] = array(
                        'author_id'		=> $speaker['author_id'],
                        'name'				=> $speaker['name'],
                        'exp'					=> $speaker['exp'],
                        'moderator'					=> true,
                        'image'				=> $this->model_themeset_themeset->resize($speaker['image'], 220, 220),
                        'href'				=> !empty($speaker['expert']) ? $this->url->link('expert/expert', 'expert_id=' . $speaker['author_id']) : '',
                    );
                }
            }

            if($speakers[0]["author_id"]) {
                foreach($expert_list as $speaker) {
                    $data['speaker_list'][] = array(
                        'author_id'		=> $speaker['author_id'],
                        'name'				=> $speaker['name'],
                        'exp'					=> $speaker['exp'],
                        'moderator'					=> false,
                        'image'				=> $this->model_themeset_themeset->resize($speaker['image'], 220, 220),
                        'href'				=> !empty($speaker['expert']) ? $this->url->link('expert/expert', 'expert_id=' . $speaker['author_id']) : '',
                    );
                }
            }
            $data['page_type'] = 'webinar';

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            $this->response->setOutput($this->load->view('master/master_info', $data));
        } else {
            $url = '';

            if (isset($this->request->get['master_id'])) {
                $url .= '&master_id=' . $this->request->get['master_id'];
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
                'href' => $this->url->link('master/master/info', $url)
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
