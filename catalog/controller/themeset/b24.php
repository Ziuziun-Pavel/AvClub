<?php
class ControllerThemesetb24 extends Controller {

	
	private $error = array();


	public function index($company_id = 0) {
		return true;
	}
	public function install($company_id = 0) {
		require_once(DIR_SYSTEM . 'library/crest/crest.php');

		$result = CRest::installApp();
		return true;
	}




}
