<?php
class ControllerExtensionModuleTags extends Controller {
	public function index() {
		$data['tags'] = array();
		$data['companies'] = array();

		$this->load->model('tag/tag');
		$this->load->model('company/company');
		$this->load->model('themeset/image');

		if(isset($this->request->get['journal_id'])) {
			$filter_data = array(
				'journal_id'	=> $this->request->get['journal_id']
			);
			
		}

		if(!empty($filter_data)) {
			$tags = $this->model_tag_tag->getTags($filter_data);
			$tag_ids = array();
			if($tags) {

				foreach($tags as $tag) {
					$data['tags'][] = array(
						'tag_id'	=> $tag['tag_id'],
						'tag'			=> $tag['title'],
						'href'		=> $this->url->link('tag/tag/info', 'tag_id=' . $tag['tag_id'])
					);
					$tag_ids[] = $tag['tag_id'];
				}

				$companies = $this->model_company_company->getCompaniesByTags($tag_ids);

				foreach($companies as $company_id) {
					$company_info = $this->model_company_company->getCompany($company_id, true);

					if(!$company_info) {continue;}

					$image = $this->model_themeset_themeset->resize($company_info['image'], 220, 85);

					$data['companies'][] = array(
						'company_id' 	 	=> $company_info['company_id'],
						'type'					=> 'company',
						'image'     	  => $image,
						'thumb'     	  => $image,
						'title'       	=> $company_info['title'],
						'preview'       => mb_strlen($company_info['description']) <= 80 ? $company_info['description'] : utf8_substr(strip_tags(html_entity_decode($company_info['description'], ENT_QUOTES, 'UTF-8')), 0, 80) . '...',
						'href'        	=> $this->url->link('company/info', 'company_id=' . $company_info['company_id'])
					);
				}

			}
		}
	
		if($data['tags']) {
			return $this->load->view('extension/module/tags', $data);
		}
	}
}
