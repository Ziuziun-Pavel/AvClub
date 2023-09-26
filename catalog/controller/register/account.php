<?php
class ControllerRegisterAccount extends Controller {

	public function index() {

		$expert_id = $this->visitor->getId();

		if($expert_id) {
			return $this->load->controller('expert/expert');
		}else{
			header("Location: " . $this->url->link('register/login'));
			exit();
		}

	}

}
