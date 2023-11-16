<?php

class ControllerThemesetEvents extends Controller
{
    private $error = array();

    private $b24_hook = '';

    function translit_sef($value): string
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        );

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }

    public function updateEventList()
    {
        header('Content-Type: application/json');
        $data = $_REQUEST;

        $this->load->model('themeset/events');
        $this->load->model('themeset/themeset');
//        print_r($data);

        if (!empty($data)) {
            $event_info = array(
                'event_id' => $data['event_id'],
                'title' => $data['title'],
                'date' => $data['date'],
                'price' => $data['price'],
                'image' => 'catalog/events/' . basename($data['image']),
                'image_full' => 'catalog/events/' . basename($data['image']),
                'count' => $data['count'],
                'address' => $data['address'],
                'address_full' => $data['address_full'],
                'type' => $data['type'],
                'status' => 1,
                'show_event' => 1,
                'keyword' => $data['type'] === 'AV Focus' ?
                    'avfocus' . date("y", strtotime($data['date'])) . $this->translit_sef($data['address']) :
                    'webinar' . date("y", strtotime($data['date'])) . $this->translit_sef($data['address']),
                'date_available' => $data['date'] . '9:00:00',
                'time_start' => '9:00:00',
                'time_end' => '17:00:00',
                'link' => 'https://www.avclub.pro/event-register/?forum_id=' . $data['event_id'],
                'old_type' => 'page',
                'old_link' => '',
                'video' => $data['video'],
                'video_image' => 'catalog/video/' . basename($data['video_image']),
                'coord' => '',
                'present_title' => '',
                'brand_title' => 'Бренды участники',
                'brand_template' => 0,
                'insta_title' => '',
                'prg_title' => 'Что будет на мероприятии',
                'prg_template' => 0,
                'prg_file_id' => '',
                'speaker_title' => 'Вы сможете задать вопрос любому из спикеров',
                'ask_title' => 'Вопросы и ответы',
            );

            $this->load->model('tool/image');

            $event_info['cities'] = $this->model_themeset_events->getCities();

            foreach ($event_info['cities'] as $city) {
                if ($event_info['address'] == $city["title"]) {
                    $event_info['city_id'] = $city['city_id'];
                    break;
                }
            }

            $event_info['types'] = $this->model_themeset_events->getTypes();

            foreach ($event_info['types'] as $type) {
                if ($event_info['type'] == $type["title"]) {
                    $event_info['type_id'] = $type['type_id'];
                    break;
                }
            }

            $image_events_dir = DIR_IMAGE . 'catalog/events/';
            $image_dir = DIR_IMAGE . 'catalog/video/';

            $image_path = $image_events_dir . basename($data['image']);
            $video_image_path = $image_dir . basename($data['video_image']);

            copy($data['image'], $image_path);
            copy($data['video_image'], $video_image_path);

            if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
                $event_info['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
            } elseif (!empty($event_info) && is_file(DIR_IMAGE . $event_info['image'])) {
                $event_info['thumb'] = $this->model_tool_image->resize($event_info['image'], 100, 100);
            } else {
                $event_info['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            }

            if (isset($this->request->post['image_full']) && is_file(DIR_IMAGE . $this->request->post['image_full'])) {
                $event_info['thumb_full'] = $this->model_tool_image->resize($this->request->post['image_full'], 100, 100);
            } elseif (!empty($event_info) && is_file(DIR_IMAGE . $event_info['image_full'])) {
                $event_info['thumb_full'] = $this->model_tool_image->resize($event_info['image_full'], 100, 100);
            } else {
                $event_info['thumb_full'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            }

            if (isset($this->request->post['date'])) {
                $event_info['date'] = $this->request->post['date'];
            } elseif (!empty($event_info)) {
                $event_info['date'] = ($event_info['date'] != '0000-00-00') ? $event_info['date'] : '';
            } else {
                $event_info['date'] = date('Y-m-d');
            }

            if (isset($this->request->post['date_stop'])) {
                $data['date_stop'] = $this->request->post['date_stop'];
            } elseif (!empty($event_info)) {
                $event_info['date_stop'] = ($event_info['date_stop'] != '0000-00-00') ? $event_info['date_stop'] : '';
            } else {
                $event_info['date_stop'] = date('Y-m-d');
            }

            $date_start = strtotime($event_info['date']);
            $date_stop = strtotime($event_info['date_stop']);

            if ($date_stop < $date_start) {
                $event_info['date_stop'] = $event_info['date'];
            }

            if (isset($this->request->post['time_start'])) {
                $event_info['time_start'] = $this->request->post['time_start'];
            } elseif (!empty($event_info)) {
                $event_info['time_start'] = ($event_info['time_start'] != '0000-00-00') ? $event_info['time_start'] : '';
            } else {
                $event_info['time_start'] = date('H:i', strtotime('09:00'));
            }

            if (isset($this->request->post['time_end'])) {
                $data['time_end'] = $this->request->post['time_end'];
            } elseif (!empty($event_info)) {
                $event_info['time_end'] = ($event_info['time_end'] != '0000-00-00') ? $event_info['time_end'] : '';
            } else {
                $event_info['time_end'] = date('H:i', strtotime('12:00'));
            }

            if (isset($this->request->post['event_description'])) {
                $event_info['event_description'] = $this->request->post['event_description'];
            } elseif (isset($this->request->get['event_id'])) {
                $event_info['event_description'] = $this->model_avevent_event->getEventDescriptions($this->request->get['event_id']);
            } else {
                $event_info['event_description'] = array();
            }

            // template
            $template = array();
            if (isset($this->request->post['template'])) {
                $tpl = $this->request->post['template'];
                foreach ($tpl as $key => $item) {
                    $template[$key] = array('status' => $item);
                }
            } else {
                $template = array(
                    'top' => 1,
                    'video' => 1,
                    'brand' => 0,
                    'plus' => 1,
                    'insta' => 0,
                    'prg' => 0,
                    'speaker' => 0,
                    'present' => 0,
                    'register' => 1,
                    'ask' => 1,
                );
            }

            $event_info['template'] = $template;

            // plus
            $event_info['plus'] = [];
            $plus = [];
            if (isset($this->request->post['plus'])) {
                $plus = $this->request->post['plus'];
            } elseif (!empty($event_info)) {
                $plus = $this->model_themeset_events->getPlusByEvent(201);
            }

            foreach ($plus as $item) {
                if ($item['image'] && is_file(DIR_IMAGE . $item['image'])) {
                    $thumb = $this->model_tool_image->resize($item['image'], 100, 100);
                } else {
                    $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
                }
                $item['thumb'] = $thumb;
                $event_info['plus'][] = $item;
            }

            // ask
            $event_info['ask'] = array();
            if (isset($this->request->post['ask'])) {
                $event_info['ask'] = $this->request->post['ask'];
            } elseif (!empty($event_info)) {
                $event_info['ask'] = $this->model_themeset_events->getAskByEvent(201);
            }

            $address = $event_info['address'] . ', ' . $event_info['address_full'];

            $parameters = array(
                'apikey' => 'c5ca1e67-1c9d-4482-b2bb-701be89f29de',
                'geocode' => $address,
                'format' => 'json'
            );

            $response = file_get_contents('https://geocode-maps.yandex.ru/1.x/?' . http_build_query($parameters));
            $obj = json_decode($response, true);

            $cord = str_replace(" ", ",", $obj['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']);

            $event_info['coord'] = $cord;

            if ($data['event_db_id']) {
                $this->model_themeset_events->editEvent($data['event_db_id'], $event_info);
                $event_id = $data['event_db_id'];
            } else {
                $event_id = $this->model_themeset_events->addEvent($event_info);
            }

            $link = 'https://www.avclub.pro/event/' . $event_info['keyword'];
            $json = ['url' => $link, 'id' => $event_id, 'b24_id' => (int)$data['event_id']];

            echo json_encode($json);
        } else {
            $message = array(
                'alert' => 'ошибка обновления мероприятия списка',
                'date' => $data
            );
            $this->model_themeset_themeset->alert($message);
            echo '0';
        }
    }

    public function updateMasterList()
    {
        header('Content-Type: application/json');
        $data = $_REQUEST;
    }

}