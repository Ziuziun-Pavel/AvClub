<?php
class ModelAveventEvent extends Model {
	public function addEvent($data) {
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

		foreach ($data['event_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_description SET event_id = '" . (int)$event_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if(isset($data['template'])) {
			$template_counter = 0;
			foreach($data['template'] as $template=>$status) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_template SET template = '" . $this->db->escape($template) . "',  event_id = '" . (int)$event_id . "',  status = '" . (int)$status . "',  sort_order = '" . (int)$template_counter . "'");
				$template_counter++;
			}
		}

		if(isset($data['author'])) {
			foreach($data['author'] as $key=>$author) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_author SET 
					author_id = '" . (int)$author . "',  
					author_exp = '" . (!empty($data['author_exp'][$key]) ? (int)$data['author_exp'][$key] : 0) . "', 
					event_id = '" . (int)$event_id . "',  
					sort_order = '0'");
			}
		}

		if(isset($data['company'])) {
			foreach($data['company'] as $key=>$company) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_company SET company_id = '" . (int)$company . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
			}
		}

		if(isset($data['present'])) {
			foreach($data['present'] as $key=>$present) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_present SET present_id = '" . (int)$present . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
			}
		}

		if(isset($data['ask'])) {
			foreach($data['ask'] as $ask) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_ask SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($ask['title']) . "', text = '" . $this->db->escape($ask['text']) . "',  sort_order = '" . (int)$ask['sort_order'] . "'");
			}
		}

		if(isset($data['prg'])) {
			foreach($data['prg'] as $prg) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_prg SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($prg['title']) . "', time_start = '" . $this->db->escape($prg['time_start']) . "', time_end = '" . $this->db->escape($prg['time_end']) . "', image = '" . $this->db->escape($prg['image']) . "', text = '" . $this->db->escape($prg['text']) . "',  sort_order = '" . (int)$prg['sort_order'] . "'");
			}
		}

		if(isset($data['plus'])) {
			foreach($data['plus'] as $plus) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_plus SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($plus['title']) . "', image = '" . $this->db->escape($plus['image']) . "', text = '" . $this->db->escape($plus['text']) . "',  sort_order = '" . (int)$plus['sort_order'] . "'");
			}
		}

		if(isset($data['insta'])) {
			foreach($data['insta'] as $insta) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_insta SET  event_id = '" . (int)$event_id . "', href = '" . $this->db->escape($insta['href']) . "', image = '" . $this->db->escape($insta['image']) . "',  sort_order = '" . (int)$insta['sort_order'] . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('event');

		return $event_id;
	}

	public function editEvent($event_id, $data) {
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
		if(isset($data['template'])) {
			$template_counter = 0;
			foreach($data['template'] as $template=>$status) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_template SET template = '" . $this->db->escape($template) . "',  event_id = '" . (int)$event_id . "',  status = '" . (int)$status . "',  sort_order = '" . (int)$template_counter . "'");
				$template_counter++;
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_author WHERE event_id = '" . (int)$event_id . "'");
		if(isset($data['author'])) {
			foreach($data['author'] as $key=>$author) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_author SET 
					author_id = '" . (int)$author . "',  
					author_exp = '" . (!empty($data['author_exp'][$key]) ? (int)$data['author_exp'][$key] : 0) . "', 
					event_id = '" . (int)$event_id . "',  
					sort_order = '0'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_present WHERE event_id = '" . (int)$event_id . "'");
		if(isset($data['present'])) {
			foreach($data['present'] as $key=>$present) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_present SET present_id = '" . (int)$present . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_company WHERE event_id = '" . (int)$event_id . "'");
		if(isset($data['company'])) {
			foreach($data['company'] as $key=>$company) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_company SET company_id = '" . (int)$company . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_ask WHERE event_id = '" . (int)$event_id . "'");
		if(isset($data['ask'])) {
			foreach($data['ask'] as $ask) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_ask SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($ask['title']) . "', text = '" . $this->db->escape($ask['text']) . "',  sort_order = '" . (int)$ask['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_prg WHERE event_id = '" . (int)$event_id . "'");
		if(isset($data['prg'])) {
			foreach($data['prg'] as $prg) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_prg SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($prg['title']) . "', time_start = '" . $this->db->escape($prg['time_start']) . "', time_end = '" . $this->db->escape($prg['time_end']) . "', image = '" . $this->db->escape($prg['image']) . "', text = '" . $this->db->escape($prg['text']) . "',  sort_order = '" . (int)$prg['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_insta WHERE event_id = '" . (int)$event_id . "'");
		if(isset($data['insta'])) {
			foreach($data['insta'] as $insta) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_insta SET  event_id = '" . (int)$event_id . "', href = '" . $this->db->escape($insta['href']) . "', image = '" . $this->db->escape($insta['image']) . "',  sort_order = '" . (int)$insta['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_plus WHERE event_id = '" . (int)$event_id . "'");
		if(isset($data['plus'])) {
			foreach($data['plus'] as $plus) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_plus SET  event_id = '" . (int)$event_id . "', title = '" . $this->db->escape($plus['title']) . "', image = '" . $this->db->escape($plus['image']) . "', text = '" . $this->db->escape($plus['text']) . "',  sort_order = '" . (int)$plus['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('event');
	}

	public function deleteEvent($event_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_description WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_template WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_author WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_company WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_ask WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_prg WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_plus WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_insta WHERE event_id = '" . (int)$event_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_present WHERE event_id = '" . (int)$event_id . "'");

		$this->cache->delete('event');
	}

	public function getEventByName($event_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "avevent e LEFT JOIN " . DB_PREFIX . "avevent_description ed ON (e.event_id = ed.event_id) WHERE LCASE(ed.title) = '" . $this->db->escape(utf8_strtolower($event_name)) . "'");

		return $query->row;
	}
	public function getEvent($event_id) {
		$query = $this->db->query("SELECT DISTINCT *, 
			(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "' LIMIT 1) AS keyword, 
			(SELECT ac.title FROM " . DB_PREFIX . "avevent_city ac WHERE ac.city_id = e.city_id) as city, 
			(SELECT atd.title FROM " . DB_PREFIX . "avevent_type_description atd WHERE atd.type_id = e.type_id AND atd.language_id = '" . (int)$this->config->get('config_language_id') . "') as type 
			FROM " . DB_PREFIX . "avevent e LEFT JOIN " . DB_PREFIX . "avevent_description ed ON (e.event_id = ed.event_id) WHERE e.event_id = '" . (int)$event_id . "' AND ed.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getEvents($data = array()) {
		if ($data) {
			$sql = "SELECT *, 
			(SELECT ac.title FROM " . DB_PREFIX . "avevent_city ac WHERE ac.city_id = e.city_id) as city, 
			(SELECT atd.title FROM " . DB_PREFIX . "avevent_type_description atd WHERE atd.type_id = e.type_id AND atd.language_id = '" . (int)$this->config->get('config_language_id') . "') as type 
			 FROM " . DB_PREFIX . "avevent e LEFT JOIN " . DB_PREFIX . "avevent_description ed ON (e.event_id = ed.event_id) WHERE ed.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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

			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
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
			$event_data = $this->cache->get('event.' . (int)$this->config->get('config_language_id'));

			if (!$event_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent e LEFT JOIN " . DB_PREFIX . "avevent_description ed ON (e.event_id = ed.event_id) WHERE ed.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ed.title");

				$event_data = $query->rows;

				$this->cache->set('event.' . (int)$this->config->get('config_language_id'), $event_data);
			}

			return $event_data;
		}
	}

	public function getEventDescriptions($event_id) {
		$event_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_description WHERE event_id = '" . (int)$event_id . "'");

		foreach ($query->rows as $result) {
			$event_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_h1'          => $result['meta_h1'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $event_description_data;
	}

	public function getTotalEvents($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "avevent e ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}


	public function getCompaniesByEvent($event_id) {
		$company_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_company c LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) WHERE c.event_id = '" . (int)$event_id . "' ORDER BY c.sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$company_data[] = array(
					'company_id'	=> $row['company_id'],
					'title'				=> $row['title']	
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
					'title'				=> $row['title']	
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
			ORDER BY 
			a.sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$exp_list = $this->model_visitor_visitor->getExpByVisitor($row['visitor_id']);
				$author_data[] = array(
					'author_id'		=> $row['visitor_id'],
					'author_exp'	=> $row['author_exp'],
					'name'				=> $row['name'],	
					'exp'					=> $row['exp'],
					'exp_list'		=> $exp_list
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

}