<?php
class ModelAveventCity extends Model {
	public function addCity($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_city SET title = '" . $this->db->escape($data['title']) . "', status = '" . (int)$data['status'] . "'");

		$city_id = $this->db->getLastId();

		$this->cache->delete('city');

		return $city_id;
	}

	public function editCity($city_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "avevent_city SET title = '" . $this->db->escape($data['title']) . "', status = '" . (int)$data['status'] . "' WHERE city_id = '" . (int)$city_id . "'");

		$this->cache->delete('city');
	}

	public function deleteCity($city_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_city WHERE city_id = '" . (int)$city_id . "'");

		$this->cache->delete('city');
	}

	public function getCity($city_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "avevent_city WHERE city_id = '" . (int)$city_id . "'");

		return $query->row;
	}

	public function getCities($data = array()) {
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


	public function getTotalCities($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "avevent_city c ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}