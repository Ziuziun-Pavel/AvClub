<?php
class ControllerExtensionModuleJournalCase extends Controller {
	public function index() {
		
		$this->load->model('journal/journal');
		$this->load->model('themeset/themeset');
        $this->load->model('company/company');

		$data['heading_title'] = $this->config->get('journal_case_title');

		$data['all_case'] = $this->url->link('journal/case');

		$wish_list = $this->wishlist->getKeyList();

		$data['journals'] = array();

		$size_width = $this->config->get('av_size_case_width');
		$size_height = $this->config->get('av_size_case_height');

		$filter_data = array(
			'filter_type' 		=> 'case',
			'start'           => 0,
			'limit'           => 2
		);

		$results = $this->model_journal_journal->getJournals($filter_data);

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_themeset_themeset->resize($result['image'], $size_width, $size_height);
			} else {
				$image = $this->model_themeset_themeset->resize('placeholder.png', $size_width, $size_height);
			}

			$case = $this->model_journal_journal->getCase($result['journal_id']);
            $company_info = $this->model_company_company->getCompany($case['company_id']);

			$data['journals'][] = array(
				'journal_id' 	 	=> $result['journal_id'],
				'type' 	 				=> $result['type'],
                'company_id' => $company_info['company_id'],
                'company_title' => $company_info['title'],
				'case' 	 				=> $case,
				'thumb'     	  => $image,
				'title'       	=> $result['title'],
				'wish'					=> ($wish_list && in_array($result['journal_id'], $wish_list)) ? true : false,
				'preview'       => $result['preview'],
				'href'        	=> $this->url->link('journal/case/info', 'journal_id=' . $result['journal_id'])
			);
		}

		if(!$data['journals']) {return false;}
		return $this->load->view('extension/module/journal_case', $data);
	}
}
