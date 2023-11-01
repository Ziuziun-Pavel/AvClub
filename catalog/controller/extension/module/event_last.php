<?php
class ControllerExtensionModuleEventLast extends Controller {
	public function index($setting) {

		$this->load->model('avevent/event');

		$data['title'] = $setting['title'];
		$data['events'] = array();

		$sort = array();

		$filter_data = array();

		if($setting['type_id']) {
			$filter_data['filter_type_id'] = $setting['type_id'];
		}

		$events = $this->model_avevent_event->getEventsLast($filter_data);

		$years = array();

		if($events) {
			foreach($events as $event) {
				$date_start = strtotime($event['date']);
				$date_stop = strtotime($event['date_stop']);

				$year = date('Y', $date_start);

				$data['events'][$year]['year'] = $year;
				$data['events'][$year]['events'][] = array(
					'event_id'		=> $event['event_id'],
					'year'				=> $year,
					'date'				=> $event['date'],
					'date_start'	=> date('d.m', $date_start),
					'date_stop'		=> date('d.m', $date_stop),
					'stop_show'		=> $date_stop > $date_start ? true : false,
					'type'				=> $event['type'],
					'city'				=> $event['city'],
					'old_type'		=> $event['old_type'],
					'old_link'		=> $event['old_link'],
					'href'        => $this->url->link('avevent/event/info', 'event_id=' . $event['event_id'])
				);

				$sort[$year] = $year;
			}
			array_multisort($sort, SORT_DESC, $data['events']);
		}

//        var_dump($data['events']);
//        die();
		if($data['events']) {
			return $this->load->view('extension/module/event_last', $data);
		}
	}
}
