<?php
class ControllerCommonTag extends Controller {
	public function index() {

		$data['link'] = $this->url->link('tag/tag');

		$this->load->model('tag/tag');

		$data['tags'] = array();

		$tags = $this->config->get('themeset_tags');
		$results = array();

		if($tags) {
			foreach($tags as $tag_id) {
				$tag_info = $this->model_tag_tag->getTag($tag_id);
				if($tag_info) {
					$results[] = array(
						'tag_id'	=> $tag_info['tag_id'],
						'title'		=> $tag_info['title']
					);
				}
			}
		}else{
			$filter_data = array(
				'start'	=> 0,
				'limit'	=> 32
			);

			$results = $this->model_tag_tag->getTags($filter_data);
		}

		foreach($results as $result) {
			$data['tags'][] = array(
				'tag_id'	=> $result['tag_id'],
				'title'		=> $result['title'],
				'href'		=> $this->url->link('tag/tag/info', 'tag_id=' . $result['tag_id']),

			);
		}

		return $this->load->view('common/tag', $data);
	}
}