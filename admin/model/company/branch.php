<?php
class ModelCompanyBranch extends Model {
	public function addBranch($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "company_branch SET title = '" . $this->db->escape($data['title']) . "'");

		$branch_id = $this->db->getLastId();

		return $branch_id;
	}

	public function addBranchByName($branch_name) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "company_branch SET title = '" . $this->db->escape($branch_name) . "'");

		$branch_id = $this->db->getLastId();

		return $branch_id;
	}

	public function editBranch($branch_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "company_branch SET title = '" . $this->db->escape($data['title']) . "' WHERE branch_id = '" . (int)$branch_id . "'");

		
	}

	public function deleteBranch($branch_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_branch WHERE branch_id = '" . (int)$branch_id . "'");

	}

	public function getBranchById($branch_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company_branch WHERE branch_id = '" . (int)$branch_id . "'");

		return $query->row;
	}

	public function getBranchByName($branch_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company_branch cb WHERE LCASE(cb.title) = '" . $this->db->escape(utf8_strtolower($branch_name)) . "'");

		return $query->row;
	}

	public function getBranch($branch_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company_branch WHERE branch_id = '" . (int)$branch_id . "'");

		return $query->row;
	}

	public function getBranches($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "company_branch c WHERE c.branch_id > '0' ";

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


	public function getTotalBranches($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "company_branch c ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}