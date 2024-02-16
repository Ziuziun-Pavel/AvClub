<?php
class ModelAveventEvent extends Model {

	public function getEvent($event_id, $actual = false) {
		$sql = "SELECT DISTINCT *, ed.title AS title, e.image, 
			(SELECT ac.title FROM " . DB_PREFIX . "avevent_city ac WHERE ac.city_id = e.city_id) as city, 
			(SELECT atd.title FROM " . DB_PREFIX . "avevent_type_description atd WHERE atd.type_id = e.type_id AND atd.language_id = '" . (int)$this->config->get('config_language_id') . "') as type 

			FROM " . DB_PREFIX . "avevent e
			LEFT JOIN " . DB_PREFIX . "avevent_description ed ON (e.event_id = ed.event_id)

			WHERE e.event_id = '" . (int)$event_id . "' AND
			ed.language_id = '" . (int)$this->config->get('config_language_id') . "' AND
			e.status = '1'";

			if($actual) {
				// $sql .= " AND e.date_available > NOW() ";
				$sql .= " AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) ";
			} else{
				$sql .= " AND ( (e.show_event = '1' AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) ) OR (e.old_type = 'page' AND e.date_available < NOW()) ) ";
			}

			$query = $this->db->query($sql);

			 

		if ($query->num_rows) {
			return array(
				'event_id'       	=> $query->row['event_id'],
				'title'             => $query->row['title'],
				'description'      	=> $query->row['description'],
				'meta_title'       	=> $query->row['meta_title'],
				'meta_h1'          	=> $query->row['meta_h1'],
				'meta_description' 	=> $query->row['meta_description'],
				'meta_keyword'     	=> $query->row['meta_keyword'],
				'image'							=> $query->row['image'],
				'image_full'				=> $query->row['image_full'],
				'video'							=> $query->row['video'],
				'video_image'				=> $query->row['video_image'],
				'show_event'				=> $query->row['show_event'],

				'date'							=> $query->row['date'],
				'date_stop'					=> $query->row['date_stop'],
				'time_start'				=> $query->row['time_start'],
				'time_end'					=> $query->row['time_end'],
				'link'							=> $query->row['link'],
				'type'							=> $query->row['type'],
				'city'							=> $query->row['city'],
				'type_id'						=> $query->row['type_id'],
				'city_id'						=> $query->row['city_id'],
				'count'							=> $query->row['count'],
				'price'							=> $query->row['price'],
				'coord'							=> $query->row['coord'],
				'address'						=> $query->row['address'],
				'address_full'			=> $query->row['address_full'],

				'brand_title'				=> $query->row['brand_title'],
				'brand_template'		=> $query->row['brand_template'],

				'speaker_title'			=> $query->row['speaker_title'],
				'present_title'			=> $query->row['present_title'],
				'insta_title'				=> $query->row['insta_title'],

				'ask_title'					=> $query->row['ask_title'],

				'prg_title'					=> $query->row['prg_title'],
				'prg_file_id'					=> $query->row['prg_file_id'],
				'prg_template'			=> $query->row['prg_template'],
				
			);
		} else {
			return false;
		}
	}


	public function getEvents($data = array(), $actual = true) {
		
		$type = 'all';

		if($actual) {
			$type = 'new';
		}

		if(!empty($data['filter_type'])) {
			switch($data['filter_type']) {
				
				case 'event_new':
				case 'new':
				$type = 'new';
				break;

				case 'event_old':
				case 'old':
				$type = 'old';
				break;

				default:
				break;

			}
		}

		if(!$data){ $event_data = $this->cache->get('event.' . (int)$this->config->get('config_language_id')); }
		
		if ($data || empty($event_data)) {
			$sql = "SELECT *, 
			(SELECT ac.title FROM " . DB_PREFIX . "avevent_city ac WHERE ac.city_id = e.city_id) as city, 
			(SELECT atd.title FROM " . DB_PREFIX . "avevent_type_description atd WHERE atd.type_id = e.type_id AND atd.language_id = '" . (int)$this->config->get('config_language_id') . "') as type  
			FROM " . DB_PREFIX . "avevent e LEFT JOIN " . DB_PREFIX . "avevent_description ed ON (e.event_id = ed.event_id) ";

			if(!empty($data['filter_company'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "avevent_company ac ON (ac.event_id = e.event_id) ";
			}

			$sql .= " WHERE ";
			$sql .= " ed.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND e.status = '1' ";

			switch ($type) {

				case 'new':
				$sql .= " AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) ";
				break;

				case 'old':
				$sql .= " AND (e.old_type = 'page' AND e.date_available < NOW()) ";
				break;

				default:
				$sql .= " AND ( (e.show_event = '1' AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) ) OR (e.old_type = 'page' AND e.date_available < NOW()) ) ";
			}

			if(!empty($data['filter_company'])) {
				$sql .= " AND ac.company_id = '" . (int)$data['filter_company'] . "'";
			}

			if (!empty($data['filter_title'])) {
				$sql .= " AND LCASE(ed.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_title'])) . "%'";
			}

			$sort_data = array(
				'ed.title',
				'e.date_available'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY e.date_available";
			}

			if (isset($data['order']) && ($data['order'] === 'DESC')) {
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

			if (!$event_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent e LEFT JOIN " . DB_PREFIX . "avevent_description ed ON (e.event_id = ed.event_id) WHERE ed.language_id = '" . (int)$this->config->get('config_language_id') . "' AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) AND e.status = '1' ORDER BY ed.title");

				$event_data = $query->rows;

				$this->cache->set('event.' . (int)$this->config->get('config_language_id'), $event_data);
			}

			return $event_data;
		}
	}


	public function getTotalEvents($data=array(), $actual = true) {

		$type = 'all';

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "avevent e ";

		if(!empty($data['filter_company'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "avevent_company ac ON (ac.event_id = e.event_id) ";
		}

		$sql .= " WHERE 
		e.status = '1' ";

		if($actual) {
			$type = 'new';
		}

		if(!empty($data['filter_type'])) {
			switch($data['filter_type']) {
				
				case 'event_new':
				case 'new':
				$type = 'new';
				break;

				case 'event_old':
				case 'old':
				$type = 'old';
				break;

				default:
				break;

			}
		}

		switch ($type) {
			
			case 'new':
			$sql .= " AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) ";
			break;
			
			case 'old':
			$sql .= " AND (e.old_type = 'page' AND e.date_available < NOW()) ";
			break;

			default:
			$sql .= " AND ( (e.show_event = '1' AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) ) OR (e.old_type = 'page' AND e.date_available < NOW()) ) ";
		}


		if(!empty($data['filter_company'])) {
			$sql .= " AND ac.company_id = '" . (int)$data['filter_company'] . "' ";
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getEventsLast($data = array()) {
		if(!$data){ $event_data = $this->cache->get('event.last.' . (int)$this->config->get('config_language_id')); }
		
		if ($data || empty($event_data)) {
			$sql = "SELECT e.event_id, e.type_id, e.city_id, e.date, e.date_stop, e.old_type, e.old_link, 
			(SELECT ac.title FROM " . DB_PREFIX . "avevent_city ac WHERE ac.city_id = e.city_id) as city, 
			(SELECT atd.title FROM " . DB_PREFIX . "avevent_type_description atd WHERE atd.type_id = e.type_id AND atd.language_id = '" . (int)$this->config->get('config_language_id') . "') as type 
			FROM " . DB_PREFIX . "avevent e LEFT JOIN " . DB_PREFIX . "avevent_description ed ON (e.event_id = ed.event_id) WHERE ed.language_id = '" . (int)$this->config->get('config_language_id') . "' AND e.date_available < NOW()";

			if (!empty($data['filter_type_id'])) {
				$sql .= " AND e.type_id = '" . (int)$data['filter_type_id'] . "'";
			}

			$sort_data = array(
				'ed.title'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY e.date_available";
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

			if (!$event_data) {
				// $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent e LEFT JOIN " . DB_PREFIX . "avevent_description ed ON (e.event_id = ed.event_id) WHERE ed.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ed.title");

				$sql = "SELECT e.event_id, e.type_id, e.city_id, e.date, e.date_stop, e.old_type, e.old_link, 
				(SELECT ac.title FROM " . DB_PREFIX . "avevent_city ac WHERE ac.city_id = e.city_id) as city, 
				(SELECT atd.title FROM " . DB_PREFIX . "avevent_type_description atd WHERE atd.type_id = e.type_id AND atd.language_id = '" . (int)$this->config->get('config_language_id') . "') as type 
				FROM " . DB_PREFIX . "avevent e LEFT JOIN " . DB_PREFIX . "avevent_description ed ON (e.event_id = ed.event_id) WHERE ed.language_id = '" . (int)$this->config->get('config_language_id') . "' AND e.date_available < NOW()";

				$sql .= " ORDER BY e.date_available";

				$sql .= " ASC";

				$query = $this->db->query($sql);

				$event_data = $query->rows;

				$this->cache->set('event.last.' . (int)$this->config->get('config_language_id'), $event_data);
			}

			return $event_data;
		}
	}

	public function getCompaniesByEvent($event_id) {
		$company_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_company ac 
			LEFT JOIN " . DB_PREFIX . "company c ON (ac.company_id = c.company_id) 
			LEFT JOIN " . DB_PREFIX . "company_description cd ON (ac.company_id = cd.company_id) 
			WHERE 
			ac.event_id = '" . (int)$event_id . "' 
			ORDER BY 
			ac.sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$company_data[] = array(
					'company_id'	=> $row['company_id'],
					'title'				=> $row['title'],
					'image'				=> $row['image'],
					'status'			=> $row['status'],
				);
			}
		}

		return $company_data;
	}


	public function getPresentsByEvent($event_id) {
		$present_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_present ap LEFT JOIN " . DB_PREFIX . "present p ON (ap.present_id = p.present_id) WHERE ap.event_id = '" . (int)$event_id . "' ORDER BY ap.sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$present_data[] = array(
					'present_id'	=> $row['present_id'],
					'title'				=> $row['title'],
					'href'				=> $row['href'],
					'image'				=> $row['image'],
				);
			}
		}

		return $present_data;
	}


	public function getTemplate($event_id) {
		$template_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_template t WHERE t.event_id = '" . (int)$event_id . "' ORDER BY t.sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$template_data[$row['template']] = array(
					'status'	=> $row['status']
				);
			}
		}

		return $template_data;
	}


	public function getAuthorsByEvent($event_id) {
		$this->load->model('visitor/visitor');

		$author_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_author a 
			LEFT JOIN " . DB_PREFIX . "visitor v ON (a.author_id = v.visitor_id) 
			WHERE 
			a.event_id = '" . (int)$event_id . "' 
			AND v.image IS NOT NULL AND v.image <> ''
			ORDER BY a.sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$author_info = $this->model_visitor_visitor->getVisitor($row['visitor_id'], $row['author_exp']);
				
				$author_data[] = array(
					'author_id'	=> $row['visitor_id'],
					'name'			=> $row['name'],	
					'image'			=> $row['image'],	
					'expert'		=> $row['expert'],	
					'exp'				=> !empty($author_info['exp']) ? $author_info['exp'] : '',
				);
			}
		}

		return $author_data;
	}


	public function getAskByEvent($event_id) {
		$ask_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_ask a WHERE a.event_id = '" . (int)$event_id . "' ORDER BY a.sort_order ASC, a.title ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$ask_data[] = array(
					'title'				=> $row['title'],
					'text'				=> $row['text'],	
					'sort_order'	=> $row['sort_order'],	
				);
			}
		}

		return $ask_data;
	}

	public function getInstaByEvent($event_id) {
		$insta_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_insta i WHERE i.event_id = '" . (int)$event_id . "' ORDER BY i.sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$insta_data[] = array(
					'href'				=> $row['href'],	
					'image'				=> $row['image'],	
					'sort_order'	=> $row['sort_order'],	
				);
			}
		}

		return $insta_data;
	}

	public function getPrgByEvent($event_id) {
		$prg_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_prg prg WHERE prg.event_id = '" . (int)$event_id . "' ORDER BY prg.sort_order ASC, prg.time_start ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$prg_data[] = array(
					'title'				=> $row['title'],
					'text'				=> $row['text'],	
					'image'				=> $row['image'],	
					'time_start'	=> $row['time_start'],	
					'time_end'		=> $row['time_end'],	
					'sort_order'	=> $row['sort_order'],	
				);
			}
		}

		return $prg_data;
	}

	public function getPlusByEvent($event_id) {
		$plus_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_plus p WHERE p.event_id = '" . (int)$event_id . "' ORDER BY p.sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$plus_data[] = array(
					'title'				=> $row['title'],
					'text'				=> $row['text'],	
					'image'				=> $row['image'],	
					'sort_order'	=> $row['sort_order'],	
				);
			}
		}

		return $plus_data;
	}

	public function getEventTypes() {
		$types_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_type_description td LEFT JOIN " . DB_PREFIX . "avevent e ON (td.type_id = e.type_id) WHERE e.status = '1' AND td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND e.date_available > NOW() GROUP BY td.type_id ORDER BY td.title ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$types_data[] = array(
					'type_id'			=> $row['type_id'],
					'title'				=> $row['title']	
				);
			}
		}

		return $types_data;
	}

	public function getEventCities() {
		$cities_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_city c LEFT JOIN " . DB_PREFIX . "avevent e ON (c.city_id = e.city_id) WHERE e.status = '1' AND e.date_available > NOW() GROUP BY c.city_id ORDER BY c.title ASC");
		
		$cities_data[] = array(
			'city_id'			=> '',
			'title'				=> 'Все города'	
		);

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$cities_data[] = array(
					'city_id'			=> $row['city_id'],
					'title'				=> $row['title']	
				);
			}
		}

		return $cities_data;
	}

}