<?php
class ControllerRegisterTest extends Controller {

	public function getId() {
		echo $this->visitor->isLogged();
	}
	public function index() {
		$time_start = microtime(true);

		ini_set('max_execution_time', 360);
	
		$this->load->model('register/register');

		$b24id = !empty($this->request->get['b24id']) ? $this->request->get['b24id'] : 0;		

		$event_list = $this->model_register_register->getVisitList($b24id);

		echo '<pre>';
		print_r($event_list);
		echo '</pre>';

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

}
