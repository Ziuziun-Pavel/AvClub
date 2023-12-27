<?php

public function getFutureEvents()
{
    sleep(3);
    $return = array();
    $data = array();

    session_write_close();

    $this->load->model('register/register');
    $this->load->model('visitor/expert');

    if (isset($this->request->get['expert_id'])) {
        $expert_id = (int)$this->request->get['expert_id'];
    } else {
        $expert_id = 0;
    }

    $expert_info = $this->model_visitor_expert->getExpert($expert_id, 0, false);

    if ($expert_info && $this->visitor->getId() && $this->visitor->getId() == $expert_id) {
        $month_list = array(
            1 => 'января',
            2 => 'февраля',
            3 => 'марта',
            4 => 'апреля',
            5 => 'мая',
            6 => 'июня',
            7 => 'июля',
            8 => 'августа',
            9 => 'сентября',
            10 => 'октября',
            11 => 'ноября',
            12 => 'декабря'
        );

        $now = strtotime("now");

        $event_list = $this->model_register_register->getFutureEvents();

        $sort_forum = array();
        if ($event_list) {
            foreach ($event_list as $event_item) {
                $time = strtotime($event_item['date']);

                $statuses = array();

                $statuses[] = array(
                    'text' => 'Заявка на рассмотрении',
                    'active' => true
                );

                if ($event_item['status'] === 'consideration') {
                    $statuses[] = array('text' => 'Участие одобрено', 'active' => false);
                }

                if ($event_item['status'] === 'paid_participation') {
                    $statuses[] = array('text' => 'Предложено платное участие', 'active' => false);
                }

                if (in_array($event_item['status'], array('admitted', 'visited', 'noVisited'))) {
                    $statuses[] = array('text' => 'Участие одобрено', 'active' => true);
                }

                if ($event_item['status'] === 'visited') {
                    $statuses[] = array('text' => 'Успешный визит', 'active' => true);
                }

                if ($event_item['status'] === 'noVisited') {
                    $statuses[] = array('text' => 'Визит не состоялся', 'active' => true);
                }

                foreach ($statuses as $key => &$status) {
                    if ($key == count($statuses) - 2 && !$statuses[count($statuses) - 1]['active']) {
                        $status['preactive'] = true;
                    } else {
                        $status['preactive'] = false;
                    }
                }

                $addresses = array();
                if (!empty($event_item['location'])) {
                    $addresses[] = $event_item['location'];
                }
                if (!empty($event_item['address'])) {
                    $addresses[] = $event_item['address'];
                }

                $event_item_info = array(
                    'type_event' => $event_item['type_event'],
                    'type' => $event_item['type'],
                    'name' => $event_item['name'],
                    'status' => $event_item['status'],
                    'old' => false,
                    'statuses' => $statuses,
                    'date' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time)
                );

                switch ($event_item['type_event']) {
                    case 'webinar':
                        $event_item_info['date'] .= ' ' . date('h:i', $time);
                        $event_item_info['url'] = $event_item['url'];
                        $event_item_info['type_text'] = $time < $now ? 'Прошедший вебинар' : 'Вебинар';
                        break;

                    case 'forum':
                        $event_item_info['link'] = $event_item['ticket_public_url'];
                        $event_item_info['summ'] = $event_item['summ'];
                        $event_item_info['location'] = $event_item['location'];
                        $event_item_info['address'] = $event_item['address'];
                        $event_item_info['addresses'] = $addresses;
                        $event_item_info['type_text'] = $time < $now ? 'Прошедшее офлайн-мероприятие' : 'Офлайн-мероприятие';
                }

                $data['event_list'][] = $event_item_info;

                $sort_forum[] = $time;

            } else {
                $return['error'] = true;
            }

            array_multisort($sort_forum, SORT_DESC, $data['event_list']);

            $return['template'] = $this->load->view('expert/expert_future_events', $data);
        }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($return));
}