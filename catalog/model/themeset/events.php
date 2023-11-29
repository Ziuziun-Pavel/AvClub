<?php

class ModelThemesetEvents extends Model
{
    public function addEvent($data)
    {
        $time = strtotime($data['date'] . ' ' . $data['time_start']);
        $date_available = date('Y-m-d H:i:s', $time);

        $this->db->query("INSERT INTO " . DB_PREFIX . "avevent SET 
			status = '" . (int)$data['status'] . "' 
			, show_event = '" . (int)$data['show_event'] . "' 
			, date_available = '" . $this->db->escape($date_available) . "' 
			, date = '" . $this->db->escape($data['date']) . "' 
			, date_stop = '" . $this->db->escape($data['date_stop']) . "' 
			, time_start = '" . $this->db->escape($data['time_start']) . "' 
			, time_end = '" . $this->db->escape($data['time_end']) . "' 
			, link = '" . $this->db->escape($data['link']) . "' 
			, type_id = '" . (int)$data['type_id'] . "' 
			, city_id = '" . (int)$data['city_id'] . "' 
			, count = '" . (int)$data['count'] . "' 
			, price = '" . (int)$data['price'] . "' 
			, coord = '" . $this->db->escape($data['coord']) . "' 
			, address = '" . $this->db->escape($data['address']) . "' 
			, address_full = '" . $this->db->escape($data['address_full']) . "' 
			, image = '" . $this->db->escape($data['image']) . "' 
			, image_full = '" . $this->db->escape($data['image_full']) . "' 
			, video = '" . $this->db->escape($data['video']) . "' 
			, video_image = '" . $this->db->escape($data['video_image']) . "' 

			, brand_title = '" . $this->db->escape($data['brand_title']) . "' 
			, brand_template = '" . (int)$data['brand_template'] . "'

			, speaker_title = '" . $this->db->escape($data['speaker_title']) . "' 
			, ask_title = '" . $this->db->escape($data['ask_title']) . "' 
			, present_title = '" . $this->db->escape($data['present_title']) . "' 
			, insta_title = '" . $this->db->escape($data['insta_title']) . "' 
			, old_type = '" . $this->db->escape($data['old_type']) . "' 
			, old_link = '" . $this->db->escape($data['old_link']) . "' 

			, prg_title = '" . $this->db->escape($data['prg_title']) . "' 
			, prg_file_id = '" . (isset($data['file_id']) ? (int)$data['file_id'] : 0) . "' 
			, prg_template = '" . (int)$data['prg_template'] . "' 
			");

        $event_id = $this->db->getLastId();

//        var_dump($event_id);

        foreach ($data['event_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_description SET event_id = '" . (int)$event_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if (isset($data['template'])) {
            $template_counter = 0;
            foreach ($data['template'] as $template => $status) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_template SET template = '" . $this->db->escape($template) . "',  event_id = '" . (int)$event_id . "',  status = '" . (int)$status . "',  sort_order = '" . (int)$template_counter . "'");
                $template_counter++;
            }
        }

        if (isset($data['author'])) {
            foreach ($data['author'] as $key => $author) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_author SET 
					author_id = '" . (int)$author . "',  
					author_exp = '" . (!empty($data['author_exp'][$key]) ? (int)$data['author_exp'][$key] : 0) . "', 
					event_id = '" . (int)$event_id . "',  
					sort_order = '0'");
            }
        }

        if (isset($data['company'])) {
            foreach ($data['company'] as $key => $company) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_company SET company_id = '" . (int)$company . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
            }
        }

        if (isset($data['present'])) {
            foreach ($data['present'] as $key => $present) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_present SET present_id = '" . (int)$present . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
            }
        }

        if (isset($data['ask'])) {
            foreach ($data['ask'] as $ask) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_ask SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($ask['title']) . "', text = '" . $this->db->escape($ask['text']) . "',  sort_order = '" . (int)$ask['sort_order'] . "'");
            }
        }

        if (isset($data['prg'])) {
            foreach ($data['prg'] as $prg) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_prg SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($prg['title']) . "', time_start = '" . $this->db->escape($prg['time_start']) . "', time_end = '" . $this->db->escape($prg['time_end']) . "', image = '" . $this->db->escape($prg['image']) . "', text = '" . $this->db->escape($prg['text']) . "',  sort_order = '" . (int)$prg['sort_order'] . "'");
            }
        }

        if (isset($data['plus'])) {
            foreach ($data['plus'] as $plus) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_plus SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($plus['title']) . "', image = '" . $this->db->escape($plus['image']) . "', text = '" . $this->db->escape($plus['text']) . "',  sort_order = '" . (int)$plus['sort_order'] . "'");
            }
        }

        if (isset($data['insta'])) {
            foreach ($data['insta'] as $insta) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_insta SET  event_id = '" . (int)$event_id . "', href = '" . $this->db->escape($insta['href']) . "', image = '" . $this->db->escape($insta['image']) . "',  sort_order = '" . (int)$insta['sort_order'] . "'");
            }
        }

        if (isset($data['keyword'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('event');

        return $event_id;
    }

    public function editEvent($event_id, $data)
    {
        $time = strtotime($data['date'] . ' ' . $data['time_start']);
        $date_available = date('Y-m-d H:i:s', $time);

        $this->db->query("UPDATE " . DB_PREFIX . "avevent SET 
			status = '" . (int)$data['status'] . "' 
			, show_event = '" . (int)$data['show_event'] . "' 
			, date_available = '" . $this->db->escape($date_available) . "' 
			, date = '" . $this->db->escape($data['date']) . "' 
			, date_stop = '" . $this->db->escape($data['date_stop']) . "' 
			, time_start = '" . $this->db->escape($data['time_start']) . "' 
			, time_end = '" . $this->db->escape($data['time_end']) . "' 
			, link = '" . $this->db->escape($data['link']) . "' 
			, type_id = '" . (int)$data['type_id'] . "' 
			, city_id = '" . (int)$data['city_id'] . "' 
			, count = '" . (int)$data['count'] . "' 
			, price = '" . (int)$data['price'] . "' 
			, coord = '" . $this->db->escape($data['coord']) . "' 
			, address = '" . $this->db->escape($data['address']) . "' 
			, address_full = '" . $this->db->escape($data['address_full']) . "' 
			, image = '" . $this->db->escape($data['image']) . "' 
			, image_full = '" . $this->db->escape($data['image_full']) . "' 
			, video = '" . $this->db->escape($data['video']) . "' 
			, video_image = '" . $this->db->escape($data['video_image']) . "' 

			, brand_title = '" . $this->db->escape($data['brand_title']) . "' 
			, brand_template = '" . (int)$data['brand_template'] . "'

			, speaker_title = '" . $this->db->escape($data['speaker_title']) . "' 
			, ask_title = '" . $this->db->escape($data['ask_title']) . "' 
			, present_title = '" . $this->db->escape($data['present_title']) . "' 
			, insta_title = '" . $this->db->escape($data['insta_title']) . "' 
			, old_type = '" . $this->db->escape($data['old_type']) . "' 
			, old_link = '" . $this->db->escape($data['old_link']) . "' 

			, prg_title = '" . $this->db->escape($data['prg_title']) . "' 
			, prg_file_id = '" . (isset($data['file_id']) ? (int)$data['file_id'] : 0) . "' 
			, prg_template = '" . (int)$data['prg_template'] . "' 
			WHERE event_id = '" . (int)$event_id . "'
			");

        $this->db->query("DELETE FROM " . DB_PREFIX . "avevent_description WHERE event_id = '" . (int)$event_id . "'");
        foreach ($data['event_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_description SET event_id = '" . (int)$event_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "avevent_template WHERE event_id = '" . (int)$event_id . "'");
        if (isset($data['template'])) {
            $template_counter = 0;
            foreach ($data['template'] as $template => $status) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_template SET template = '" . $this->db->escape($template) . "',  event_id = '" . (int)$event_id . "',  status = '" . (int)$status . "',  sort_order = '" . (int)$template_counter . "'");
                $template_counter++;
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "avevent_author WHERE event_id = '" . (int)$event_id . "'");
        if (isset($data['author'])) {
            foreach ($data['author'] as $key => $author) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_author SET 
					author_id = '" . (int)$author . "',  
					author_exp = '" . (!empty($data['author_exp'][$key]) ? (int)$data['author_exp'][$key] : 0) . "', 
					event_id = '" . (int)$event_id . "',  
					sort_order = '0'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "avevent_present WHERE event_id = '" . (int)$event_id . "'");
        if (isset($data['present'])) {
            foreach ($data['present'] as $key => $present) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_present SET present_id = '" . (int)$present . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "avevent_company WHERE event_id = '" . (int)$event_id . "'");
        if (isset($data['company'])) {
            foreach ($data['company'] as $key => $company) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_company SET company_id = '" . (int)$company . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "avevent_ask WHERE event_id = '" . (int)$event_id . "'");
        if (isset($data['ask'])) {
            foreach ($data['ask'] as $ask) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_ask SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($ask['title']) . "', text = '" . $this->db->escape($ask['text']) . "',  sort_order = '" . (int)$ask['sort_order'] . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "avevent_prg WHERE event_id = '" . (int)$event_id . "'");
        if (isset($data['prg'])) {
            foreach ($data['prg'] as $prg) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_prg SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($prg['title']) . "', time_start = '" . $this->db->escape($prg['time_start']) . "', time_end = '" . $this->db->escape($prg['time_end']) . "', image = '" . $this->db->escape($prg['image']) . "', text = '" . $this->db->escape($prg['text']) . "',  sort_order = '" . (int)$prg['sort_order'] . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "avevent_insta WHERE event_id = '" . (int)$event_id . "'");
        if (isset($data['insta'])) {
            foreach ($data['insta'] as $insta) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_insta SET  event_id = '" . (int)$event_id . "', href = '" . $this->db->escape($insta['href']) . "', image = '" . $this->db->escape($insta['image']) . "',  sort_order = '" . (int)$insta['sort_order'] . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "avevent_plus WHERE event_id = '" . (int)$event_id . "'");
        if (isset($data['plus'])) {
            foreach ($data['plus'] as $plus) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "avevent_plus SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($plus['title']) . "', image = '" . $this->db->escape($plus['image']) . "', text = '" . $this->db->escape($plus['text']) . "',  sort_order = '" . (int)$plus['sort_order'] . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "'");

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('event');
    }

    public function getCities($data = array())
    {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "avevent_city c WHERE c.city_id > '0' ";

            if (!empty($data['filter_title'])) {
                $sql .= " AND LCASE(c.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_title'])) . "%'";
            }

            $sort_data = array(
                'c.title'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY c.title";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $city_data = $this->cache->get('city.' . (int)$this->config->get('config_language_id'));

            if (!$city_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_city c ORDER BY c.title");

                $city_data = $query->rows;

                $this->cache->set('city.' . (int)$this->config->get('config_language_id'), $city_data);
            }

            return $city_data;
        }
    }

    public function getTypes($data = array())
    {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "avevent_type t LEFT JOIN " . DB_PREFIX . "avevent_type_description td ON (t.type_id = td.type_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            if (!empty($data['filter_title'])) {
                $sql .= " AND LCASE(td.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_title'])) . "%'";
            }

            $sort_data = array(
                'td.title'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY td.title";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $type_data = $this->cache->get('type.' . (int)$this->config->get('config_language_id'));

            if (!$type_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_type t LEFT JOIN " . DB_PREFIX . "avevent_type_description td ON (t.type_id = td.type_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY td.title");

                $type_data = $query->rows;

                $this->cache->set('type.' . (int)$this->config->get('config_language_id'), $type_data);
            }

            return $type_data;
        }
    }

    public function getPlusByEvent($event_id)
    {
        $plus_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_plus p WHERE p.event_id = '" . (int)$event_id . "' ORDER BY p.sort_order ASC");

        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                $plus_data[] = array(
                    'title' => $row['title'],
                    'text' => $row['text'],
                    'image' => $row['image'],
                    'sort_order' => $row['sort_order'],
                );
            }
        }

        return $plus_data;
    }

    public function getAskByEvent($event_id)
    {
        $ask_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_ask a WHERE a.event_id = '" . (int)$event_id . "' ORDER BY a.sort_order ASC, a.title ASC");

        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                $ask_data[] = array(
                    'title' => $row['title'],
                    'text' => $row['text'],
                    'sort_order' => $row['sort_order'],
                );
            }
        }

        return $ask_data;
    }

    public function getCompanyIDs($city_b24ids)
    {
        $companyValues = implode("', '", $city_b24ids);

//        var_dump($companyValues);
//        die();
        $sql = "SELECT company_id FROM " . DB_PREFIX . "company WHERE b24id IN ('$companyValues') ORDER BY company_id ASC";

        $query = $this->db->query($sql);

        $companyIDs = array_map(function ($row) {
            return (int)$row['company_id'];
        }, $query->rows);
//        var_dump($query->rows);
//        die();
        return $companyIDs;
    }

    public function getExperts($experts)
    {
        $expertsValues = implode("', '", $experts);

        $sql = "SELECT avc_visitor.visitor_id, avc_visitor_exp.exp_id
            FROM " . DB_PREFIX . "visitor avc_visitor
            LEFT JOIN " . DB_PREFIX . "visitor_exp avc_visitor_exp ON avc_visitor.visitor_id = avc_visitor_exp.visitor_id
            WHERE avc_visitor.b24id IN ('$expertsValues')
            ORDER BY avc_visitor.visitor_id ASC";

        $query = $this->db->query($sql);

        $expertsData = array_map(function ($row) {
            return array(
                'visitor_id' => (int)$row['visitor_id'],
                'exp_id' => (int)$row['exp_id']
            );
        }, $query->rows);

        return $expertsData;
    }

    public function addMaster($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "master SET 
			date_available = '" . $this->db->escape($data['date_available']) . "', 
			type = '" . $this->db->escape($data['type']) . "', 
			image = '" . $this->db->escape($data['image']) . "', 
			logo = '" . $this->db->escape($data['logo']) . "', 
			link = '" . $this->db->escape($data['link']) . "', 
			status = '" . (int)$data['status'] . "', 
			author_id = '" . (int)$data['author_id'] . "', 
			author_exp = '" . (!empty($data['author_exp']) ? (int)$data['author_exp'] : 0) . "', 
			company_id = '" . (!empty($data['company_id']) ? (int)$data['company_id'] : 0) . "'");

        $master_id = $this->db->getLastId();

        foreach ($data['master_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "master_description SET 
				master_id = '" . (int)$master_id . "', 
				language_id = '" . (int)$language_id . "', 
				title = '" . $this->db->escape($value['title']) . "', 
				description = '" . $this->db->escape($value['description']) . "', 
				preview = '" . $this->db->escape($value['preview']) . "', 
				meta_title = '" . $this->db->escape($value['meta_title']) . "', 
				meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', 
				meta_description = '" . $this->db->escape($value['meta_description']) . "', 
				meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if(isset($data['company'])) {
            foreach($data['company'] as $key=>$company) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "master_company SET company_id = '" . (int)$company . "',  master_id = '" . (int)$master_id . "'");
            }
        }

        if(!empty($data['experts'])) {
            foreach($data['experts'] as $key=>$expert) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "master_expert SET 
					author_id = '" . (int)$expert['author_id'] . "',  
					author_exp = '" . (int)$expert['exp_id'] . "', 
					master_id = '" . (int)$master_id . "'");
            }
        }

        if (isset($data['master_layout'])) {
            foreach ($data['master_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "master_to_layout SET master_id = '" . (int)$master_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        if (isset($data['keyword'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'master_id=" . (int)$master_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('master');

        return $master_id;
    }

    public function editMaster($master_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "master SET date_available = '" . $this->db->escape($data['date_available']) . "', 
		type = '" . $this->db->escape($data['type']) . "', 
		image = '" . $this->db->escape($data['image']) . "', 
		logo = '" . $this->db->escape($data['logo']) . "', 
		link = '" . $this->db->escape($data['link']) . "', 
		status = '" . (int)$data['status'] . "', 
		author_id = '" . (int)$data['author_id'] . "', 
		author_exp = '" . (!empty($data['author_exp']) ? (int)$data['author_exp'] : 0) . "', 
		company_id = '" . (!empty($data['company_id']) ? (int)$data['company_id'] : 0) . "' WHERE master_id = '" . (int)$master_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "master_description WHERE master_id = '" . (int)$master_id . "'");

        foreach ($data['master_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "master_description SET master_id = '" . (int)$master_id . "', 
			language_id = '" . (int)$language_id . "', 
			title = '" . $this->db->escape($value['title']) . "', 
			description = '" . $this->db->escape($value['description']) . "', 
			preview = '" . $this->db->escape($value['preview']) . "', 
			meta_title = '" . $this->db->escape($value['meta_title']) . "', 
			meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', 
			meta_description = '" . $this->db->escape($value['meta_description']) . "', 
			meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "master_company WHERE master_id = '" . (int)$master_id . "'");
        if(isset($data['company'])) {
            foreach($data['company'] as $key=>$company) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "master_company SET company_id = '" . (int)$company . "',  master_id = '" . (int)$master_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "master_expert WHERE master_id = '" . (int)$master_id . "'");
        if(!empty($data['experts'])) {
            foreach($data['experts'] as $key=>$expert) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "master_expert SET 
					author_id = '" . (int)$expert['author_id'] . "',  
					author_exp = '" . (int)$expert['exp_id'] . "', 
					master_id = '" . (int)$master_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "master_to_layout WHERE master_id = '" . (int)$master_id . "'");
        if (isset($data['master_layout'])) {
            foreach ($data['master_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "master_to_layout SET master_id = '" . (int)$master_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

//        $this->db->query("DELETE FROM " . DB_PREFIX . "master_tag WHERE master_id = '" . (int)$master_id . "'");
//        if (isset($data['tag'])) {
//            foreach ($data['tag'] as $tag_id) {
//                $this->db->query("INSERT INTO " . DB_PREFIX . "master_tag SET master_id = '" . (int)$master_id . "', tag_id = '" . (int)$tag_id . "'");
//            }
//        }

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'master_id=" . (int)$master_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('master');
    }

}