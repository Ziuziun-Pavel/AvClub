<?php
class ControllerCompanyAdd extends Controller {
    
    public function index() {

        $this->load->model('company/company');

        $this->load->model('tool/image');
        $this->load->model('themeset/themeset');

        $meta_info = $this->config->get('av_company');


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
            'href' => $this->url->link('company/company')
        );


        $this->document->setTitle($meta_info['meta_title']);
        $this->document->setDescription($meta_info['meta_description']);
        $this->document->setKeywords($meta_info['meta_keyword']);

        $data['heading_title'] = $meta_info['meta_h1'];

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


        $data['continue'] = $this->url->link('common/home');

        $data['company_list'] = $this->load->controller('company/company/list');
        $data['filter'] = $this->load->controller('company/filter');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('company/company_category', $data));

    }

}
