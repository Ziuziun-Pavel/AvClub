<?php
class ModelAvbannerBanner extends Model {
	public function addBanner($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "avbanner SET 
			name = '" . $this->db->escape($data['name']) . "', 
			date_start = '" . $this->db->escape($data['date_start']) . "', 
			date_stop = '" . $this->db->escape($data['date_stop']) . "', 
			image_pc = '" . $this->db->escape($data['image_pc']) . "', 
			image_mob = '" . $this->db->escape($data['image_mob']) . "', 
			type = '" . $this->db->escape($data['type']) . "', 
			link = '" . $this->db->escape($data['link']) . "', 
			target = '" . (int)$data['target'] . "', 
			status = '" . (int)$data['status'] . "'");

		$banner_id = $this->db->getLastId();

		$this->cache->delete('banner');

		return $banner_id;
	}

	public function editBanner($banner_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "avbanner SET 
			name = '" . $this->db->escape($data['name']) . "', 
			date_start = '" . $this->db->escape($data['date_start']) . "', 
			date_stop = '" . $this->db->escape($data['date_stop']) . "', 
			image_pc = '" . $this->db->escape($data['image_pc']) . "', 
			image_mob = '" . $this->db->escape($data['image_mob']) . "', 
			type = '" . $this->db->escape($data['type']) . "', 
			link = '" . $this->db->escape($data['link']) . "', 
			target = '" . (int)$data['target'] . "', 
			status = '" . (int)$data['status'] . "' 
			WHERE banner_id = '" . (int)$banner_id . "'");

		
		$this->cache->delete('banner');
	}

	public function copyBanner($banner_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "avbanner b WHERE b.banner_id = '" . (int)$banner_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['name'] = $query->row['name'] . ' - копия';
			$data['status'] = '0';

			$this->addBanner($data);
		}
	}

	public function deleteBanner($banner_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "avbanner WHERE banner_id = '" . (int)$banner_id . "'");
		$this->cache->delete('banner');
	}

	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "avbanner WHERE banner_id = '" . (int)$banner_id . "'");

		return $query->row;
	}

	public function getBanners($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "avbanner b WHERE 1 ";


			if (!empty($data['filter_type'])) {
				$sql .= " AND b.type = '" . $this->db->escape($data['filter_type']) . "' ";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " AND b.name LIKE '%" . $this->db->escape($data['filter_name']) . "%' ";
			}

			$sort_data = array(
				'b.name',
				'b.date_start',
				'b.date_stop',
				'b.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY b.date_start";
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
			$banner_data = $this->cache->get('banner.' . (int)$this->config->get('config_language_id'));

			if (!$banner_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avbanner b  WHERE 1 ORDER BY b.name");

				$banner_data = $query->rows;

				$this->cache->set('banner.' . (int)$this->config->get('config_language_id'), $banner_data);
			}

			return $banner_data;
		}
	}


	public function getTotalBanners($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "avbanner b WHERE 1 ";

		if (!empty($data['filter_type'])) {
			$sql .= " AND b.type = '" . $this->db->escape($data['filter_type']) . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND b.name LIKE '%" . $this->db->escape($data['filter_name']) . "%' ";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}