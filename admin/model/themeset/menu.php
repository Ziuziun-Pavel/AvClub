<?php
class ModelThemesetMenu extends Model {
	public function addMenu($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "thememenu SET name = '" . $this->db->escape($data['name']) . "', title = '" . $this->db->escape($data['title']) . "'");

		$menu_id = $this->db->getLastId();

		if(isset($data['links'])) {
			foreach($data['links'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "thememenu_values SET 
					menu_id = '" . (int)$menu_id . "', 
					title = '" . $this->db->escape($value['title']) . "', 
					type = '" . $this->db->escape($value['type']) . "', 
					category_id = '" . (int)$value['category_id'] . "', 
					manufacturer_id = '" . (int)$value['manufacturer_id'] . "', 
					information_id = '" . (int)$value['information_id'] . "', 
					news_category_id = '" . (int)$value['news_category_id'] . "', 
					news_article = '" . (int)$value['news_article'] . "', 
					standart = '" . $this->db->escape($value['standart']) . "', 
					href = '" . $this->db->escape($value['href']) . "', 
					image = '" . $this->db->escape($value['image']) . "', 
					submenu = '" . (int)$value['submenu'] . "', 
					sort_order = '" . (int)$value['sort_order'] . "', 
					label = '" . (int)$value['label'] . "', 
					status = '" . (int)$value['status'] . "', 
					mobile = '" . (int)$value['mobile'] . "'
					");
			}
		}

		return $menu_id;
	}

	public function editMenu($menu_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "thememenu SET name = '" . $this->db->escape($data['name']) . "', title = '" . $this->db->escape($data['title']) . "' WHERE menu_id = '" . (int)$menu_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "thememenu_values WHERE menu_id = '" . (int)$menu_id . "'");

		if(isset($data['links'])) {
			foreach($data['links'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "thememenu_values SET 
					menu_id = '" . (int)$menu_id . "', 
					title = '" . $this->db->escape($value['title']) . "', 
					type = '" . $this->db->escape($value['type']) . "', 
					category_id = '" . (int)$value['category_id'] . "', 
					manufacturer_id = '" . (int)$value['manufacturer_id'] . "', 
					information_id = '" . (int)$value['information_id'] . "', 
					news_category_id = '" . (int)$value['news_category_id'] . "', 
					news_article = '" . (int)$value['news_article'] . "', 
					standart = '" . $this->db->escape($value['standart']) . "', 
					href = '" . $this->db->escape($value['href']) . "', 
					image = '" . $this->db->escape($value['image']) . "', 
					submenu = '" . (int)$value['submenu'] . "', 
					sort_order = '" . (int)$value['sort_order'] . "', 
					status = '" . (int)$value['status'] . "', 
					label = '" . (int)$value['label'] . "', 
					mobile = '" . (int)$value['mobile'] . "'
					");
			}
		}
	}

	public function deleteMenu($menu_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "thememenu WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "thememenu_values WHERE menu_id = '" . (int)$menu_id . "'");
	}

	public function getMenu($menu_id) {
		$menu_data = array();
		$links_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "thememenu WHERE menu_id = '" . (int)$menu_id . "'");


		$query_links = $this->db->query("SELECT * FROM " . DB_PREFIX . "thememenu_values WHERE menu_id = '" . (int)$menu_id . "' ORDER BY sort_order, title ASC");

		if($query_links->rows) {
			$links_data = $query_links->rows;
		}

		if($query->rows) {
			$menu_data = array(
				'name'	=> $query->row['name'],
				'title'	=> $query->row['title'],
				'links'	=> $links_data
			);
		}
		
		return $menu_data;
	}

	public function getMenus($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "thememenu ";

		$sort_data = array(
			'agd.name',
			'ag.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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


	public function getTotalMenus() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "thememenu");

		return $query->row['total'];
	}

}