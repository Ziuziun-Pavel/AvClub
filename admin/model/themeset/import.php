<?php
class ModelThemesetImport extends Model {
	public function addImport($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "import SET title = '" . $this->db->escape($data['title']) . "', type = '" . $this->db->escape($data['type']) . "', link = '" . $this->db->escape($data['link']) . "', author_id = '" . (!empty($data['author_id']) ? (int)$data['author_id'] : 0) . "'");

		$import_id = $this->db->getLastId();

		if (isset($data['tag'])) {
			foreach ($data['tag'] as $tag_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "import_tag SET import_id = '" . (int)$import_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}
		if (isset($data['tag_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['tag_new'] as $tag_name) {
				$tag_id = $this->model_tag_tag->addTagByName($tag_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "import_tag SET import_id = '" . (int)$import_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}


		return $import_id;
	}

	public function editImport($import_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "import SET title = '" . $this->db->escape($data['title']) . "', type = '" . $this->db->escape($data['type']) . "', link = '" . $this->db->escape($data['link']) . "', author_id = '" . (!empty($data['author_id']) ? (int)$data['author_id'] : 0) . "' WHERE import_id = '" . (int)$import_id . "'");


		$this->db->query("DELETE FROM " . DB_PREFIX . "import_tag WHERE import_id = '" . (int)$import_id . "'");
		if (isset($data['tag'])) {
			foreach ($data['tag'] as $tag_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "import_tag SET import_id = '" . (int)$import_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}
		if (isset($data['tag_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['tag_new'] as $tag_name) {
				$tag_id = $this->model_tag_tag->addTagByName($tag_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "import_tag SET import_id = '" . (int)$import_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}	
	}

	public function deleteImport($import_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "import WHERE import_id = '" . (int)$import_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "import_tag WHERE import_id = '" . (int)$import_id . "'");
	}

	public function getImport($import_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "import WHERE import_id = '" . (int)$import_id . "'");

		return $query->row;
	}

	public function getImports($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "import i WHERE i.import_id > 0 ";


		if (!empty($data['filter_type'])) {
			$sql .= " AND i.type = '" . $this->db->escape($data['filter_type']) . "' ";
		}

		$sort_data = array(
			'i.title',
			'i.type'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY i.journal_id ASC, " . $data['sort'];
		} else {
			$sql .= " ORDER BY i.journal_id ASC, i.title";
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
	}


	public function getImportTags($import_id) {
		$journal_tag_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "import_tag it LEFT JOIN " . DB_PREFIX . "tag_description td ON (it.tag_id = td.tag_id) WHERE import_id = '" . (int)$import_id . "' ORDER BY td.title ASC");

		foreach ($query->rows as $row) {
			$journal_tag_data[] = array(
				'tag_id'	=> $row['tag_id'],
				'tag'			=> $row['title']
			);
		}

		return $journal_tag_data;
	}

	public function getTotalImports($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "import i ";

		if (!empty($data['filter_type'])) {
			$sql .= " WHERE i.type = '" . $this->db->escape($data['filter_type']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}