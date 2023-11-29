<?php
class ModelMasterMaster extends Model {
	public function addMaster($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "master SET 
			date_available = '" . $this->db->escape($data['date_available']) . "', 
			type = '" . $this->db->escape($data['type']) . "', 
			image = '" . $this->db->escape($data['image']) . "', 
			logo = '" . $this->db->escape($data['logo']) . "', 
			link = '" . $this->db->escape($data['link']) . "', 
			status = '" . (int)$data['status'] . "', 
			author_id = '" . (int)$data['author_id'] . "', 
			author_exp = '" . (!empty($data['author_exp']) ? (int)$data['author_exp'] : 0) . "', 
			company_id = '" . (!empty($data['company_id']) ? (int)$data['company_id'] : 0) . "'");

		$master_id = $this->db->getLastId();

		foreach ($data['master_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "master_description SET 
				master_id = '" . (int)$master_id . "', 
				language_id = '" . (int)$language_id . "', 
				title = '" . $this->db->escape($value['title']) . "', 
				description = '" . $this->db->escape($value['description']) . "', 
				preview = '" . $this->db->escape($value['preview']) . "', 
				meta_title = '" . $this->db->escape($value['meta_title']) . "', 
				meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', 
				meta_description = '" . $this->db->escape($value['meta_description']) . "', 
				meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if(isset($data['company'])) {
			foreach($data['company'] as $key=>$company) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "master_company SET company_id = '" . (int)$company . "',  master_id = '" . (int)$master_id . "'");
			}
		}

		if(!empty($data['experts'])) {
			foreach($data['experts'] as $key=>$expert) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "master_expert SET 
					author_id = '" . (int)$expert['author_id'] . "',  
					author_exp = '" . (int)$expert['exp_id'] . "', 
					master_id = '" . (int)$master_id . "'");
			}
		}

		if (isset($data['master_layout'])) {
			foreach ($data['master_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "master_to_layout SET master_id = '" . (int)$master_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

        if (isset($data['tag'])) {
            foreach ($data['tag'] as $tag_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "master_tag SET master_id = '" . (int)$master_id . "', tag_id = '" . (int)$tag_id . "'");
            }
        }

        if (isset($data['keyword'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'master_id=" . (int)$master_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

		$this->cache->delete('master');

		return $master_id;
	}

	public function editMaster($master_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "master SET date_available = '" . $this->db->escape($data['date_available']) . "', 
		type = '" . $this->db->escape($data['type']) . "', 
		image = '" . $this->db->escape($data['image']) . "', 
		logo = '" . $this->db->escape($data['logo']) . "', 
		link = '" . $this->db->escape($data['link']) . "', 
		status = '" . (int)$data['status'] . "', 
		author_id = '" . (int)$data['author_id'] . "', 
		author_exp = '" . (!empty($data['author_exp']) ? (int)$data['author_exp'] : 0) . "', 
		company_id = '" . (!empty($data['company_id']) ? (int)$data['company_id'] : 0) . "' WHERE master_id = '" . (int)$master_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "master_description WHERE master_id = '" . (int)$master_id . "'");

		foreach ($data['master_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "master_description SET master_id = '" . (int)$master_id . "', 
			language_id = '" . (int)$language_id . "', 
			title = '" . $this->db->escape($value['title']) . "', 
			description = '" . $this->db->escape($value['description']) . "', 
			preview = '" . $this->db->escape($value['preview']) . "', 
			meta_title = '" . $this->db->escape($value['meta_title']) . "', 
			meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', 
			meta_description = '" . $this->db->escape($value['meta_description']) . "', 
			meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "master_company WHERE master_id = '" . (int)$master_id . "'");
		if(isset($data['company'])) {
			foreach($data['company'] as $key=>$company) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "master_company SET company_id = '" . (int)$company . "',  master_id = '" . (int)$master_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "master_expert WHERE master_id = '" . (int)$master_id . "'");
		if(!empty($data['experts'])) {
			foreach($data['experts'] as $key=>$expert) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "master_expert SET 
					author_id = '" . (int)$expert['author_id'] . "',  
					author_exp = '" . (int)$expert['exp_id'] . "', 
					master_id = '" . (int)$master_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "master_to_layout WHERE master_id = '" . (int)$master_id . "'");
		if (isset($data['master_layout'])) {
			foreach ($data['master_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "master_to_layout SET master_id = '" . (int)$master_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

        $this->db->query("DELETE FROM " . DB_PREFIX . "master_tag WHERE master_id = '" . (int)$master_id . "'");
        if (isset($data['tag'])) {
            foreach ($data['tag'] as $tag_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "master_tag SET master_id = '" . (int)$master_id . "', tag_id = '" . (int)$tag_id . "'");
            }
        }

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'master_id=" . (int)$master_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

		$this->cache->delete('master');
	}

	public function deleteMaster($master_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "master WHERE master_id = '" . (int)$master_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "master_expert WHERE master_id = '" . (int)$master_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "master_description WHERE master_id = '" . (int)$master_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "master_to_layout WHERE master_id = '" . (int)$master_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '$master_id=" . (int)$master_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "master_tag WHERE master_id = '" . (int)$master_id . "'");

		$this->cache->delete('master');
	}

	public function getMaster($master_id) {
		$query = $this->db->query("SELECT DISTINCT *,
                (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'master_id=" . (int)$master_id . "' LIMIT 1) AS keyword
                FROM " . DB_PREFIX . "master WHERE master_id = '" . (int)$master_id . "'");

		return $query->row;
	}

	public function getMasters($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "master m LEFT JOIN " . DB_PREFIX . "master_description md ON (m.master_id = md.master_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			if(!empty($data['filter_actual']) && $data['filter_actual']) {
				$sql .= " AND m.date_available > NOW() ";
			}

			$sort_data = array(
				'md.title',
				'm.date_available'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY md.title";
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
			$master_data = $this->cache->get('master.' . (int)$this->config->get('config_language_id'));

			if (!$master_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master m LEFT JOIN " . DB_PREFIX . "master_description md ON (m.master_id = md.master_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY md.title");

				$master_data = $query->rows;

				$this->cache->set('master.' . (int)$this->config->get('config_language_id'), $master_data);
			}

			return $master_data;
		}
	}

	public function getMasterDescriptions($master_id) {
		$master_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master_description WHERE master_id = '" . (int)$master_id . "'");

		foreach ($query->rows as $result) {
			$master_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'preview'      => $result['preview'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_h1'          => $result['meta_h1'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $master_description_data;
	}


	public function getCompaniesByMaster($master_id) {
		$company_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master_company c LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) WHERE c.master_id = '" . (int)$master_id . "' ORDER BY cd.title ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$company_data[] = array(
					'company_id'	=> $row['company_id'],
					'title'				=> $row['title']	
				);
			}
		}

		return $company_data;
	}


	public function getMasterExperts($master_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master_expert WHERE master_id = '" . (int)$master_id . "'");

		return $query->rows;
	}

    public function getMasterTags($master_id) {
        $master_tag_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master_tag mt LEFT JOIN " . DB_PREFIX . "tag_description td ON (mt.tag_id = td.tag_id) WHERE master_id = '" . (int)$master_id . "' ORDER BY td.title ASC");

        foreach ($query->rows as $row) {
            $master_tag_data[] = array(
                'tag_id'	=> $row['tag_id'],
                'tag'			=> $row['title']
            );
        }

        return $master_tag_data;
    }

	public function getMasterLayouts($master_id) {
		$master_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master_to_layout WHERE master_id = '" . (int)$master_id . "'");

		foreach ($query->rows as $result) {
			$master_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $master_layout_data;
	}

	public function getTotalMasters($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "master m ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalMastersByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "master_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}