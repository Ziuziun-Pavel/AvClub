<?php
class ModelTagTag extends Model {
	public function updateViewed($tag_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "tag SET viewed = (viewed + 1) WHERE tag_id = '" . (int)$tag_id . "'");
	}

	public function getTag($tag_id) {
		$query = $this->db->query("SELECT DISTINCT *, td.title AS title 
			FROM " . DB_PREFIX . "tag t
			LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id)
			WHERE t.tag_id = '" . (int)$tag_id . "' 
			AND	td.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND t.status = '1' ");

		if ($query->num_rows) {
			return array(
				'tag_id'  			 	=> $query->row['tag_id'],
				'title'        	    => $this->mb_ucfirst($query->row['title']),
				'description'  	    => $query->row['description'],
				'meta_title'     	  => $query->row['meta_title'],
				'meta_h1'         	=> $query->row['meta_h1'],
				'meta_description' 	=> $query->row['meta_description'],
				'meta_keyword'     	=> $query->row['meta_keyword'],
				'status'           	=> $query->row['status']
			);
		} else {
			return false;
		}
	}

	public function getTags($data = array()) {
		$sql = "SELECT t.tag_id FROM " . DB_PREFIX . "tag t ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) ";

		if (!empty($data['journal_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "journal_tag jt ON (t.tag_id = jt.tag_id) ";
		}

		$sql .= " WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1' ";

		if (!empty($data['journal_id'])) {
			$sql .= " AND jt.journal_id = '" . (int)$data['journal_id'] . "' ";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND (";

			$implode = array();

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

			foreach ($words as $word) {
				$implode[] = "td.title LIKE '%" . $this->db->escape($word) . "%'";
			}

			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}

			if (!empty($data['filter_description'])) {
				$sql .= " OR td.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}

			$sql .= ")";
		}

		$sql .= " GROUP BY t.tag_id";

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

		$tag_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$tag_data[$result['tag_id']] = $this->getTag($result['tag_id']);
		}

		return $tag_data;
	}

    public function getProductTags($data = array()) {
        $sql = "SELECT td.tag_id FROM " . DB_PREFIX . "b24_tags t ";
        $sql .= " LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag = td.title) ";
        $sql .= " WHERE t.group_id = '93'";

        if (!empty($data['journal_id'])) {
            $sql .= " AND td.journal_id = '" . (int)$data['journal_id'] . "'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND td.title LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sql .= " GROUP BY td.tag_id";
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

        $tag_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $tag_data[$result['tag_id']] = $this->getTag($result['tag_id']);
        }

        return $tag_data;
    }

    public function getIndustryTags($data = array()) {
        $sql = "SELECT td.tag_id FROM " . DB_PREFIX . "b24_tags t ";
        $sql .= " LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag = td.title) ";
        $sql .= " WHERE t.group_id = '91'";

        if (!empty($data['journal_id'])) {
            $sql .= " AND td.journal_id = '" . (int)$data['journal_id'] . "'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND td.title LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sql .= " GROUP BY td.tag_id";
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

        $tag_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $tag_data[$result['tag_id']] = $this->getTag($result['tag_id']);
        }

        return $tag_data;
    }

    public function getB24IdByTagDescriptions($tag_title) {
        $b24_tag_id = 0;

        // Escape the single quote within the string
        $escaped_tag_title = $this->db->escape($tag_title);

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "b24_tags WHERE tag = '" . $escaped_tag_title . "'");

        foreach ($query->rows as $result) {
            $b24_tag_id = (int)$result['tag_id'];
        }

        return $b24_tag_id;
    }


	public function getTagLayoutId($tag_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tag_to_layout WHERE tag_id = '" . (int)$tag_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}


	public function getTotalTags($data = array()) {
		$sql = "SELECT COUNT(DISTINCT t.tag_id) AS total FROM " . DB_PREFIX . "tag t ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1'";


		if (!empty($data['filter_name']) ) {
			$sql .= " AND (";

			$implode = array();

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

			foreach ($words as $word) {
				$implode[] = "td.title LIKE '%" . $this->db->escape($word) . "%'";
			}

			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}

			if (!empty($data['filter_description'])) {
				$sql .= " OR td.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}

			$sql .= ")";
		}


		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function addTagByName($tag_name) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "tag SET status = '1'");

		$tag_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "tag_description SET tag_id = '" . (int)$tag_id . "', language_id = '" . (int)$this->config->get('config_language_id') . "', title = '" . $this->db->escape($tag_name) . "', description = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = ''");

		$this->db->query("INSERT INTO " . DB_PREFIX . "tag_to_layout SET tag_id = '" . (int)$tag_id . "', store_id = '0', layout_id = '0'");

		$this->cache->delete('tag');

		return $tag_id;
	}
	public function getTagByName($tag_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "tag t LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) WHERE LCASE(td.title) = '" . $this->db->escape(utf8_strtolower($tag_name)) . "' OR LCASE(td.title) = '" . $this->db->escape(utf8_strtolower(htmlspecialchars($tag_name))) . "'");

		return $query->row;
	}

	
	private function mb_ucfirst($string, $encoding = 'UTF-8'){
		$strlen = mb_strlen($string, $encoding);
		$firstChar = mb_substr($string, 0, 1, $encoding);
		$then = mb_substr($string, 1, $strlen - 1, $encoding);
		return mb_strtoupper($firstChar, $encoding) . $then;
	}


}
