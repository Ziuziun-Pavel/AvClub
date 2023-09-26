<?php 

if(isset($this->request->get['journal_id'])) {
	$journal_id = $this->request->get['journal_id'];

	$this->load->model('journal/journal');
	$journal_info = $this->model_journal_journal->getJournal($journal_id);
	if($journal_info && $journal_info['master_id']) {

		$this->load->model('master/master');
		$this->load->model('themeset/themeset');

		$master_info = $this->model_master_master->getMaster($journal_info['master_id']);
		if($master_info) {
			$html .= '<div class="amaster__cont ">';
			$html .= '	<div class="amaster__title title">АКТУАЛЬНОЕ ОНЛАЙН-СОБЫТИЕ</div>';
			$html .= '	<div class="master__item">';
			$html .= '		<div class="master__img">';
			$html .= '			<div class="master__image">';
			/*if(!empty($master_info['logo'])) {
				$html .= '				<img src="'.$this->model_themeset_themeset->resize_crop($master_info['logo']).'" alt="" class="master__logo">';
			}*/
			$html .= '				<img src="'.$this->model_themeset_themeset->resize_crop($master_info['image']).'" alt="">';
			$html .= '			</div>';
			$html .= '			<a href="' . $master_info['link'] . '" class="btn btn-red master__reg" target="_blank"><span>Зарегистрироваться</span></a>';
			$html .= '		</div>';
			$html .= '		<div class="master__data">';
			$html .= '			<div class="master__date date">'.$master_info['date'].' <span>'.$master_info['time'].'</span></div>';
			$html .= '			<div class="master__name">'.$master_info['title'].'</div>';
			$html .= '			<div class="master__preview">';
			$html .= '				<p><strong>В программе:</strong></p>';
			$html .=  				html_entity_decode($master_info['preview']);
			$html .= '			</div>';
			/*
			$html .= '			<div class="master__more"><a href="' . $master_info['link'] . '" target="_blank"><svg class="ico ico-center"><use xlink:href="#dots" /></svg></a></div>';
			*/
			$html .= '			<div class="master__author">';
			$html .= '				<p><strong>'.$master_info['author'].'</strong></p>';
			$html .= '				<p>'.$master_info['exp'].'</p>';
			$html .= '			</div>';
			$html .= '		</div>';
			$html .= '	</div>';
			$html .= '</div>';
		}

	}
}

?>