<?php
class ControllerCommonFixed extends Controller {
	public function index() {

		$data = array();

		require(DIR_APPLICATION . 'controller/common/fixed-menu.php');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$data['og_url'] = (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_SERVER : HTTP_SERVER) . substr($this->request->server['REQUEST_URI'], 1, (strlen($this->request->server['REQUEST_URI'])-1));

		$data['home'] = $this->url->link('common/home');

		$data['wishlist_active'] = $this->wishlist->count() ? true : false;
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);

		$type = 'header';

		if(isset($this->request->get['journal_id'])) {
			$this->load->model('journal/journal');
			$journal_info = $this->model_journal_journal->getJournal($this->request->get['journal_id']);
			if($journal_info) {
				if ($journal_info['meta_h1']) {
					$data['heading_title'] = $journal_info['meta_h1'];
				} else {
					$data['heading_title'] = $journal_info['title'];
				}
				$type = 'journal';
			}
			$data['journal_id'] = $this->request->get['journal_id'];
		}

		if(isset($this->request->get['event_id'])) {
			$this->load->model('avevent/event');
			$event_info = $this->model_avevent_event->getEvent($this->request->get['event_id']);
			if($event_info) {

				$month_list = array(
					1 	=> 'января',
					2 	=> 'февраля',
					3 	=> 'марта',
					4 	=> 'апреля',
					5 	=> 'мая',
					6 	=> 'июня',
					7 	=> 'июля',
					8 	=> 'августа',
					9 	=> 'сентября',
					10 	=> 'октября',
					11 	=> 'ноября',
					12 	=> 'декабря'
				);

				$data['all_events'] = $this->url->link('avevent/event');
				
				$data['event_id'] = $event_info['event_id'];
				$data['address_full'] = $event_info['address_full'];
				$data['type'] = $event_info['type'];
				$data['city'] = $event_info['city'];
				$data['link'] = $event_info['link'];

				$date = strtotime($event_info['date']);
				$data['date'] = date('d', $date) . '&nbsp;' . $month_list[(int)date('m', $date)] . '&nbsp;' . date('Y', $date);

				$time_start = strtotime($event_info['time_start']);
				$time_end = strtotime($event_info['time_end']);

				$data['time_start'] = date('H:s', $time_start);
				$data['time_end'] = date('H:s', $time_end);

				$type = 'event';
			}
		}

		
		$data['login'] = $this->url->link('register/login', '', true);
		$data['account'] = $this->url->link('register/account', '', true);

		$data['logged'] = $this->visitor->isLogged();
		
		if($type === 'journal') {
			return $this->load->view('common/fixed_journal', $data);
		}else if($type === 'event') {
			return $this->load->view('common/fixed_event', $data);
		}else{
			return $this->load->view('common/fixed_header', $data);
		}

	}


	protected function getJournal() {
		$data = array();
		
		$this->response->setOutput($this->load->view('common/fixed_header', $data));
	}

	protected function getEvent() {
		$data = array();
		
		$this->response->setOutput($this->load->view('common/fixed_header', $data));
	}
}