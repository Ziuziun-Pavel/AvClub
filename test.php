<?php
public function getCatalogList()
{
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

        $catalog_list = $this->model_register_register->getCatalogList($expert_info['b24id']);

        $sort_catalog = array();

        foreach ($catalog_list as $catalog_item) {

            $time = strtotime($catalog_item['date']);

            $statuses = array();

            if ($catalog_item['status'] === 'new') {
                $statuses[] = array(
                    'text' => 'Новая заявка',
                    'active' => true,
                    'preactive' => true
                );
            } else {
                $statuses[] = array(
                    'text' => 'Новая заявка',
                    'active' => false,
                    'preactive' => false
                );
            }

            if ($catalog_item['status'] === 'wait_payment') {
                $statuses[] = array('text' => 'Ожидает оплаты', 'active' => true, 'preactive' => true);
            } else {
                $statuses[] = array(
                    'text' => 'Ожидает оплаты',
                    'active' => false,
                    'preactive' => false
                );

            }

            if ($catalog_item['status'] === 'work') {
                $statuses[] = array('text' => 'Подготовка к размещению', 'active' => true, 'preactive' => true);
            } else {
                $statuses[] = array(
                    'text' => 'Подготовка к размещению',
                    'active' => false,
                    'preactive' => false
                );
            }

            if ($catalog_item['status'] === 'won') {
                $statuses[] = array('text' => 'Завершена', 'active' => true, 'preactive' => true);
            }

            if ($catalog_item['status'] === 'cancel') {
                $statuses[] = array('text' => 'Отмена', 'active' => true, 'preactive' => true);
            }

            if ($catalog_item['status'] !== 'won' && $catalog_item['status'] !== 'cancel') {
                $statuses[] = array(
                    'text' => 'Завершена',
                    'active' => false,
                    'preactive' => false
                );
            }


//                foreach ($statuses as $key => &$status) {
//                    if ($key == count($statuses) - 2 && !$statuses[count($statuses) - 1]['active']) {
//                        $status['preactive'] = true;
//                    } else {
//                        $status['preactive'] = false;
//                    }
//                }

            $catalog_item_info = array(
                'title' => $catalog_item['title'],
                'date' => date('d', $time) . '&nbsp;' . $month_list[(int)date('m', $time)] . '&nbsp;' . date('Y', $time),
                'status' => $catalog_item['status'],
                'statuses' => $statuses,
                'company' => $catalog_item['company'],
                'invoice' => $catalog_item['invoice'],
            );

            $data['catalog_list'][] = $catalog_item_info;

            $sort_catalog[] = $time;
        }

        array_multisort($sort_catalog, SORT_DESC, $data['catalog_list']);

        $return['template'] = $this->load->view('register/catalog_list', $data);

    } else {
        $return['error'] = true;
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($return));
}

