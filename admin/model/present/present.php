<?php
class ModelPresentPresent extends Model {
	public function addPresent($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "present SET title = '" . $this->db->escape($data['title']) . "', href = '" . $this->db->escape($data['href']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "'");

		$present_id = $this->db->getLastId();

		// present
		if(!empty($data['event'])) {
			foreach($data['event'] as $event_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_present SET present_id = '" . (int)$present_id . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
			}
		}

		$this->cache->delete('present');

		return $present_id;
	}

	public function editPresent($present_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "present SET title = '" . $this->db->escape($data['title']) . "', href = '" . $this->db->escape($data['href']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "' WHERE present_id = '" . (int)$present_id . "'");

		// present
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_present WHERE present_id = '" . (int)$present_id . "'");
		if(!empty($data['event'])) {
			foreach($data['event'] as $event_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_present SET present_id = '" . (int)$present_id . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
			}
		}

		$this->cache->delete('present');
	}

	public function deletePresent($present_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "present WHERE present_id = '" . (int)$present_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_present WHERE present_id = '" . (int)$present_id . "'");

		$this->cache->delete('present');
	}

	public function getPresentByName($present_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "present p WHERE LCASE(p.title) = '" . $this->db->escape(utf8_strtolower($present_name)) . "'");

		return $query->row;
	}

	public function getPresent($present_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "present WHERE present_id = '" . (int)$present_id . "'");

		return $query->row;
	}

	public function getPresents($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "present p WHERE p.present_id > '0' ";

			if (!empty($data['filter_title'])) {
				$sql .= " AND LCASE(p.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_title'])) . "%'";
			}

			$sort_data = array(
				'p.title'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY p.title";
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
			$present_data = $this->cache->get('present.' . (int)$this->config->get('config_language_id'));

			if (!$present_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "present p ORDER BY p.title");

				$present_data = $query->rows;

				$this->cache->set('present.' . (int)$this->config->get('config_language_id'), $present_data);
			}

			return $present_data;
		}
	}

	public function getEventsByPresent($present_id) {
		$event_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_present ap LEFT JOIN " . DB_PREFIX . "avevent e ON (e.event_id = ap.event_id) WHERE ap.present_id = '" . (int)$present_id . "' ORDER BY e.date_available ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$event_data[$row['event_id']] = $row['event_id'];
			}
		}

		return $event_data;
	}


	public function getTotalPresents($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "present p ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}