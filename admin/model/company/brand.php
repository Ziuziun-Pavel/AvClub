<?php
class ModelCompanyBrand extends Model {
	public function addBrand($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "company_brand SET title = '" . $this->db->escape($data['title']) . "'");

		$brand_id = $this->db->getLastId();

		return $brand_id;
	}

	public function addBrandByName($brand_name) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "company_brand SET title = '" . $this->db->escape($brand_name) . "'");

		$brand_id = $this->db->getLastId();

		return $brand_id;
	}

	public function editBrand($brand_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "company_brand SET title = '" . $this->db->escape($data['title']) . "' WHERE brand_id = '" . (int)$brand_id . "'");

		
	}

	public function deleteBrand($brand_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_brand WHERE brand_id = '" . (int)$brand_id . "'");

	}

	public function getBrandById($brand_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company_brand WHERE brand_id = '" . (int)$brand_id . "'");

		return $query->row;
	}

	public function getBrandByName($brand_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company_brand cb WHERE LCASE(cb.title) = '" . $this->db->escape(utf8_strtolower($brand_name)) . "'");

		return $query->row;
	}

	public function getBrand($brand_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company_brand WHERE brand_id = '" . (int)$brand_id . "'");

		return $query->row;
	}

	public function getBrands($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "company_brand c WHERE c.brand_id > '0' ";

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
	}


	public function getTotalBrands($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "company_brand c ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}