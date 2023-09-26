<?php
class ModelAveventType extends Model {
	public function addType($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_type SET status = '" . (int)$data['status'] . "'");

		$type_id = $this->db->getLastId();

		foreach ($data['type_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_type_description SET type_id = '" . (int)$type_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'type_id=" . (int)$type_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('type');

		return $type_id;
	}

	public function editType($type_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "avevent_type SET status = '" . (int)$data['status'] . "' WHERE type_id = '" . (int)$type_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_type_description WHERE type_id = '" . (int)$type_id . "'");

		foreach ($data['type_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_type_description SET type_id = '" . (int)$type_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'type_id=" . (int)$type_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'type_id=" . (int)$type_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('type');
	}

	public function deleteType($type_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_type WHERE type_id = '" . (int)$type_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_type_description WHERE type_id = '" . (int)$type_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "journal_type WHERE type_id = '" . (int)$type_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'type_id=" . (int)$type_id . "'");

		$this->cache->delete('type');
	}

	public function getTypeByName($type_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "avevent_type t LEFT JOIN " . DB_PREFIX . "avevent_type_description td ON (t.type_id = td.type_id) WHERE LCASE(td.title) = '" . $this->db->escape(utf8_strtolower($type_name)) . "'");

		return $query->row;
	}
	public function getType($type_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'type_id=" . (int)$type_id . "' LIMIT 1) AS keyword FROM " . DB_PREFIX . "avevent_type WHERE type_id = '" . (int)$type_id . "'");

		return $query->row;
	}

	public function getTypes($data = array()) {
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

	public function getTypeDescriptions($type_id) {
		$type_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_type_description WHERE type_id = '" . (int)$type_id . "'");

		foreach ($query->rows as $result) {
			$type_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_h1'          => $result['meta_h1'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $type_description_data;
	}

	public function getTotalTypes($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "avevent_type t ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}