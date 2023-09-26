<?php
class ControllerCompanyCompany extends Controller {
	private $limit = 31;

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

	public function category() {

		$this->load->model('company/company');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		$meta_info = $this->config->get('av_company');

		if (isset($this->request->get['company_category_id'])) {
			$category_id = $this->request->get['company_category_id'];
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_company_company->getCategory($category_id);

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

		if($category_info) {

			$data['breadcrumbs'][] = array(
				'text' => $category_info['title'],
				'href' => $this->url->link('company/company/category', 'category_id=' . $category_id)
			);

		}else{
			$category_info = $meta_info;
		}

		$this->document->setTitle($category_info['meta_title']);
		$this->document->setDescription($category_info['meta_description']);
		$this->document->setKeywords($category_info['meta_keyword']);

		$data['heading_title'] = $category_info['meta_h1'];



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

	public function list($json = false) {

		$this->load->model('company/company');

		$this->load->model('tool/image');
		$this->load->model('themeset/themeset');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['company_category_id'])) {
			$filter_category_id = $this->request->get['company_category_id'];
		} else {
			$filter_category_id = '';
		}

		if (isset($this->request->get['company'])) {
			$filter_company = $this->request->get['company'];
		} else {
			$filter_company = '';
		}

		if (isset($this->request->get['type'])) {
			$filter_tag = $this->request->get['type'];
		} else {
			$filter_tag = '';
		}

		if (isset($this->request->get['brand'])) {
			$filter_brand = $this->request->get['brand'];
		} else {
			$filter_brand = '';
		}

		if (isset($this->request->get['branch'])) {
			$filter_branches = $this->request->get['branch'];
		} else {
			$filter_branches = array();
		}

		if (isset($this->request->get['form']) && $this->request->get['form'] === 'filter') {
			$json = true;
		}

		

		// result
		$data['companies'] = array();

		$filter_data = array(
			'filter_category'			=> $filter_category_id,
			'filter_tag'					=> $filter_tag,
			'filter_company'			=> $filter_company,
			'filter_brand'				=> $filter_brand,
			'filter_branches'			=> $filter_branches,
			'start'           		=> ($page - 1) * $this->limit,
			'limit'           		=> $this->limit,
			'words'								=> explode(' ', trim(preg_replace('/\s+/', ' ', $filter_company)))
		);


		$company_total = $this->model_company_company->getTotalCompanies($filter_data);

		$results = $this->model_company_company->getCompanies($filter_data);

		foreach ($results as $result) {
			$image = $this->model_tool_image->resize($result['image'], 168, 73);

			$tags = $this->model_company_company->getTagsByCompany($result['company_id']);

			$data['companies'][] = array(
				'company_id' 	 	=> $result['company_id'],
				'thumb'     	  => $image,
				'title'       	=> $result['title'],
				'preview'       => mb_strlen($result['description']) <= 80 ? $result['description'] : utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 80) . '...',
				'branches'      => $result['branches'],
				'tags'      		=> $tags,
				'href'        	=> $this->url->link('company/info', 'company_id=' . $result['company_id'])
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

		$pagination = new Pagination();
		$pagination->total = $company_total;
		$pagination->page = $page;
		$pagination->limit = $this->limit;
		$pagination->url = $this->url->link('company/company', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		if($json) {
			$data['filter_data'] = $filter_data;
			$data['template'] = $this->load->view('company/company_list', $data);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}else{
			return $this->load->view('company/company_list', $data);
		}

	}

	public function autocomplete() {
		$json = array();

		$filter_disabled = 0;

		if (!empty($this->request->get['filter_disabled'])) {
			$filter_disabled = (int)$this->request->get['filter_disabled'];
		}

		$results = array();

		$this->load->model('company/company');
		if (isset($this->request->get['filter_company'])) {
			// COMPANY
			$filter_name = $this->request->get['filter_company'];
			$filter_type = !empty($this->request->get['filter_type']) ? $this->request->get['filter_type'] : '';
			$filter_category = !empty($this->request->get['filter_category_id']) ? $this->request->get['filter_category_id'] : '';
			$filter_limit = !empty($this->request->get['filter_limit']) ? (int)$this->request->get['filter_limit'] : 5; 

			$filter_data = array(
				'filter_category'  	=> $filter_category,
				'filter_name'  			=> $filter_name,
				'filter_tag'  			=> $filter_type,
				'filter_disabled'  	=> $filter_disabled,
				'start'        			=> 0,
				'limit'       			=> $filter_limit
			);
			$results = $this->model_company_company->getCompanies($filter_data);
			foreach ($results as $result) {
				$json[] = array(
					'id'  				 	=> $result['company_id'],
					'b24id'  				=> !empty($result['b24id']) ? $result['b24id'] : 0,
					'title'    			=> $result['title']
				);
			}
		} elseif (isset($this->request->get['filter_type'])) {
			// TYPE
			$filter_title = $this->request->get['filter_type'];
			$filter_category = !empty($this->request->get['filter_category_id']) ? $this->request->get['filter_category_id'] : '';
			$filter_data = array(
				'filter_category'  	=> $filter_category,
				'filter_title'  		=> $filter_title,
				'start'       			=> 0,
				'limit'       			=> 5
			);
			$results = $this->model_company_company->getTags($filter_data);
			foreach ($results as $result) {
				$json[] = array(
					'id'   		=> $result['tag_id'],
					'title'   => $result['title']
				);
			}

		}elseif (isset($this->request->get['filter_brand'])) {
			// BRANDS
			$filter_title = $this->request->get['filter_brand'];

			$filter_data = array(
				'filter_title'  => $filter_title,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_company_company->getBrands($filter_data);
			
			foreach ($results as $result) {
				$json[] = array(
					'id'   					=> $result['brand_id'],
					'title'    			=> $result['title']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}



}
