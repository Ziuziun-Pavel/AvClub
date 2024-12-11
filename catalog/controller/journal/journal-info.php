<?php
$this->load->language('journal/journal');

$this->load->model('journal/journal');
$this->load->model('company/company');

$this->load->model('themeset/themeset');
$this->load->model('themeset/image');
$this->load->model('tool/image');

if (isset($this->request->get['journal_id'])) {
    $journal_id = (int)$this->request->get['journal_id'];
} else {
    $journal_id = 0;
}
switch ($this->request->get['route']) {
    case 'journal/news/info':
        $type = 'news';
        break;
    case 'journal/video/info':
        $type = 'video';
        break;
    case 'journal/opinion/info':
        $type = 'opinion';
        break;
    case 'journal/case/info':
        $type = 'case';
        break;
    case 'journal/special/info':
        $type = 'special';
        break;

    default:
        $type = 'article';
}

$meta_info = $this->config->get('av_meta_' . $type);

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

$data['breadcrumbs'][] = array(
    'text' => $meta_info['bread'],
    'href' => $this->url->link('journal/' . $type)
);

$admin = false;
if (!empty($this->session->data['user_id']) && !empty($this->session->data['token'])) {
    $admin = true;
}

$journal_info = $this->model_journal_journal->getJournal($journal_id, $admin);

if ($journal_info) {
    $this->document->setTitle($journal_info['title']);

    $data['breadcrumbs'][] = array(
        'text' => $journal_info['title'],
        'href' => $this->url->link('journal/' . $type . '/info', 'journal_id=' . $this->request->get['journal_id'])
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

    if ($journal_info['image'] && ($journal_info['image_show'] || ($journal_info['video'] && $journal_info['type'] === 'video'))) {
        $data['thumb'] = $this->model_themeset_themeset->resize_crop($journal_info['image']);
        $this->document->setOgImage($data['thumb']);
    } else {
        $data['thumb'] = '';
    }

    $data['author'] = array();

    if ($journal_info['author_id']) {
        $this->load->model('visitor/visitor');
        $author_info = $this->model_visitor_visitor->getVisitor($journal_info['author_id'], $journal_info['author_exp']);

        if ($author_info) {
            $data['author'] = array(
                'name' => $author_info['name'],
                'exp' => $author_info['exp'],
                'avatar' => $this->model_themeset_themeset->resize($author_info['image'], 220, 220),
                'href' => !empty($author_info['expert']) ? $this->url->link('expert/expert', 'expert_id=' . $author_info['visitor_id']) : '',
            );
        }
    }

    $case = $type === 'case' ? $this->model_journal_journal->getCase($journal_id) : array();
    if ($case) {
        if ($case['logo']) {
            $logo = $this->model_themeset_themeset->resize_crop($case['logo']);
        } else {
            $logo = '';
        }

        if ($case['company_id']) {
            $data['case'] = $case['company_id'];
        } else {
            $data['case'] = null;
        }
        $case['logo'] = $logo;
    }
    $data['case'] = $case;

    $data['type'] = $journal_info['type'];

    if (strpos($journal_info['video'], 'youtube') !== false || strpos($journal_info['video'], 'youtu.be') !== false) {

        $data['video'] = $journal_info['video'];
    } else {
        if (preg_match('/video-(\d+)_(\d+)/', $journal_info['video'], $matches)) {
            $oid = '-' . $matches[1];
            $id = $matches[2];

            $newVideoUrl = "https://vk.com/video_ext.php?oid={$oid}&id={$id}&hd=2&autoplay=0";

            $data['video'] = $newVideoUrl;
        } else {
            $data['video'] = $journal_info['video'];
        }
    }

    $data['copies'] = $this->model_journal_journal->getCopies($journal_id);

    $data['description'] = html_entity_decode($journal_info['description'], ENT_QUOTES, 'UTF-8');

    $wish_list = $this->wishlist->getKeyList();
    $data['wish_active'] = ($wish_list && in_array($journal_id, $wish_list)) ? true : false;

    $month_list = array(
        1 => 'января',
        2 => 'февраля',
        3 => 'марта',
        4 => 'апреля',
        5 => 'мая',
        6 => 'июня',
        7 => 'июля',
        8 => 'августа',
        9 => 'сентября',
        10 => 'октября',
        11 => 'ноября',
        12 => 'декабря'
    );

    $time = strtotime($journal_info['date_available']);

    $data['date'] = date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time);


    $data['experts'] = array();
    $experts = $this->model_journal_journal->getJournalExperts($journal_id);

    $this->load->model('visitor/visitor');

    foreach ($experts as $expert) {
        $author_info = $this->model_visitor_visitor->getVisitor($expert['author_id'], $expert['author_exp']);

        if ($author_info) {

            if ($author_info['image'] && is_file(DIR_IMAGE . $author_info['image'])) {
                $image = $this->model_themeset_image->crop($author_info['image'], 160, 160);

                $data['experts'][] = array(
                    'name' => $author_info['name'],
                    'exp' => $author_info['exp'],
                    'thumb' => $image,
                    'avatar' => $this->model_themeset_themeset->resize($author_info['image'], 220, 220),
                    'href' => !empty($author_info['expert']) ? $this->url->link('expert/expert', 'expert_id=' . $author_info['visitor_id']) : '',
                );
            }
//            else {
//                $image = $this->model_themeset_image->crop('user_no_avatar.png', 160, 160);
//            }


        }
    }

    if ($journal_info && $journal_info['master_id']) {

        $this->load->model('master/master');
        $this->load->model('themeset/themeset');

        $master_info = $this->model_master_master->getMaster($journal_info['master_id']);
        $html = '';
//        if($master_info) {
//            $html .= '<div class="amaster__cont ">';
//            $html .= '	<div class="amaster__title title">АКТУАЛЬНОЕ ОНЛАЙН-СОБЫТИЕ</div>';
//            $html .= '	<div class="master__item">';
//            $html .= '		<div class="master__img">';
//            $html .= '			<div class="master__image">';
//            /*if(!empty($master_info['logo'])) {
//                $html .= '				<img src="'.$this->model_themeset_themeset->resize_crop($master_info['logo']).'" alt="" class="master__logo">';
//            }*/
//            $html .= '				<img src="'.$this->model_themeset_themeset->resize_crop($master_info['image']).'" alt="">';
//            $html .= '			</div>';
//            $html .= '			<a href="' . $master_info['link'] . '" class="btn btn-red master__reg" target="_blank"><span>Зарегистрироваться</span></a>';
//            $html .= '		</div>';
//            $html .= '		<div class="master__data">';
//            $html .= '			<div class="master__date date">'.$master_info['date'].' <span>'.$master_info['time'].'</span></div>';
//            $html .= '			<div class="master__name">'.$master_info['title'].'</div>';
//            $html .= '			<div class="master__preview">';
//            $html .= '				<p><strong>В программе:</strong></p>';
//            $html .=  				html_entity_decode($master_info['preview']);
//            $html .= '			</div>';
//            /*
//            $html .= '			<div class="master__more"><a href="' . $master_info['link'] . '" target="_blank"><svg class="ico ico-center"><use xlink:href="#dots" /></svg></a></div>';
//            */
//            $html .= '			<div class="master__author">';
//            $html .= '				<p><strong>'.$master_info['author'].'</strong></p>';
//            $html .= '				<p>'.$master_info['exp'].'</p>';
//            $html .= '			</div>';
//            $html .= '		</div>';
//            $html .= '	</div>';
//            $html .= '</div>';
//        }

        $data['html'] = $html;

    }

    $company_info = $this->model_company_company->getCompany($case['company_id']);

    $image = $this->model_themeset_themeset->resize($company_info['image'], 168, 73);

    $data['company'] = array(
        'company_id' => $company_info['company_id'],
        'image' => $image,
        'title' => $company_info['title'],
        'preview' => $company_info['description'],
        'href' => $this->url->link('company/info', 'company_id=' . $company_info['company_id'])
    );

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
        'href' => $this->url->link('journal/' . $type . '/info', $url)
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
