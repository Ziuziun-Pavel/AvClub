<?php
class ModelMasterMaster extends Model {
	public function updateViewed($master_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "master SET viewed = (viewed + 1) WHERE master_id = '" . (int)$master_id . "'");
	}

	public function getMaster($master_id) {
		$this->load->model('visitor/visitor');

		$query = $this->db->query("SELECT DISTINCT *, md.title AS title, m.image 
		FROM " . DB_PREFIX . "master m
		LEFT JOIN " . DB_PREFIX . "visitor a ON (a.visitor_id = m.author_id)
		LEFT JOIN " . DB_PREFIX . "master_description md ON (m.master_id = md.master_id)
		WHERE 
		m.master_id = '" . (int)$master_id . "' 
		AND	md.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND m.status = '1' 
		AND m.date_available > NOW()");

		if ($query->num_rows) {
			$author_info = $this->model_visitor_visitor->getVisitor($query->row['author_id'], $query->row['author_exp']);

			return array(
				'master_id'  			 	=> $query->row['master_id'],
				'title'        	    => $query->row['title'],
				'author'        	  => $query->row['name'],
				'exp'        	    	=> !empty($author_info['exp']) ? $author_info['exp'] : '',
				'preview'     			=> $query->row['preview'],
				'description'  	    => $query->row['description'],
				'meta_title'     	  => $query->row['meta_title'],
				'meta_h1'         	=> $query->row['meta_h1'],
				'meta_description' 	=> $query->row['meta_description'],
				'meta_keyword'     	=> $query->row['meta_keyword'],
				'link'            	=> $query->row['link'],
				'type'            	=> $query->row['type'],
				'image'            	=> $query->row['image'],
				'logo'            	=> $query->row['logo'],
				'date_available'   	=> $query->row['date_available'],
				'date'   						=> date('d.m.Y', strtotime($query->row['date_available'])),
				'time'   						=> date('H:i', strtotime($query->row['date_available'])),
				'status'           	=> $query->row['status']
			);
		} else {
			return false;
		}
	}

	public function getMasters($data = array()) {
		$sql = "SELECT m.master_id FROM " . DB_PREFIX . "master m ";

		if (!empty($data['filter_company'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "master_company mc ON (m.master_id = mc.master_id) ";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "master_description md ON (m.master_id = md.master_id) 
		WHERE 
		md.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND m.status = '1' 
		AND m.date_available > NOW() ";

		if (!empty($data['filter_company'])) {
			$sql .= " AND mc.company_id = '" . (int)$data['filter_company'] . "'";
		}

		if (!empty($data['filter_type'])) {
			$sql .= " AND m.type = '" . $this->db->escape($data['filter_type']) . "'";
		}
		
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "md.title LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR md.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "md.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			$sql .= ")";
		}

		$sql .= " GROUP BY m.master_id";

		$sql .= " ORDER BY m.date_available ASC, LCASE(md.title) ASC";


		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$master_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$master_data[$result['master_id']] = $this->getMaster($result['master_id']);
		}

		return $master_data;
	}


	public function getMasterLayoutId($master_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master_to_layout WHERE master_id = '" . (int)$master_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}


	public function getTotalMasters($data = array()) {
		$sql = "SELECT COUNT(DISTINCT m.master_id) AS total FROM " . DB_PREFIX . "master m ";

		if (!empty($data['filter_company'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "master_company mc ON (m.master_id = mc.master_id) ";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "master_description md ON (m.master_id = md.master_id) 
		WHERE 
		md.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND m.status = '1' 
		AND m.date_available <= NOW() ";

		if (!empty($data['filter_company'])) {
			$sql .= " AND mc.company_id = '" . (int)$data['filter_company'] . "'";
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "md.title LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR md.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "md.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}
			$sql .= ")";
		}


		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}
