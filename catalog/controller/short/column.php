<?php 
// $html = '';
if(isset($this->request->get['journal_id']) && !empty($id)) {
	$this->load->model('journal/journal');

	$journal_id = $this->request->get['journal_id'];

	$column = $this->model_journal_journal->getColumnById($journal_id, (int)$id);

	$size = json_decode($column['size'], true);

	if($column) {
		$html .= '<div class="jcol__cont row">';
		$html .= '	<div class="jcol__col col-' . $size['xs']['left']. ' col-sm-' . $size['sm']['left']. ' col-md-' . $size['md']['left']. ' col-lg-' . $size['lg']['left']. ' col-xl-' . $size['xl']['left']. ' col-xlg-' . $size['xlg']['left']. '">' . html_entity_decode($column['text_left']) . '</div>';
		$html .= '	<div class="jcol__col col-' . $size['xs']['right']. ' col-sm-' . $size['sm']['right']. ' col-md-' . $size['md']['right']. ' col-lg-' . $size['lg']['right']. ' col-xl-' . $size['xl']['right']. ' col-xlg-' . $size['xlg']['right']. '">' . html_entity_decode($column['text_right']) . '</div>';
		$html .= '</div>';
	}

}

// return html_entity_decode($html);

?>