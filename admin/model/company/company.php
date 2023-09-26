<?php
class ModelCompanyCompany extends Model {
	public function addCompany($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "company SET 
			status = '" . (int)$data['status'] . "', 
			sort_order = '" . (int)$data['sort_order'] . "', 
			image = '" . $this->db->escape($data['image']) . "', 
			category_id = '" . (int)$data['category_id'] . "', 
			tag_id = '" . (!empty($data['tag_id']) ? (int)$data['tag_id'] : '') . "', 
			phone_1 = '" . $this->db->escape($data['phone_1']) . "', 
			phone_2 = '" . $this->db->escape($data['phone_2']) . "', 
			site = '" . $this->db->escape($data['site']) . "', 
			email = '" . $this->db->escape($data['email']) . "' 
			");

		$company_id = $this->db->getLastId();



		foreach ($data['company_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "company_description SET 
				company_id = '" . (int)$company_id . "', 
				language_id = '" . (int)$language_id . "', 
				title = '" . $this->db->escape($value['title']) . "', 
				alternate = '" . $this->db->escape($value['alternate']) . "', 
				preview = '" . $this->db->escape($value['preview']) . "', 
				description = '" . $this->db->escape($value['description']) . "', 
				meta_title = '" . $this->db->escape($value['meta_title']) . "', 
				meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', 
				meta_description = '" . $this->db->escape($value['meta_description']) . "', 
				meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// social
		if (isset($data['social'])) {
			$index = 0;
			foreach ($data['social'] as $item) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_social SET 
					company_id = '" . (int)$company_id . "', 
					link = '" . $this->db->escape($item['link']) . "', 
					type = '" . $this->db->escape($item['type']) . "', 
					sort_order = '" . (int)$index . "'");
				$index++;
			}
		}

		// tags
		if (isset($data['tag'])) {
			foreach ($data['tag'] as $tag_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_tag SET company_id = '" . (int)$company_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}
		if (isset($data['tag_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['tag_new'] as $tag_name) {
				$tag_id = $this->model_tag_tag->addTagByName($tag_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_tag SET company_id = '" . (int)$company_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}

		// brands
		if (isset($data['brand'])) {
			foreach ($data['brand'] as $brand_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_brand SET company_id = '" . (int)$company_id . "', brand_id = '" . (int)$brand_id . "'");
			}
		}
		if (isset($data['brand_new'])) {
			$this->load->model('company/brand');
			foreach ($data['brand_new'] as $brand_name) {
				$brand_id = $this->model_company_brand->addBrandByName($brand_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_brand SET company_id = '" . (int)$company_id . "', brand_id = '" . (int)$brand_id . "'");
			}
		}

		// branches
		if (isset($data['branch'])) {
			foreach ($data['branch'] as $branch_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_branch SET company_id = '" . (int)$company_id . "', branch_id = '" . (int)$branch_id . "'");
			}
		}
		if (isset($data['branch_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['branch_new'] as $branch_name) {
				$branch_id = $this->model_tag_tag->addTagByName($branch_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_branch SET company_id = '" . (int)$company_id . "', branch_id = '" . (int)$branch_id . "'");
			}
		}

		// event
		if(!empty($data['event'])) {
			foreach($data['event'] as $event_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_company SET company_id = '" . (int)$company_id . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
			}
		}

		// gallery
		if (!empty($data['gallery'])) {
			foreach ($data['gallery'] as $key => $image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_gallery SET company_id = '" . (int)$company_id . "', image = '" . $this->db->escape($image) . "', sort_order = '" . (int)$key . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'company_id=" . (int)$company_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('company');

		return $company_id;
	}

	public function editCompany($company_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "company SET 
			status = '" . (int)$data['status'] . "', 
			sort_order = '" . (int)$data['sort_order'] . "', 
			image = '" . $this->db->escape($data['image']) . "', 
			category_id = '" . (int)$data['category_id'] . "', 
			tag_id = '" . (!empty($data['tag_id']) ? (int)$data['tag_id'] : '') . "', 
			phone_1 = '" . $this->db->escape($data['phone_1']) . "', 
			phone_2 = '" . $this->db->escape($data['phone_2']) . "', 
			site = '" . $this->db->escape($data['site']) . "', 
			email = '" . $this->db->escape($data['email']) . "', 
			tg = '" . $this->db->escape($data['tg']) . "', 
			insta = '" . $this->db->escape($data['insta']) . "', 
			fb = '" . $this->db->escape($data['fb']) . "' 
			WHERE company_id = '" . (int)$company_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "company_description WHERE company_id = '" . (int)$company_id . "'");

		foreach ($data['company_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "company_description SET 
				company_id = '" . (int)$company_id . "', 
				language_id = '" . (int)$language_id . "', 
				title = '" . $this->db->escape($value['title']) . "', 
				alternate = '" . $this->db->escape($value['alternate']) . "', 
				preview = '" . $this->db->escape($value['preview']) . "', 
				description = '" . $this->db->escape($value['description']) . "', 
				meta_title = '" . $this->db->escape($value['meta_title']) . "', 
				meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', 
				meta_description = '" . $this->db->escape($value['meta_description']) . "', 
				meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// social
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_social WHERE company_id = '" . (int)$company_id . "'");
		if (isset($data['social'])) {
			$index = 0;
			foreach ($data['social'] as $item) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_social SET company_id = '" . (int)$company_id . "', link = '" . $this->db->escape($item['link']) . "', type = '" . $this->db->escape($item['type']) . "', sort_order = '" . (int)$index . "'");
				$index++;
			}
		}

		// tags
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_to_tag WHERE company_id = '" . (int)$company_id . "'");
		if (isset($data['tag'])) {
			foreach ($data['tag'] as $tag_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_tag SET company_id = '" . (int)$company_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}
		if (isset($data['tag_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['tag_new'] as $tag_name) {
				$tag_id = $this->model_tag_tag->addTagByName($tag_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_tag SET company_id = '" . (int)$company_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}

		// brands
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_to_brand WHERE company_id = '" . (int)$company_id . "'");
		if (isset($data['brand'])) {
			foreach ($data['brand'] as $brand_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_brand SET company_id = '" . (int)$company_id . "', brand_id = '" . (int)$brand_id . "'");
			}
		}
		if (isset($data['brand_new'])) {
			$this->load->model('company/brand');
			foreach ($data['brand_new'] as $brand_name) {
				$brand_id = $this->model_company_brand->addBrandByName($brand_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_brand SET company_id = '" . (int)$company_id . "', brand_id = '" . (int)$brand_id . "'");
			}
		}

		// branches
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_to_branch WHERE company_id = '" . (int)$company_id . "'");
		if (isset($data['branch'])) {
			foreach ($data['branch'] as $branch_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_branch SET company_id = '" . (int)$company_id . "', branch_id = '" . (int)$branch_id . "'");
			}
		}
		if (isset($data['branch_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['branch_new'] as $branch_name) {
				$branch_id = $this->model_tag_tag->addTagByName($branch_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_to_branch SET company_id = '" . (int)$company_id . "', branch_id = '" . (int)$branch_id . "'");
			}
		}

		// event
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_company WHERE company_id = '" . (int)$company_id . "'");
		if(!empty($data['event'])) {
			foreach($data['event'] as $event_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_company SET company_id = '" . (int)$company_id . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
			}
		}

		// gallery
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_gallery WHERE company_id = '" . (int)$company_id . "'");
		if (!empty($data['gallery'])) {
			foreach ($data['gallery'] as $key => $image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "company_gallery SET company_id = '" . (int)$company_id . "', image = '" . $this->db->escape($image) . "', sort_order = '" . (int)$key . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'company_id=" . (int)$company_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'company_id=" . (int)$company_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('company');
	}

	public function deleteCompany($company_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "company WHERE company_id = '" . (int)$company_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_description WHERE company_id = '" . (int)$company_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_company WHERE company_id = '" . (int)$company_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_gallery WHERE company_id = '" . (int)$company_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_to_tag WHERE company_id = '" . (int)$company_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_social WHERE company_id = '" . (int)$company_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_to_brand WHERE company_id = '" . (int)$company_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "company_to_branch WHERE company_id = '" . (int)$company_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "journal_company WHERE company_id = '" . (int)$company_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'company_id=" . (int)$company_id . "'");

		$this->cache->delete('company');
	}

	public function getGallery($company_id) {
		$data = array();

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company_gallery WHERE company_id = '" . (int)$company_id . "' ORDER BY sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$data[] = $row['image'];
			}
		}

		return $data;
	}

	public function getCompanyByName($company_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company c LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) WHERE LCASE(cd.title) = '" . $this->db->escape(utf8_strtolower($company_name)) . "'");

		return $query->row;
	}
	public function getCompany($company_id) {
		$query = $this->db->query("SELECT DISTINCT *, 
			(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'company_id=" . (int)$company_id . "' LIMIT 1) AS keyword 
			FROM " . DB_PREFIX . "company c LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) WHERE c.company_id = '" . (int)$company_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	public function getCompanySocial($company_id) {
		$query = $this->db->query("SELECT *	FROM " . DB_PREFIX . "company_social WHERE company_id = '" . (int)$company_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getCompanies($data = array()) {
		if ($data) {
			$sql = "SELECT * 
			FROM " . DB_PREFIX . "company c 
			LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) 
			WHERE 
			cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			if (!empty($data['filter_name'])) {
				$sql .= " AND (
				LCASE(cd.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%' 
				OR LCASE(cd.alternate) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%' 			)";
			}
			if (isset($data['filter_status'])) {
				$sql .= " AND c.status = '" . (int)$data['filter_status'] . "'";
			}

			$sort_data = array(
				'cd.title'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY cd.title";
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
			$company_data = $this->cache->get('company.' . (int)$this->config->get('config_language_id'));

			if (!$company_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "company c LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cd.title");

				$company_data = $query->rows;

				$this->cache->set('company.' . (int)$this->config->get('config_language_id'), $company_data);
			}

			return $company_data;
		}
	}

	public function getCompanyDescriptions($company_id) {
		$company_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "company_description WHERE company_id = '" . (int)$company_id . "'");

		foreach ($query->rows as $result) {
			$company_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'alternate'            => $result['alternate'],
				'preview'      => $result['preview'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_h1'          => $result['meta_h1'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $company_description_data;
	}

	public function getCompanyBrands($company_id) {
		$company_brand_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "company_to_brand c2b LEFT JOIN " . DB_PREFIX . "company_brand cb ON (cb.brand_id = c2b.brand_id) WHERE c2b.company_id = '" . (int)$company_id . "' ORDER BY cb.title ASC");

		foreach ($query->rows as $row) {
			$company_brand_data[] = array(
				'brand_id'	=> $row['brand_id'],
				'brand'			=> $row['title']
			);
		}

		return $company_brand_data;
	}

	public function getCompanyBranches($company_id) {
		$company_branch_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "company_to_branch c2b LEFT JOIN " . DB_PREFIX . "tag_description td ON (c2b.branch_id = td.tag_id) WHERE c2b.company_id = '" . (int)$company_id . "' ORDER BY td.title ASC");

		foreach ($query->rows as $row) {
			$company_branch_data[] = array(
				'branch_id'	=> $row['branch_id'],
				'branch'		=> $row['title']
			);
		}

		return $company_branch_data;
	}

	public function getCompanyTags($company_id = 0) {
		$company_tag_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "company_to_tag c2t LEFT JOIN " . DB_PREFIX . "tag_description td ON (c2t.tag_id = td.tag_id) WHERE company_id = '" . (int)$company_id . "' ORDER BY td.title ASC");

		foreach ($query->rows as $row) {
			$company_tag_data[] = array(
				'tag_id'	=> $row['tag_id'],
				'tag'			=> $row['title']
			);
		}

		return $company_tag_data;
	}

	public function getTotalCompanies($data=array()) {

		$sql = "SELECT COUNT(*) AS total 
		FROM " . DB_PREFIX . "company c 
		LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) 
		WHERE 
		cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND (
			LCASE(cd.title) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%' 
			OR LCASE(cd.alternate) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%' 			)";
		}
		if (isset($data['filter_status'])) {
			$sql .= " AND c.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getEventsByCompany($company_id) {
		$event_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_company ec LEFT JOIN " . DB_PREFIX . "avevent e ON (e.event_id = ec.event_id) WHERE ec.company_id = '" . (int)$company_id . "' ORDER BY e.date_available ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$event_data[$row['event_id']] = $row['event_id'];
			}
		}

		return $event_data;
	}

}