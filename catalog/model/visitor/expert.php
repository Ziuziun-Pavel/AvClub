<?php

class ModelVisitorExpert extends Model
{
    private $url_deal_create = "http://clients.techin.by/avclub/site/api/v1/deal/create";
    private $url_deal_update = "http://clients.techin.by/avclub/site/api/v1/deal/{id}/update";

    public function getExpert($expert_id, $exp_id = 0, $expert = true)
    {
        $visitor_info = array();

        $sql = "SELECT DISTINCT 
		v.visitor_id, 
		v.name, 
		v.firstname, 
		v.lastname, 
		v.image,  
		v.email,  
		v.emails,  
		v.b24id,  
		v.company_id,
		v.expert,
		v.field_useful,
		v.field_regalia,
		(SELECT COUNT(va.visitor_id) FROM " . DB_PREFIX . "visitor_alternate va WHERE va.visitor_id = " . (int)$expert_id . ") as alternate_count  
		FROM " . DB_PREFIX . "visitor v 
		WHERE 
		v.visitor_id = '" . (int)$expert_id . "' 
		AND v.status = '1'";

        if ($expert) {
            $sql .= " AND v.expert = '1' ";
        }

        $query = $this->db->query($sql);

        if ($query->num_rows) {

            $exp_list = array(
                'main' => '',
                'num' => ''
            );

            $sql_exp = "SELECT * FROM " . DB_PREFIX . "visitor_exp ve 
			WHERE 
			ve.visitor_id = '" . (int)$expert_id . "'
			AND ( 
			ve.exp_id = " . (int)$exp_id . " 
			OR ve.main = '1' ) 
			";

            $query_exp = $this->db->query($sql_exp);

            if ($query_exp->num_rows) {
                foreach ($query_exp->rows as $row) {
                    if ($row['main']) {
                        $exp_list['main'] = $row['exp'];
                    } else {
                        $exp_list['num'] = $row['exp'];
                    }
                }
            }

            $exp = !empty($exp_list['num']) ? $exp_list['num'] : $exp_list['main'];
            $visitor_info = array(
                'expert_id' => $query->row['visitor_id'],
                'name' => $query->row['name'],
                'firstname' => $query->row['firstname'],
                'lastname' => $query->row['lastname'],
                'image' => $query->row['image'],
                'email' => $query->row['email'],
                'emails' => $query->row['emails'],
                'b24id' => $query->row['b24id'],
                'company_id' => $query->row['company_id'],
                'expert' => $query->row['expert'],
                'alternate_count' => $query->row['alternate_count'],
                'field_useful' => $query->row['field_useful'],
                'field_regalia' => $query->row['field_regalia'],
                'exp' => $this->mb_ucfirst($exp),
            );
        }

        return $visitor_info;
    }

    public function getExperts($data = array())
    {
        $expert_list = array();

        $sort_list = array(
            'lastname',
            'modified',
            'articles'
        );
        $sort_items = array(
            'lastname' => " LCASE(v.lastname) ",
            'modified' => " CASE WHEN article_date THEN article_date ELSE v.date_modified END ",
            'articles' => " articles ",
        );

        $sort = '';
        $order = !empty($data['order']) && $data['order'] === 'ASC' ? 'ASC' : 'DESC';

        if (!empty($data['sort']) && in_array($data['sort'], $sort_list)) {

            switch ($data['sort']) {
                case 'modified':
                    // $sort = 'article_date';
                    // $sort = 'v.date_modified';
                    $sort = $sort_items['modified'];
                    break;

                case 'articles':
                    $sort = $sort_items['articles'];
                    break;

                case 'lastname':
                    $sort = $sort_items['lastname'];
                    break;
            }
        }

        $sort = !empty($sort) ? $sort : $sort_items['modified'];
        // $sort = !empty($sort) ? $sort : 'v.date_modified';

        $sql = "SELECT v.visitor_id ";

        /* Дата первой статьи, мероприятия, онлайн-события */
        $sql_date = array();
        $sql_date['journal'] = " SELECT j2.date_available as `publish_date` 
		FROM " . DB_PREFIX . "journal j2 
		LEFT JOIN " . DB_PREFIX . "journal_expert j2e ON (j2.journal_id = j2e.journal_id)
		WHERE 
		j2.status = '1'
		AND j2.date_available <= NOW()  
		AND (j2.author_id = v.visitor_id OR j2e.author_id = v.visitor_id) ";

        $sql_date['master'] = " SELECT m2.date_available as `publish_date`
		FROM " . DB_PREFIX . "master m2 
		LEFT JOIN " . DB_PREFIX . "master_expert m2e ON (m2.master_id = m2e.master_id) 
		WHERE 
		(m2.author_id = v.visitor_id OR m2e.author_id = v.visitor_id) 
		AND m2.status = '1' 
		AND m2.date_available > NOW() 
		";

        $sql_date['event'] = " SELECT e2.date_available as `publish_date`
		FROM " . DB_PREFIX . "avevent e2 
		LEFT JOIN " . DB_PREFIX . "avevent_author e2a ON (e2.event_id = e2a.event_id) 
		WHERE 
		e2a.author_id = v.visitor_id  
		AND e2.status = '1' 
		AND ( 
		(e2.show_event = '1' AND (e2.date >= CURRENT_DATE() OR e2.date_stop >= CURRENT_DATE()) ) 
		OR 
		(e2.old_type = 'page' AND e2.date_available < NOW())
		)
		";


        /*$sql .= ", (SELECT j2.date_available
        FROM " . DB_PREFIX . "journal j2
        LEFT JOIN " . DB_PREFIX . "journal_expert j2e ON (j2.journal_id = j2e.journal_id)
        WHERE
        j2.status = '1'
        AND j2.date_available <= NOW()
        AND (j2.author_id = v.visitor_id OR j2e.author_id = v.visitor_id)
        AND j2.master_old <> '1'
        ORDER BY
        j2.date_available ASC
        LIMIT 1) as article_date ";*/

        $sql .= ", ( " . implode(" UNION ", $sql_date) . " ORDER BY `publish_date` ASC LIMIT 1) as article_date ";
        /* # Дата первой статьи, мероприятия, онлайн-события */


        /* кол-во публикаций / мероприятий / событий */

        $sql_count = array();
        $sql_count['journal'] = " SELECT j3.journal_id as `id3` 
		FROM " . DB_PREFIX . "journal j3 
		LEFT JOIN " . DB_PREFIX . "journal_expert j3e ON (j3.journal_id = j3e.journal_id)
		WHERE 
		j3.status = '1'
		AND j3.date_available <= NOW()  
		AND (j3.author_id = v.visitor_id OR j3e.author_id = v.visitor_id) ";

        $sql_count['master'] = " SELECT m3.master_id as `id3`
		FROM " . DB_PREFIX . "master m3 
		LEFT JOIN " . DB_PREFIX . "master_expert m3e ON (m3.master_id = m3e.master_id) 
		WHERE 
		(m3.author_id = v.visitor_id OR m3e.author_id = v.visitor_id) 
		AND m3.status = '1' 
		AND m3.date_available > NOW() 
		";

        $sql_count['event'] = " SELECT e3.event_id as `id3`
		FROM " . DB_PREFIX . "avevent e3 
		LEFT JOIN " . DB_PREFIX . "avevent_author e3a ON (e3.event_id = e3a.event_id) 
		WHERE 
		e3a.author_id = v.visitor_id 
		AND e3.status = '1' 
		AND ( 
		(e3.show_event = '1' AND (e3.date >= CURRENT_DATE() OR e3.date_stop >= CURRENT_DATE()) )  
		OR 
		(e3.old_type = 'page' AND e3.date_available < NOW())
		)
		";

        /*$sql .= ", (SELECT COUNT(j.journal_id)
        FROM " . DB_PREFIX . "journal j
        LEFT JOIN " . DB_PREFIX . "journal_expert je ON (j.journal_id = je.journal_id)
        WHERE
        j.status = '1'
        AND j.date_available <= NOW()
        AND (j.author_id = v.visitor_id OR je.author_id = v.visitor_id)
        AND j.master_old <> '1') as articles";*/

        $sql .= ", ( SELECT COUNT(*) FROM (" . implode(" UNION ALL ", $sql_count) . ") as `publish_count` ) as articles ";
        /* кол-во публикаций / мероприятий / событий */

        $sql .= " FROM " . DB_PREFIX . "visitor v ";

        /*if($sort == 'j.date_available') {
            $sql .= " LEFT JOIN " . DB_PREFIX . "journal dj ON (v.visitor_id = dj.visitor_id)
            LEFT JOIN " . DB_PREFIX . "journal_expert dje ON (dj.journal_id = dje.journal_id) ";
        }*/

        if (!empty($data['filter_tag'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "visitor_tag_expert vte ON (v.visitor_id = vte.visitor_id) 
			LEFT JOIN " . DB_PREFIX . "tag_description tde ON (vte.tag_id = tde.tag_id) ";
        }

        if (!empty($data['filter_branch'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "visitor_tag_branch vtb ON (v.visitor_id = vtb.visitor_id) 
			LEFT JOIN " . DB_PREFIX . "tag_description tdb ON (vtb.tag_id = tdb.tag_id) ";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "company_description cd ON (cd.company_id = v.company_id) ";
        }


        $sql .= " WHERE 
		v.expert = '1' 
		AND v.status = '1'
        AND v.image IS NOT NULL AND v.image <> ''
		";

        /*if(!empty($sort === 'j.date_available')) {
            $sql .= " AND (j.author_id = v.visitor_id OR je.author_id = v.visitor_id)";
        }*/

        if (!empty($data['filter_name'])) {
            $sql .= " AND LCASE(v.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
        }
        if (!empty($data['filter_tag'])) {
            $sql .= " AND LCASE(tde.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'
			AND tde.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        }
        if (!empty($data['filter_branch'])) {
            $sql .= " AND LCASE(tdb.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_branch'])) . "%'
			AND tdb.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        }
        if (!empty($data['filter_company'])) {
            $sql .= " AND ( LCASE(cd.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_company'])) . "%' OR LCASE(cd.alternate) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_company'])) . "%')
			AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        }


        $sql .= " ORDER BY ";

        $sql .= " CASE WHEN articles > 0 THEN 1 ELSE 0 END ASC, ";

        $sql .= $sort . " " . $order . ", LCASE(v.lastname) ASC ";

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

        foreach ($query->rows as $result) {
            $expert_info = $this->getExpert($result['visitor_id']);

            if (!$expert_info) {
                continue;
            }

            $expert_list[$result['visitor_id']] = $expert_info;
            $expert_list[$result['visitor_id']]['visitor_id'] = $result['visitor_id'];
            // $expert_list[$result['visitor_id']]['articles'] = $result['articles'];
            $expert_list[$result['visitor_id']]['article_date'] = $result['article_date'];
            // $expert_list[$result['visitor_id']]['sort'] = $sort;
            // $expert_list[$result['visitor_id']]['order'] = $order;

        }

        // echo '<pre style="display:none;">';
        // print_r($sql);
        // echo '</pre>';
        // echo '<pre style="display:none;">';
        // print_r($query->rows);
        // echo '</pre>';

        return $expert_list;
    }

    public function getTotalExperts($data = array())
    {
        $sql = "SELECT COUNT(DISTINCT v.visitor_id) as total  
		FROM " . DB_PREFIX . "visitor v ";

        if (!empty($data['filter_tag'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "visitor_tag_expert vte ON (v.visitor_id = vte.visitor_id) 
			LEFT JOIN " . DB_PREFIX . "tag_description tde ON (vte.tag_id = tde.tag_id) ";
        }

        if (!empty($data['filter_branch'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "visitor_tag_branch vtb ON (v.visitor_id = vtb.visitor_id) 
			LEFT JOIN " . DB_PREFIX . "tag_description tdb ON (vtb.tag_id = tdb.tag_id) ";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "company_description cd ON (cd.company_id = v.company_id) ";
        }

        $sql .= " WHERE 
            v.expert = '1' 
            AND v.status = '1'
            AND v.image IS NOT NULL AND v.image <> ''
            ";

        if (!empty($data['filter_name'])) {
            $sql .= " AND LCASE(v.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
        }
        if (!empty($data['filter_tag'])) {
            $sql .= " AND LCASE(tde.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'
			AND tde.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        }
        if (!empty($data['filter_branch'])) {
            $sql .= " AND LCASE(tdb.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_branch'])) . "%'
			AND tdb.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        }
        if (!empty($data['filter_company'])) {
            $sql .= " AND ( LCASE(cd.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_company'])) . "%' OR LCASE(cd.alternate) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_company'])) . "%')
			AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTagsByExpert($expert_id = 0)
    {
        $visitor_tag_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor_tag_expert v2t 
			LEFT JOIN " . DB_PREFIX . "tag_description td ON (td.tag_id = v2t.tag_id) 
			WHERE 
			v2t.visitor_id = '" . (int)$expert_id . "' 
			ORDER BY 
			td.title ASC");

        foreach ($query->rows as $row) {
            $visitor_tag_data[] = array(
                'tag_id' => $row['tag_id'],
                'title' => $this->mb_ucfirst($row['title'])
            );
        }

        return $visitor_tag_data;
    }

    public function getBranchesByExpert($expert_id = 0)
    {
        $visitor_tag_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor_tag_branch v2t 
			LEFT JOIN " . DB_PREFIX . "tag_description td ON (td.tag_id = v2t.tag_id) 
			WHERE 
			v2t.visitor_id = '" . (int)$expert_id . "' 
			ORDER BY 
			td.title ASC");

        foreach ($query->rows as $row) {
            $visitor_tag_data[] = array(
                'tag_id' => $row['tag_id'],
                'title' => $this->mb_ucfirst($row['title'])
            );
        }

        return $visitor_tag_data;
    }

    public function getTabsExpert($expert_id = 0)
    {
        $return_tabs = array();
        $implode = array();

        if (!$expert_id) {
            return false;
        }


        // journal
        $sql = "SELECT j.type FROM " . DB_PREFIX . "journal j 
		LEFT JOIN " . DB_PREFIX . "journal_expert je ON (j.journal_id = je.journal_id) 
		WHERE 
		j.status = '1' 
		AND j.date_available <= NOW()  
		AND (j.author_id = '" . (int)$expert_id . "' OR je.author_id = '" . (int)$expert_id . "')
		AND j.master_old <> '1'
		GROUP BY j.type";

        $query = $this->db->query($sql);

        if ($query->rows) {
            foreach ($query->rows as $row) {
                $return_tabs[$row['type']] = true;
            }
        }

        // master
        $master = array();
        $sql = "SELECT m.type FROM " . DB_PREFIX . "master m 
		LEFT JOIN " . DB_PREFIX . "master_expert me ON (m.master_id = me.master_id) 
		WHERE 
		(m.author_id = '" . (int)$expert_id . "' OR me.author_id = '" . (int)$expert_id . "') 
		AND m.status = '1' 
		AND m.date_available > NOW() 
		GROUP BY m.type";

        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $master['master_all'] = 'master_all';
            /*foreach($query->rows as $row) {
                $master['master_' . $row['type']] = 'master_' . $row['type'];
            }*/
        }

        // master old
        $sql = "SELECT DISTINCT j.journal_id FROM " . DB_PREFIX . "journal j 
		LEFT JOIN " . DB_PREFIX . "journal_expert je ON (j.journal_id = je.journal_id) 
		WHERE 
		j.status = '1' 
		AND j.date_available <= NOW()  
		AND (j.author_id = '" . (int)$expert_id . "' OR je.author_id = '" . (int)$expert_id . "')
		AND j.master_old = '1' ";

        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $master['master_old'] = 'master_old';
        }

        if ($master) {
            $return_tabs['master'] = $master;
        }

        // events
        $events = array();
        $sql = "SELECT DISTINCT e.event_id
		FROM " . DB_PREFIX . "avevent_author ea 
		LEFT JOIN " . DB_PREFIX . "avevent e ON (e.event_id = ea.event_id) 
		WHERE 
		ea.author_id = '" . (int)$expert_id . "' 
		AND e.status = '1' ";

        $sql_new = $sql . " AND (e.show_event = '1' AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) )";
        $sql_old = $sql . " AND (e.old_type = 'page' AND e.date_available < NOW())";

        $query_new = $this->db->query($sql_new);
        $query_old = $this->db->query($sql_old);

        if ($query_new->num_rows) {
            $events['event_new'] = 'event_new';
        }
        if ($query_old->num_rows) {
            $events['event_old'] = 'event_old';
        }
        if ($events) {
            $return_tabs['event'] = $events;
        }

        if ($return_tabs) {
            $return_tabs = array_merge(array('all' => true), $return_tabs);
        }

        return $return_tabs;
    }

    public function sendPublication($data = array())
    {
        $fields = $data;

        $url = $this->url_deal_create;

        if ($data['deal_id']) {
            $url = str_replace('{id}', $data['deal_id'], $this->url_deal_update);
        }

        $ch_deal = curl_init($url);
        curl_setopt($ch_deal, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_deal, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch_deal, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch_deal, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch_deal, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $body = curl_exec($ch_deal);
        curl_close($ch_deal);

        $json = json_decode($body, true);

        return $json;

    }

    public function getTotalItems($data = array())
    {

        if (empty($data['filter_expert'])) {
            return 0;
        }


        $sql_journal = "SELECT jd.journal_id as `id`, 
		j.date_available 
		FROM " . DB_PREFIX . "journal_description jd 
		LEFT JOIN " . DB_PREFIX . "journal j ON (j.journal_id = jd.journal_id) 
		LEFT JOIN " . DB_PREFIX . "journal_expert je ON (j.journal_id = je.journal_id) 
		WHERE 
		(j.author_id = '" . (int)$data['filter_expert'] . "' OR je.author_id = '" . (int)$data['filter_expert'] . "')
		AND j.date_available <= NOW() 
		AND j.status = '1' 
		";

        if (!empty($data['filter_type']) && in_array($data['filter_type'], array('news', 'article', 'opinion', 'video', 'case'))) {
            $sql_journal .= " AND j.type = '" . $this->db->escape(utf8_strtolower($data['filter_type'])) . "'";
        }

        if (!empty($data['filter_type'])) {
            if (in_array($data['filter_type'], array('master_old'))) {
                $sql_journal .= " AND j.master_old = '1' ";
            } else if (in_array($data['filter_type'], array('video'))) {
                $sql_journal .= " AND j.master_old <> '1' ";
            }
        }


        $sql_event = "SELECT ea.event_id as id, 
		e.date_available	
		FROM " . DB_PREFIX . "avevent_author ea 
		LEFT JOIN " . DB_PREFIX . "avevent e ON (e.event_id = ea.event_id) 
		WHERE 
		ea.author_id = '" . (int)$data['filter_expert'] . "' 
		AND e.status = '1' ";

        if (!empty($data['filter_type'])) {
            if (in_array($data['filter_type'], array('event_new'))) {
                $sql_event .= " AND (e.show_event = '1' AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) ) ";
            } else if (in_array($data['filter_type'], array('event_old'))) {
                $sql_event .= " AND (e.old_type = 'page' AND e.date_available < NOW()) ";
            } else {
                $sql_event .= " AND ( (e.show_event = '1' AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE())) OR (e.old_type = 'page' AND e.date_available < NOW()) ) ";
            }
        }


        $sql_master = "SELECT md.master_id as id, 
		m.date_available 
		FROM " . DB_PREFIX . "master_description md 
		LEFT JOIN " . DB_PREFIX . "master m ON (m.master_id = md.master_id) 
		LEFT JOIN " . DB_PREFIX . "master_expert me ON (m.master_id = me.master_id) 
		WHERE 
		(m.author_id = '" . (int)$data['filter_expert'] . "' OR me.author_id = '" . (int)$data['filter_expert'] . "' )
		AND m.date_available > NOW() 
		AND m.status = '1' ";

        if (!empty($data['filter_type']) && in_array($data['filter_type'], array('master_master', 'master_meetup'))) {
            $sql_journal .= " AND m.type = '" . $this->db->escape(utf8_strtolower(str_replace('master_', '', $data['filter_type']))) . "'";
        }


        $sql = "SELECT item.id, COUNT(*) as total FROM (";

        if (!empty($data['filter_type'])) {
            switch (true) {
                case in_array($data['filter_type'], array('news', 'article', 'opinion', 'video', 'case', 'master_old')):
                    $sql .= $sql_journal;
                    break;

                case in_array($data['filter_type'], array('avevent', 'event', 'event_old', 'event_new')):
                    $sql .= $sql_event;
                    break;

                case in_array($data['filter_type'], array('master', 'master_all', 'master_master', 'master_meetup')):
                    $sql .= $sql_master;
                    break;

                default:
                    $sql .= $sql_journal . " UNION " . $sql_event . " UNION " . $sql_master;
            }
        } else {
            $sql .= $sql_journal . " UNION " . $sql_event . " UNION " . $sql_master;
        }

        $sql .= " ) as item";

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getItems($data = array())
    {

        if (empty($data['filter_expert'])) {
            return array();
        }

        $sql_journal = "SELECT jd.journal_id as `id`, 
		j.date_available,
		'journal' as `type`,
		10000 as `date_diff` 
		FROM " . DB_PREFIX . "journal_description jd 
		LEFT JOIN " . DB_PREFIX . "journal j ON (j.journal_id = jd.journal_id) 
		LEFT JOIN " . DB_PREFIX . "journal_expert je ON (j.journal_id = je.journal_id) 
		WHERE 
		(j.author_id = '" . (int)$data['filter_expert'] . "' OR je.author_id = '" . (int)$data['filter_expert'] . "') 
		AND j.date_available <= NOW() 
		AND j.status = '1' 
		";

        if (!empty($data['filter_type']) && in_array($data['filter_type'], array('news', 'article', 'opinion', 'video', 'case'))) {
            $sql_journal .= " AND j.type = '" . $this->db->escape(utf8_strtolower($data['filter_type'])) . "'";
        }

        if (!empty($data['filter_type'])) {
            if (in_array($data['filter_type'], array('master_old'))) {
                $sql_journal .= " AND j.master_old = '1' ";
            } else if (in_array($data['filter_type'], array('video'))) {
                $sql_journal .= " AND j.master_old <> '1' ";
            }
        }


        $sql_event = "SELECT ea.event_id as id, 
		e.date_available, 
		'event' as `type`,
		(CASE WHEN e.date_available >= NOW() THEN DATEDIFF(e.date_available, NOW()) ELSE 10000 END ) as `date_diff`  
		FROM " . DB_PREFIX . "avevent_author ea 
		LEFT JOIN " . DB_PREFIX . "avevent e ON (e.event_id = ea.event_id) 
		WHERE 
		ea.author_id = '" . (int)$data['filter_expert'] . "' 
		AND e.status = '1' 
		";
        if (!empty($data['filter_type'])) {
            if (in_array($data['filter_type'], array('event_new'))) {
                $sql_event .= " AND (e.show_event = '1' AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) ) ";
            } else if (in_array($data['filter_type'], array('event_old'))) {
                $sql_event .= " AND (e.old_type = 'page' AND e.date_available < NOW()) ";
            } else {
                $sql_event .= " AND ( (e.show_event = '1' AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE())) OR (e.old_type = 'page' AND e.date_available < NOW()) ) ";
            }
        }


        $sql_master = "SELECT md.master_id as id, 
		m.date_available, 
		'master' as `type`,
		DATEDIFF(m.date_available, CURRENT_DATE()) as `date_diff`  
		FROM " . DB_PREFIX . "master_description md 
		LEFT JOIN " . DB_PREFIX . "master m ON (m.master_id = md.master_id) 
		LEFT JOIN " . DB_PREFIX . "master_expert me ON (m.master_id = me.master_id) 
		WHERE 
		(m.author_id = '" . (int)$data['filter_expert'] . "' OR me.author_id = '" . (int)$data['filter_expert'] . "' )
		AND m.date_available > NOW() 
		AND m.status = '1' 
		";

        if (!empty($data['filter_type']) && in_array($data['filter_type'], array('master_master', 'master_meetup'))) {
            $sql_journal .= " AND m.type = '" . $this->db->escape(utf8_strtolower(str_replace('master_', '', $data['filter_type']))) . "'";
        }

        $sql = "";

        if (!empty($data['filter_type'])) {
            switch (true) {
                case in_array($data['filter_type'], array('news', 'article', 'opinion', 'video', 'case', 'master_old')):
                    $sql = $sql_journal;
                    break;

                case in_array($data['filter_type'], array('avevent', 'event', 'event_old', 'event_new')):
                    $sql = $sql_event;
                    break;

                case in_array($data['filter_type'], array('master', 'master_all', 'master_master', 'master_meetup')):
                    $sql = $sql_master;
                    break;

                default:
                    $sql = $sql_journal . " UNION " . $sql_event . " UNION " . $sql_master;
            }
        } else {
            $sql = $sql_journal . " UNION " . $sql_event . " UNION " . $sql_master;
        }

        $sort = ' DESC ';

        if (!empty($data['filter_type']) && in_array($data['filter_type'], array('event_new'))) {
            $sort = ' ASC ';
        }
        $sql .= " ORDER BY `date_diff` ASC, `date_available` " . $sort;

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
    }

    public function addExpertMail($data = array())
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "visitor_expert_mail SET 
			visitor_id = '" . (int)$data['visitor_id'] . "', 
			date = NOW(), 
			emails = '" . $this->db->escape($data['emails']) . "', 
			fields = '" . json_encode($data) . "' 
			");
    }

    public function getAllTags($data = array())
    {

        $sql = "SELECT vte.tag_id, LCASE(td.title) as title
		FROM " . DB_PREFIX . "visitor_tag_expert vte 
		LEFT JOIN " . DB_PREFIX . "visitor v ON (v.visitor_id = vte.visitor_id) 
		LEFT JOIN " . DB_PREFIX . "tag_description td ON (vte.tag_id = td.tag_id) 
		WHERE 
		td.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND v.expert = '1' ";

        if (!empty($data['filter_tag'])) {
            $sql .= " AND LCASE(td.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
        }

        $sql .= " GROUP BY td.title";

        $sql .= " ORDER BY LCASE(td.title) ASC";

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
    }

    public function getAllBranches($data = array())
    {

        $sql = "SELECT vtb.tag_id, LCASE(td.title) as title
		FROM " . DB_PREFIX . "visitor_tag_branch vtb 
		LEFT JOIN " . DB_PREFIX . "visitor v ON (v.visitor_id = vtb.visitor_id) 
		LEFT JOIN " . DB_PREFIX . "tag_description td ON (vtb.tag_id = td.tag_id) 
		WHERE 
		td.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND v.expert = '1' ";

        if (!empty($data['filter_tag'])) {
            $sql .= " AND LCASE(td.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
        }

        $sql .= " GROUP BY td.title";

        $sql .= " ORDER BY LCASE(td.title) ASC";

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
    }

    private function mb_ucfirst($string, $encoding = 'UTF-8')
    {
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
}