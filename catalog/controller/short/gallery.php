<?php 

if(isset($this->request->get['journal_id']) && !empty($gallery_id)) {
	$journal_id = $this->request->get['journal_id'];

	$this->load->model('journal/journal');
	$gallery = $this->model_journal_journal->getJournalGallery($journal_id, $gallery_id);
	if($gallery) {
		$this->load->model('themeset/themeset');

		$images = array();

		foreach($gallery as $image) {
			if ($image && is_file(DIR_IMAGE . $image)) {
				$images[] = array(
					'image'	=> $this->model_themeset_themeset->resize($image, 855, 425),
					'thumb'	=> $this->model_themeset_themeset->resize($image, 140, 78),
				);
			}
		}

		if($images) {
			$html .= '<div class="asl__cont">';
			$html .= '<div class="asl__main">';
			foreach($images as $image) {
				$html .= '<div class="asl__main_slide"><img src="'.$image['image'].'" alt=""></div>';
			}
			$html .= '</div>';
			$html .= '<div class="asl__thumb">';
			foreach($images as $image) {
				$html .= '<div class="asl__thumb_slide"><img src="'.$image['thumb'].'" alt=""></div>';
			}
			$html .= '</div>';
			$html .= '</div>';
		}

	}
}

?>