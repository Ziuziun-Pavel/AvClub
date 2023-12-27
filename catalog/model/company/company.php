<?php
class ModelCompanyCompany extends Model {
    private $url_deal_create = "http://clients.techin.by/avclub/site/api/v1/deal/create";
    private $url_company_create = "http://clients.techin.by/avclub/site/api/v1/company/create";

	public function getCompany($company_id = 0, $show_disabled = false) {
		
		$sql = "SELECT DISTINCT *, cd.title AS title, c.image 
		FROM " . DB_PREFIX . "company c 
		LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id)
		WHERE c.company_id = '" . (int)$company_id . "' 
		AND	cd.language_id = '" . (int)$this->config->get('config_language_id') . "'  ";

		if(!$show_disabled) {
			$sql .= " AND c.status = '1' ";
		}

		$query = $this->db->query($sql);

		$branches = $this->getBranchesByCompany($company_id);

		if ($query->num_rows) {
			return array(
				'company_id'  			=> $query->row['company_id'],
				'title'        	    => $query->row['title'],
				'preview'  	   		 	=> $query->row['preview'],
				'description'  	    => $query->row['description'],
				'meta_title'     	  => $query->row['meta_title'],
				'meta_h1'         	=> $query->row['meta_h1'],
				'meta_description' 	=> $query->row['meta_description'],
				'meta_keyword'     	=> $query->row['meta_keyword'],
				'image'            	=> $query->row['image'],
				'site'            	=> $query->row['site'],
				'email'            	=> $query->row['email'],
				'activity'          => $query->row['activity'],
				'phone_1'           => $query->row['phone_1'],
				'phone_2'           => $query->row['phone_2'],
				'tg'            		=> $query->row['tg'],
				'insta'            	=> $query->row['insta'],
				'fb'            		=> $query->row['fb'],
				'tag_id'           	=> $query->row['tag_id'],
				'b24id'           	=> (int)$query->row['b24id'],
				'branches'          => $branches,
				'status'           	=> $query->row['status']
			);
		} else {
			return false;
		}
	}

	public function getCompanySocial($company_id) {
		$query = $this->db->query("SELECT *	FROM " . DB_PREFIX . "company_social WHERE company_id = '" . (int)$company_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getTabsCompany($company_id = 0, $tag_id = 0) {
		$data = array();
		$implode = array();

		// if(!$tag_id) {return false;}


		// journal
		$sql = "SELECT j.type FROM " . DB_PREFIX . "journal j 
		LEFT JOIN " . DB_PREFIX . "journal_tag jt ON (j.journal_id = jt.journal_id) 
		WHERE 
		j.status = '1' 
		AND j.date_available <= NOW() 
		AND jt.tag_id = '" . (int)$tag_id . "' 
		GROUP BY j.type";

		$query = $this->db->query($sql);

		if($query->rows) {
			foreach($query->rows as $row) {
				$data['journal'][] = $row['type'];
			}
		}

		// events
		$events = array();
		$sql = "SELECT DISTINCT e.event_id 
		FROM " . DB_PREFIX . "avevent_company ec 
		LEFT JOIN " . DB_PREFIX . "avevent e ON (e.event_id = ec.event_id) 
		WHERE 
		ec.company_id = '" . (int)$company_id . "' 
		AND e.status = '1' ";

		$sql_new = $sql . " AND (e.show_event = '1' AND (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) )";
		$sql_old = $sql . " AND (e.old_type = 'page' AND e.date_available < NOW())";

		$query_new = $this->db->query($sql_new);
		$query_old = $this->db->query($sql_old);

		if($query_new->num_rows) { 	$events['event_new'] = 'event_new'; }
		if($query_old->num_rows) { $events['event_old'] = 'event_old'; }
		if($events) { $data['event'] = $events; }

		$master = array();
		// master
		$sql = "SELECT m.type FROM " . DB_PREFIX . "master m LEFT JOIN " . DB_PREFIX . "master_company mc ON (m.master_id = mc.master_id) WHERE mc.company_id = '" . (int)$company_id . "' AND m.status = '1' AND m.date_available > NOW() GROUP BY m.type";

		$query = $this->db->query($sql);
		if($query->num_rows) {
			$master['mastertobe'] = 'mastertobe';
			foreach($query->rows as $row) {
				$master['master' . $row['type']] = 'master' . $row['type'];
			}
		}

		// master old
		$sql = "SELECT DISTINCT j.journal_id FROM " . DB_PREFIX . "journal j 
		LEFT JOIN " . DB_PREFIX . "journal_tag jt ON (j.journal_id = jt.journal_id) 
		WHERE 
		j.status = '1' 
		AND j.date_available <= NOW() 
		AND jt.tag_id = '" . (int)$tag_id . "' 
		AND j.master_old = '1' ";

		$query = $this->db->query($sql);
		if($query->num_rows) {
			$master['masterold'] = 'masterold';
		}

		if($master) {
			$data['master'] = $master;
		}

		// expert
		$sql = "SELECT DISTINCT v.visitor_id 
		FROM " . DB_PREFIX . "visitor v 
		WHERE 
		v.company_id = '" . (int)$company_id . "' 
		AND v.status = '1' 
		AND v.expert = '1'";

		$query = $this->db->query($sql);
		if($query->num_rows) {
			$data['expert'] = true;
		}


		return $data;
	}

	public function getGalleryByCompany($company_id = 0) {
		$data = array();

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company_gallery WHERE company_id = '" . (int)$company_id . "' ORDER BY sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$data[] = $row['image'];
			}
		}

		return $data;
	}

	public function getTagsByCompany($company_id = 0) {
		$company_tag_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "company_to_tag c2b LEFT JOIN " . DB_PREFIX . "tag_description td ON (td.tag_id = c2b.tag_id) WHERE c2b.company_id = '" . (int)$company_id . "' ORDER BY td.title ASC");

		foreach ($query->rows as $row) {
			$company_tag_data[] = array(
				'tag_id'	=> $row['tag_id'],
				'title'			=> $this->mb_ucfirst($row['title'])
			);
		}

		return $company_tag_data;
	}

	public function getBrandsByCompany($company_id = 0) {
		$company_brand_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "company_to_brand c2b LEFT JOIN " . DB_PREFIX . "company_brand cb ON (cb.brand_id = c2b.brand_id) WHERE c2b.company_id = '" . (int)$company_id . "' ORDER BY cb.title ASC");

		foreach ($query->rows as $row) {
			$company_brand_data[] = array(
				'brand_id'	=> $row['brand_id'],
				'title'			=> $row['title']
			);
		}

		return $company_brand_data;
	}
	public function getBranchesByCompany($company_id = 0) {
		$company_branch_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "company_to_branch c2b LEFT JOIN " . DB_PREFIX . "tag_description td ON (c2b.branch_id = td.tag_id) WHERE c2b.company_id = '" . (int)$company_id . "' AND td.title <> '' ORDER BY td.title ASC");

		foreach ($query->rows as $row) {
			$company_branch_data[] = array(
				'branch_id'	=> $row['branch_id'],
				'title'		=> $this->mb_ucfirst($row['title'])
			);
		}

		return $company_branch_data;
	}

	public function getAllBranches() {
		$company_branch_data = array();

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company_to_branch c2b LEFT JOIN " . DB_PREFIX . "tag_description td ON (c2b.branch_id = td.tag_id) WHERE td.title <> '' GROUP BY td.tag_id ORDER BY td.title ASC");

		foreach ($query->rows as $row) {
			$company_branch_data[] = array(
				'branch_id'	=> $row['branch_id'],
				'title'		=> $this->mb_ucfirst($row['title'])
			);
		}

		return $company_branch_data;
	}



	public function getCompanies($data = array()) {

		$show_disabled = !empty($data['filter_disabled']) ? true : false;

		$sql = "SELECT c.company_id FROM " . DB_PREFIX . "company c ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) ";

		if(!empty($data['filter_tag'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "company_to_tag c2t ON (c.company_id = c2t.company_id) LEFT JOIN " . DB_PREFIX . "tag_description td ON (td.tag_id = c2t.tag_id) ";
		}

		if(!empty($data['filter_brand'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "company_to_brand c2b ON (c.company_id = c2b.company_id) LEFT JOIN " . DB_PREFIX . "company_brand cbd ON (cbd.brand_id = c2b.brand_id) ";
		}

		if(!empty($data['filter_branches'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "company_to_branch c2bc ON (c.company_id = c2bc.company_id) ";
		}

		$sql .= " WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

		if(!$show_disabled) {
			$sql .= " AND c.status = '1' ";
		}

		if(!empty($data['filter_tag'])) {
			// $sql .= " AND c2t.tag_id = '" . (int)$data['filter_tag'] . "' ";
			// $sql .= " AND td.title LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			$sql .= " AND td.title LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%' ";
		}

		if(!empty($data['filter_brand'])) {
			// $sql .= " AND c2b.brand_id = '" . (int)$data['filter_brand'] . "' ";
			$sql .= " AND cbd.title LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_brand'])) . "%'";
		}

		if(!empty($data['filter_company'])) {

			$search = array('&quot;');
			$replace = array('"');
			$data['filter_company'] = str_replace($search, $replace, $data['filter_company']);

			$sql .= " AND ( cd.title LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_company'])) . "%'";

			$sql .= " OR (";
			$implode = array();
			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_company'])));
			foreach ($words as $word) {
				$implode[] = "(cd.title LIKE '%" . $this->db->escape($word) . "%' OR cd.alternate LIKE '%" . $this->db->escape($word) . "%')";
			}
			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}
			$sql .= ")";

			$sql .= ")";
		}

		if(!empty($data['filter_branches'])) {
			$sql .= " AND c2bc.branch_id IN (" . implode(",", $data['filter_branches']) . ") ";
		}

		if (!empty($data['filter_category'])) {
			$sql .= " AND c.category_id = '" . (int)$data['filter_category'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND (";

			$implode = array();

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

			foreach ($words as $word) {
				$implode[] = "(cd.title LIKE '%" . $this->db->escape($word) . "%' OR cd.alternate LIKE '%" . $this->db->escape($word) . "%')";
			}

			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}

			$sql .= ")";
		}

		$sql .= " GROUP BY c.company_id";

		$sql .= " ORDER BY c.sort_order DESC, LCASE(cd.title) ASC";


		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$company_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$company_data[$result['company_id']] = $this->getCompany($result['company_id'], $show_disabled);
		}

		return $company_data;
	}

	public function getTotalCompanies($data = array()) {
		$sql = "SELECT COUNT(DISTINCT c.company_id) AS total FROM " . DB_PREFIX . "company c ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id) ";

		if(!empty($data['filter_tag'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "company_to_tag c2t ON (c.company_id = c2t.company_id) LEFT JOIN " . DB_PREFIX . "tag_description td ON (td.tag_id = c2t.tag_id) ";
		}

		if(!empty($data['filter_brand'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "company_to_brand c2b ON (c.company_id = c2b.company_id) LEFT JOIN " . DB_PREFIX . "company_brand cbd ON (cbd.brand_id = c2b.brand_id) ";
		}

		if(!empty($data['filter_branches'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "company_to_branch c2bc ON (c.company_id = c2bc.company_id) ";
		}

		$sql .= " WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status = '1' ";

		if(!empty($data['filter_tag'])) {
			// $sql .= " AND c2t.tag_id = '" . (int)$data['filter_tag'] . "' ";
			$sql .= " AND td.title LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
		}

		if(!empty($data['filter_brand'])) {
			// $sql .= " AND c2b.brand_id = '" . (int)$data['filter_brand'] . "' ";
			$sql .= " AND cbd.title LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_brand'])) . "%'";
		}

		if(!empty($data['filter_company'])) {
			// $sql .= " AND c.company_id = '" . (int)$data['filter_company'] . "' ";
			$sql .= " AND cd.title LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_company'])) . "%'";
		}

		if(!empty($data['filter_branches'])) {
			$sql .= " AND c2bc.branch_id IN (" . implode(",", $data['filter_branches']) . ") ";
		}

		if (!empty($data['filter_category'])) {
			$sql .= " AND c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND (";

			$implode = array();

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

			foreach ($words as $word) {
				$implode[] = "(cd.title LIKE '%" . $this->db->escape($word) . "%' OR cd.alternate LIKE '%" . $this->db->escape($word) . "%')";
			}

			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}

			$sql .= ")";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}


	public function getExperts($data = array()) {
		$this->load->model('visitor/visitor');
		
		$expert_list = array();

		$sql = "SELECT v.visitor_id FROM " . DB_PREFIX . "visitor v ";

		$sql .= " WHERE v.status = '1' AND v.expert = '1' ";

		if(!empty($data['filter_company'])) {
			$sql .= " AND v.company_id = '" . (int)$data['filter_company'] . "' ";
		}

		$sql .= " GROUP BY v.visitor_id";

		$sql .= " ORDER BY v.lastname ASC, v.firstname ASC";


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

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$expert_list[] = $this->model_visitor_visitor->getVisitor($row['visitor_id']);
			}
		}

		return $expert_list;
	}

	public function getTotalExperts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT v.visitor_id) AS total FROM " . DB_PREFIX . "visitor v ";

		$sql .= " WHERE v.status = '1' AND v.expert = '1' ";

		if(!empty($data['filter_company'])) {
			$sql .= " AND v.company_id = '" . (int)$data['filter_company'] . "' ";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTags($data = array()) {
		$sql = "SELECT t.tag_id, td.title FROM " . DB_PREFIX . "tag t ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "company_to_tag c2t ON (c2t.tag_id = td.tag_id) ";

		if(!empty($data['filter_category'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "company c ON (c2t.company_id = c.company_id) ";
		}

		$sql .= " WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1' ";

		$sql .= " AND c2t.tag_id <> '' ";

		if(!empty($data['filter_category'])) {
			$sql .= " AND c.category_id = '" . (int)$data['filter_category'] . "' ";
		}

		if (!empty($data['filter_title'])) {
			$sql .= " AND (";

			$implode = array();

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_title'])));

			foreach ($words as $word) {
				$implode[] = "td.title LIKE '%" . $this->db->escape($word) . "%'";
			}

			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}

			$sql .= ")";
		}

		$sql .= " GROUP BY t.tag_id";

		$sql .= " ORDER BY LCASE(td.title) ASC";


		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$tag_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $row) {
			$tag_data[] = array(
				'tag_id'	=> $row['tag_id'],
				'title'			=> $this->mb_ucfirst($row['title'])
			);
		}

		return $tag_data;
	}

	public function getBrands($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "company_brand cb ";

		$sql .= " WHERE 1 ";

		if (!empty($data['filter_title'])) {
			$sql .= " AND (";

			$implode = array();

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_title'])));

			foreach ($words as $word) {
				$implode[] = "cb.title LIKE '%" . $this->db->escape($word) . "%'";
			}

			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}

			$sql .= ")";
		}


		$sql .= " ORDER BY LCASE(cb.title) ASC";


		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$tag_data = array();

		$query = $this->db->query($sql);


		return $query->rows;
	}



	public function getCompanyCategories() {
		$category_data = $this->cache->get('company.category.companies.' . (int)$this->config->get('config_language_id'));

		if (!$category_data) {
			$query = $this->db->query("SELECT DISTINCT cc.category_id, ccd.title FROM " . DB_PREFIX . "company_category cc 
				LEFT JOIN " . DB_PREFIX . "company_category_description ccd ON (cc.category_id = ccd.category_id) 
				LEFT JOIN " . DB_PREFIX . "company c ON (c.category_id = cc.category_id) 
				WHERE 
				ccd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND c.status = '1' 
				GROUP BY cc.category_id 
				ORDER BY ccd.title");

			$category_data = $query->rows;

			$this->cache->set('company.category.companies.' . (int)$this->config->get('config_language_id'), $category_data);
		}

		return $category_data;
	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "company_category WHERE category_id = '" . (int)$category_id . "'");

		return $query->row;
	}

	public function getCompaniesByTags($tag_ids = array()) {
		$companies = array();

		$sql = "SELECT c.company_id FROM " . DB_PREFIX . "company c 
		LEFT JOIN " . DB_PREFIX . "company_description cd ON (c.company_id = cd.company_id)
		WHERE 
		c.status = 1 
		AND c.tag_id IN (" . implode(",", $tag_ids) . ") ";

		$sql .= " GROUP BY c.company_id";
		$sql .= " ORDER BY c.sort_order DESC, LCASE(cd.title) ASC";

		$query = $this->db->query($sql);

		foreach($query->rows as $row) {
			$companies[] = $row['company_id'];
		}

		return $companies;
	}

    public function addCompanyProfile($data_company = array(), $data_deal= array())
    {
        $company_fields = $data_company;
        $deal_fields = $data_deal;

        $ch_company = curl_init($this->url_company_create);
        curl_setopt($ch_company, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_company, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch_company, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch_company, CURLOPT_POSTFIELDS, json_encode($company_fields));
        curl_setopt($ch_company, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $company_body = curl_exec($ch_company);
        curl_close($ch_company);

        $company_json = json_decode($company_body, true);

        if ($company_json && $company_json['id']) {
            $deal_fields['company_id'] = (int)$company_json['id'];
        }

        $ch_deal = curl_init($this->url_deal_create);
        curl_setopt($ch_deal, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_deal, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch_deal, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch_deal, CURLOPT_POSTFIELDS, json_encode($deal_fields));
        curl_setopt($ch_deal, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $deal_body = curl_exec($ch_deal);
        curl_close($ch_deal);

        $deal_json = json_decode($deal_body, true);
        
        return $deal_json;

    }
	
	private function mb_ucfirst($string, $encoding = 'UTF-8'){
		$strlen = mb_strlen($string, $encoding);
		$firstChar = mb_substr($string, 0, 1, $encoding);
		$then = mb_substr($string, 1, $strlen - 1, $encoding);
		return mb_strtoupper($firstChar, $encoding) . $then;
	}

}