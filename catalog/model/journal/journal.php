<?php
class ModelJournalJournal extends Model {
	public function updateViewed($journal_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "journal SET viewed = (viewed + 1) WHERE journal_id = '" . (int)$journal_id . "'");
	}

	public function getJournal($journal_id, $admin = false) {
		

		$sql = "SELECT DISTINCT *, jd.title AS title, j.image,
		j.sort_order 
		FROM " . DB_PREFIX . "journal j
		LEFT JOIN " . DB_PREFIX . "journal_description jd ON (j.journal_id = jd.journal_id)

		WHERE j.journal_id = '" . (int)$journal_id . "' AND
		jd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if(!$admin) {
			$sql .= " AND	j.status = '1' AND j.date_available <= NOW()";
		}

		
		$query = $this->db->query($sql);

		if ($query->num_rows) {
			return array(
				'journal_id'       	=> $query->row['journal_id'],
				'title'             => $query->row['title'],
				'video'             => $query->row['video'],
				'link'             => $query->row['link'],
				'master_old'        => $query->row['master_old'],
				'type'             	=> $query->row['type'],
				'preview'						=> $query->row['preview'],
				'description'      	=> $query->row['description'],
				'meta_title'       	=> $query->row['meta_title'],
				'meta_h1'          	=> $query->row['meta_h1'],
				'meta_description' 	=> $query->row['meta_description'],
				'meta_keyword'     	=> $query->row['meta_keyword'],
				'author_id'         => $query->row['author_id'],
				'author_exp'         => $query->row['author_exp'],
				'master_id'         => $query->row['master_id'],
				'image'							=> $query->row['image'],
				'image_show'				=> $query->row['image_show'],
				'date_available'		=> $query->row['date_available'],
				'sort_order'				=> $query->row['sort_order'],
				'status'						=> $query->row['status']
			);
		} else {
			return false;
		}
	}

	public function getJournals($data = array()) {
		$sql = "SELECT j.journal_id FROM " . DB_PREFIX . "journal j ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "journal_description jd ON (j.journal_id = jd.journal_id) ";

		if (!empty($data['filter_tag'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "journal_tag t ON (j.journal_id = t.journal_id) ";
		}

		$sql .= " WHERE jd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND j.status = '1' AND j.date_available <= NOW() ";


		if (!empty($data['filter_tag'])) {
			$sql .= " AND t.tag_id = '" . (int)$data['filter_tag'] . "' ";
		}
		

		if (!empty($data['filter_type'])) {
			$sql .= " AND j.type = '" . $this->db->escape($data['filter_type']) . "'";
		}

		if (!empty($data['filter_master']) && $data['filter_master']) {
			$sql .= " AND j.master_old = '1'";
		}


		if (!empty($data['filter_name'])) {
			$sql .= " AND (";

			$implode = array();

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

			foreach ($words as $word) {
				$implode[] = "jd.title LIKE '%" . $this->db->escape($word) . "%'";
			}

			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}

			if (!empty($data['filter_description'])) {
				$sql .= " OR jd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}

			$sql .= ")";
		}

		$sql .= " GROUP BY j.journal_id";

		$sql .= " ORDER BY j.date_available DESC, LCASE(jd.title) DESC";


		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$journal_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$journal_data[$result['journal_id']] = $this->getJournal($result['journal_id']);
		}

		return $journal_data;
	}


	public function getJournalLayoutId($journal_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal_to_layout WHERE journal_id = '" . (int)$journal_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getJournalGallery($journal_id, $gallery_id) {
		$data = array();

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "journal_gallery WHERE journal_id = '" . (int)$journal_id . "' AND gallery_id = '" . (int)$gallery_id . "'  ORDER BY sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$data[] = $row['image'];
			}
		}

		return $data;
	}

	public function getColumnById($journal_id, $column_id) {
		
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "journal_column WHERE journal_id = '" . (int)$journal_id . "' AND column_id = '" . (int)$column_id . "'  ORDER BY column_id ASC");

		return $query->row;
	}

	public function getTotalJournals($data = array()) {
		$sql = "SELECT COUNT(DISTINCT j.journal_id) AS total FROM " . DB_PREFIX . "journal j ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "journal_description jd ON (j.journal_id = jd.journal_id) ";

		if (!empty($data['filter_tag'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "journal_tag t ON (j.journal_id = t.journal_id) ";
		}

		$sql .= " WHERE jd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND j.status = '1' AND j.date_available <= NOW() ";

		if (!empty($data['filter_tag'])) {
			$sql .= " AND t.tag_id = '" . (int)$data['filter_tag'] . "' ";
		}

		if (!empty($data['filter_type'])) {
			$sql .= " AND j.type = '" . $this->db->escape($data['filter_type']) . "'";
		}

		if (!empty($data['filter_master']) && $data['filter_master']) {
			$sql .= " AND j.master_old = '1'";
		}


		if (!empty($data['filter_name'])) {
			$sql .= " AND (";

			$implode = array();

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

			foreach ($words as $word) {
				$implode[] = "jd.title LIKE '%" . $this->db->escape($word) . "%'";
			}

			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}

			if (!empty($data['filter_description'])) {
				$sql .= " OR jd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}

			$sql .= ")";
		}


		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getRelated($journal_id) {
		$tag_list = array();
		
		
		$sql = "SELECT t2.journal_id, count(*) as cnt FROM " . DB_PREFIX . "journal_tag t 
		LEFT JOIN " . DB_PREFIX . "journal_tag t2 ON (t.tag_id = t2.tag_id) 
		LEFT JOIN " . DB_PREFIX . "journal j ON (j.journal_id = t2.journal_id) 
		WHERE t.journal_id = '".(int)$journal_id."' 
		AND t2.journal_id <> '".(int)$journal_id."' 
		AND j.status = '1' 
		AND j.date_available <= NOW()  
		GROUP BY t2.journal_id 
		ORDER BY cnt DESC, t2.journal_id DESC 
		LIMIT 0,8";
	
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getCopies($journal_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal_copy WHERE journal_id = '" . (int)$journal_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getCase($journal_id = 0) {
		$case_data = array();
		$case_attr = array();

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "journal_case WHERE journal_id = '" . (int)$journal_id . "'");

		if($query->num_rows) {
			$query_attr = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "journal_case_attr WHERE journal_id = '" . (int)$journal_id . "' AND case_id='" . (int)$query->row['case_id'] . "' ORDER BY sort_order ASC");
			if($query_attr->num_rows) {
				foreach($query_attr->rows as $attr) {
					$case_attr[] = array(
						'title'					=>	$attr['title'],
						'text'					=>	$attr['text'],
						'catalog'				=>	$attr['catalog'],
						'sort_order'		=>	$attr['sort_order'],
					);
				}
			}
			$case_data = array(
				'title'					=>	$query->row['title'],
				'description'		=>	$query->row['description'],
				'logo'					=>	$query->row['logo'],
				'template'			=>	$query->row['template'],
				'bottom'				=>	$query->row['bottom'],
				'attr'					=>	$case_attr
			);
		}
		return $case_data;
	}

	public function getJournalExperts($journal_id) {

		$sql = "SELECT * FROM " . DB_PREFIX . "journal_expert je
		LEFT JOIN " . DB_PREFIX . "visitor v ON (je.author_id = v.visitor_id) 
		WHERE 
		je.journal_id = '" . (int)$journal_id . "' 
		ORDER BY 
		v.lastname ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

}
