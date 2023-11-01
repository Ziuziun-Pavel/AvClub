<?php
class ModelThemesetTag extends Model {

	public function updateTag($data = array()) {

		$tag_search = $this->db->query("SELECT * FROM " . DB_PREFIX . "b24_tags 
			WHERE 
			group_id = '" . (int)$data['group_id'] . "' 
			AND tag_id = '" . (int)$data['tag_id'] . "'
			");

		if($tag_search->num_rows) {
			/* UPDATE */
			$this->db->query("UPDATE " . DB_PREFIX . "b24_tags 
				SET 
				tag = '" . $this->db->escape($data['tag']) . "'
				WHERE 
				group_id = '" . (int)$data['group_id'] . "' 
				AND tag_id = '" . (int)$data['tag_id'] . "' 
				");
			/* # UPDATE */
		}else{
			/* CREATE */
			$this->db->query("INSERT INTO " . DB_PREFIX . "b24_tags 
				SET 
				group_id = '" . (int)$data['group_id'] . "', 
				tag_id = '" . (int)$data['tag_id'] . "', 
				tag = '" . $this->db->escape(htmlspecialchars($data['tag'])) . "' 
				");
			/* # CREATE */
		}

	}

	public function getTagsFromList($list_id, $filter_ids = array()) {

		$return_list = array();

		if(!empty($list_id)) {

			$sql = "SELECT * FROM " . DB_PREFIX . "b24_tags 
			WHERE 
			group_id = '" . (int)$list_id . "'
			";

			if(!empty($filter_ids)) {
				$sql .= " AND tag_id IN(" . implode(",", $filter_ids) . ") ";
			}

			$query = $this->db->query($sql);
			foreach($query->rows as $row) {
				$return_list[$row['tag_id']] = $row['tag'];
			}
		}

		return $return_list;
	}

	public function updateActivity($activity_list = array()) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "b24_activity WHERE 1");

		foreach($activity_list as $item) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "b24_activity 
				SET 
				tag_id = '" . (int)$item['tag_id'] . "', 
				tag = '" . $this->db->escape(htmlspecialchars($item['tag'])) . "' 
				");
		}

	}

	public function getActivity() {

		$return_list = array();

		$sql = "SELECT * FROM " . DB_PREFIX . "b24_activity ";

		$query = $this->db->query($sql);
		foreach($query->rows as $row) {
			$return_list[$row['tag_id']] = $row['tag'];
		}

		return $return_list;

	}

}