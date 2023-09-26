<?php
class ModelTagTag extends Model {
	public function addTag($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "tag SET status = '" . (int)$data['status'] . "'");

		$tag_id = $this->db->getLastId();

		foreach ($data['tag_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tag_description SET tag_id = '" . (int)$tag_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['tag_layout'])) {
			foreach ($data['tag_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tag_to_layout SET tag_id = '" . (int)$tag_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'tag_id=" . (int)$tag_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('tag');

		return $tag_id;
	}

	public function addTagByName($tag_name) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "tag SET status = '1'");

		$tag_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "tag_description SET tag_id = '" . (int)$tag_id . "', language_id = '" . (int)$this->config->get('config_language_id') . "', title = '" . $this->db->escape($tag_name) . "', description = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = ''");

		$this->db->query("INSERT INTO " . DB_PREFIX . "tag_to_layout SET tag_id = '" . (int)$tag_id . "', store_id = '0', layout_id = '0'");

		$this->load->model('themeset/url');
		$keyword = $this->model_themeset_url->getUrl($tag_name, 'tag');
		if ($keyword) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'tag_id=" . (int)$tag_id . "', keyword = '" . $this->db->escape($keyword) . "'");
		}

		$this->cache->delete('tag');

		return $tag_id;
	}

	public function editTag($tag_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "tag SET status = '" . (int)$data['status'] . "' WHERE tag_id = '" . (int)$tag_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "tag_description WHERE tag_id = '" . (int)$tag_id . "'");

		foreach ($data['tag_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tag_description SET tag_id = '" . (int)$tag_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "tag_to_layout WHERE tag_id = '" . (int)$tag_id . "'");

		if (isset($data['tag_layout'])) {
			foreach ($data['tag_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tag_to_layout SET tag_id = '" . (int)$tag_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'tag_id=" . (int)$tag_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'tag_id=" . (int)$tag_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('tag');
	}

	public function deleteTag($tag_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "tag WHERE tag_id = '" . (int)$tag_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tag_description WHERE tag_id = '" . (int)$tag_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tag_to_layout WHERE tag_id = '" . (int)$tag_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_tag WHERE tag_id = '" . (int)$tag_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'tag_id=" . (int)$tag_id . "'");

		$this->cache->delete('tag');
	}

	public function getTagById($tag_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "tag t LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) WHERE t.tag_id = '" . (int)$tag_id . "'");

		return $query->row;
	}

	public function getTagByName($tag_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "tag t LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) WHERE LCASE(td.title) = '" . $this->db->escape(utf8_strtolower($tag_name)) . "'");

		return $query->row;
	}
	public function getTag($tag_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'tag_id=" . (int)$tag_id . "' LIMIT 1) AS keyword FROM " . DB_PREFIX . "tag WHERE tag_id = '" . (int)$tag_id . "'");

		return $query->row;
	}

	public function getTags($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "tag t LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			if (!empty($data['filter_tag'])) {
				$sql .= " AND LCASE(td.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
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
			$tag_data = $this->cache->get('tag.' . (int)$this->config->get('config_language_id'));

			if (!$tag_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tag t LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY td.title");

				$tag_data = $query->rows;

				$this->cache->set('tag.' . (int)$this->config->get('config_language_id'), $tag_data);
			}

			return $tag_data;
		}
	}

	public function getTagDescriptions($tag_id) {
		$tag_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tag_description WHERE tag_id = '" . (int)$tag_id . "'");

		foreach ($query->rows as $result) {
			$tag_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_h1'          => $result['meta_h1'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $tag_description_data;
	}


	public function getTagLayouts($tag_id) {
		$tag_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tag_to_layout WHERE tag_id = '" . (int)$tag_id . "'");

		foreach ($query->rows as $result) {
			$tag_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $tag_layout_data;
	}

	public function getTotalTags($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tag t ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalTagsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tag_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}