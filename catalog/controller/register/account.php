<?php
class ControllerRegisterAccount extends Controller {

	public function index() {

		$expert_id = $this->visitor->getId();
        $contact_id = $this->visitor->getB24id();

//        if ($contact_id == 67593 || $contact_id == 59759) {
//            $this->visitor->logout();
//            header("Location: " . $this->url->link('register/login'));
//            exit();
//        }

		if($expert_id) {
			return $this->load->controller('expert/expert');
		}else{
			header("Location: " . $this->url->link('register/login'));
			exit();
		}

	}

}
