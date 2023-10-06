<?php
class ModelVisitorVisitor extends Model {
	public function addVisitor($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "visitor SET 
			name = '" . $this->db->escape($data['firstname'] . ' ' . $data['lastname']) . "', 
			firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			exp = '" . $this->db->escape($data['exp']) . "', 
			expert = '" . (!empty($data['expert']) ? (int)$data['expert'] : 0) . "', 
			b24id = '" . (!empty($data['b24id']) ? (int)$data['b24id'] : 0) . "', 
			emails = '" . (!empty($data['emails']) ? $this->db->escape($data['emails']) : '') . "', 
			field_expertise = '" . (!empty($data['field_expertise']) ? $this->db->escape($data['field_expertise']) : '') . "', 
			field_useful = '" . (!empty($data['field_useful']) ? $this->db->escape($data['field_useful']) : '') . "', 
			field_regalia = '" . (!empty($data['field_regalia']) ? $this->db->escape($data['field_regalia']) : '') . "', 
			image = '" . $this->db->escape($data['image']) . "', 
			company_id = '" . (!empty($data['company_id']) ? (int)$data['company_id'] : 0) . "', 
			email = '" . $this->db->escape($data['email']) . "', 
			salt = '" . $this->db->escape($salt = token(9)) . "', 
			password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			status = '" . (int)$data['status'] . "'");

		$visitor_id = $this->db->getLastId();

		// event
		if(!empty($data['event'])) {
			foreach($data['event'] as $event_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_author SET author_id = '" . (int)$visitor_id . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
			}
		}

		// exp
		if(!empty($data['exp_list'])) {
			foreach($data['exp_list'] as $exp_id=>$exp) {
				$this->db->query("UPDATE " . DB_PREFIX . "visitor_exp SET 
					exp = '" . $this->db->escape($exp['exp']) . "', 
					main = '" . (!empty($exp['main']) ? (int)$exp['main'] : 0) . "' 
					WHERE 
					visitor_id = '" . (int)$visitor_id . "' 
					AND exp_id = '" . (int)$exp_id . "'");
			}
		}
		if(!empty($data['exp_add'])) {
			foreach($data['exp_add'] as $exp) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_exp SET 
					visitor_id = '" . (int)$visitor_id . "',  
					exp = '" . $this->db->escape($exp['exp']) . "', 
					main = '" . (!empty($exp['main']) ? (int)$exp['main'] : 0) . "'");
			}
		}

		// tags expert
		if (isset($data['tag_expert'])) {
			foreach ($data['tag_expert'] as $tag_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_expert SET visitor_id = '" . (int)$visitor_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}
		if (isset($data['tag_expert_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['tag_expert_new'] as $tag_name) {
				$tag_id = $this->model_tag_tag->addTagByName($tag_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_expert SET visitor_id = '" . (int)$visitor_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}

		// tags branch
		if (isset($data['tag_branch'])) {
			foreach ($data['tag_branch'] as $tag_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_branch SET visitor_id = '" . (int)$visitor_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}
		if (isset($data['tag_branch_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['tag_branch_new'] as $tag_name) {
				$tag_id = $this->model_tag_tag->addTagByName($tag_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_branch SET visitor_id = '" . (int)$visitor_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'expert_id=" . (int)$visitor_id . "'");
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'expert_id=" . (int)$visitor_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('seo_pro');
		return $visitor_id;
	}

	public function editVisitor($visitor_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "visitor SET 
			name = '" . $this->db->escape($data['firstname'] . ' ' . $data['lastname']) . "', 
			firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			exp = '" . $this->db->escape($data['exp']) . "', 
			expert = '" . (!empty($data['expert']) ? (int)$data['expert'] : 0) . "', 
			b24id = '" . (!empty($data['b24id']) ? (int)$data['b24id'] : 0) . "', 
			emails = '" . (!empty($data['emails']) ? $this->db->escape($data['emails']) : '') . "', 
			field_expertise = '" . (!empty($data['field_expertise']) ? $this->db->escape($data['field_expertise']) : '') . "', 
			field_useful = '" . (!empty($data['field_useful']) ? $this->db->escape($data['field_useful']) : '') . "', 
			field_regalia = '" . (!empty($data['field_regalia']) ? $this->db->escape($data['field_regalia']) : '') . "', 
			image = '" . $this->db->escape($data['image']) . "', 
			company_id = '" . (!empty($data['company_id']) ? (int)$data['company_id'] : 0) . "', 
			email = '" . $this->db->escape($data['email']) . "', 
			status = '" . (int)$data['status'] . "' 
			WHERE 
			visitor_id = '" . (int)$visitor_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE " . DB_PREFIX . "visitor SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE visitor_id = '" . (int)$visitor_id . "'");
		}

		// event
		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_author WHERE author_id = '" . (int)$visitor_id . "'");
		if(!empty($data['event'])) {
			foreach($data['event'] as $event_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "avevent_author SET author_id = '" . (int)$visitor_id . "',  event_id = '" . (int)$event_id . "',  sort_order = '0'");
			}
		}

		// exp
		if(!empty($data['exp_list'])) {
			foreach($data['exp_list'] as $exp_id=>$exp) {
				$this->db->query("UPDATE " . DB_PREFIX . "visitor_exp SET 
					exp = '" . $this->db->escape($exp['exp']) . "', 
					main = '" . (!empty($exp['main']) ? (int)$exp['main'] : 0) . "' 
					WHERE 
					visitor_id = '" . (int)$visitor_id . "' 
					AND exp_id = '" . (int)$exp_id . "'");
			}
		}
		if(!empty($data['exp_add'])) {
			foreach($data['exp_add'] as $exp) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_exp SET 
					visitor_id = '" . (int)$visitor_id . "',  
					exp = '" . $this->db->escape($exp['exp']) . "', 
					main = '" . (!empty($exp['main']) ? (int)$exp['main'] : 0) . "'");
			}
		}

		// tags expert
		$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_tag_expert WHERE visitor_id = '" . (int)$visitor_id . "'");
		if (isset($data['tag_expert'])) {
			foreach ($data['tag_expert'] as $tag_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_expert SET visitor_id = '" . (int)$visitor_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}
		if (isset($data['tag_expert_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['tag_expert_new'] as $tag_name) {
				$tag_id = $this->model_tag_tag->addTagByName($tag_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_expert SET visitor_id = '" . (int)$visitor_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}

		// tags branch
		$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_tag_branch WHERE visitor_id = '" . (int)$visitor_id . "'");
		if (isset($data['tag_branch'])) {
			foreach ($data['tag_branch'] as $tag_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_branch SET visitor_id = '" . (int)$visitor_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}
		if (isset($data['tag_branch_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['tag_branch_new'] as $tag_name) {
				$tag_id = $this->model_tag_tag->addTagByName($tag_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "visitor_tag_branch SET visitor_id = '" . (int)$visitor_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'expert_id=" . (int)$visitor_id . "'");
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'expert_id=" . (int)$visitor_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('seo_pro');

	}

	public function editToken($visitor_id, $token) {
		$this->db->query("UPDATE " . DB_PREFIX . "visitor SET token = '" . $this->db->escape($token) . "' WHERE visitor_id = '" . (int)$visitor_id . "'");
	}

	public function deleteVisitor($visitor_id) {

		$log = new Log('visitor_delete-' . date('Y-m') . '.log');
		$message = "\n------------------\n";
		$visitor_info = $this->getVisitor($visitor_id);
		if(!empty($visitor_info)) {
			foreach($visitor_info as $key=>$value) {
				$message .= $key . " -- " . json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
			}
		}else{
			$message .= json_encode($visitor_info, JSON_UNESCAPED_UNICODE);
		}
		$message .= "\n\n\n";
		$log->write($message);

		$this->db->query("DELETE FROM " . DB_PREFIX . "visitor WHERE visitor_id = '" . (int)$visitor_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_exp WHERE visitor_id = '" . (int)$visitor_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'expert_id=" . (int)$visitor_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "avevent_author WHERE author_id = '" . (int)$visitor_id . "'");
	}


	public function getVisitor($visitor_id) {
		$query = $this->db->query("SELECT DISTINCT *, 
			(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'expert_id=" . (int)$visitor_id . "' LIMIT 1) AS keyword 
			FROM " . DB_PREFIX . "visitor 
			WHERE 
			visitor_id = '" . (int)$visitor_id . "'");

		return $query->row;
	}

	public function getVisitorByName($visitor_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "visitor v WHERE LCASE(v.name) = '" . $this->db->escape(utf8_strtolower($visitor_name)) . "'");

		return $query->row;
	}

	public function getVisitorByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "visitor WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getVisitors($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "visitor v ";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "v.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "v.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "v.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_expert']) && !is_null($data['filter_expert'])) {
			$implode[] = "v.expert = '" . (int)$data['filter_expert'] . "'";
		}

        //Убираем из выборки удалённые контакты
        $implode[] = "v.b24id != 0";
        //Убираем из выборки заархивированные контакты
        $implode[] = "v.status != 0";

        if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'v.name',
			'v.email',
			'v.status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY v.name";
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

	public function getTotalVisitors($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "visitor";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_expert']) && !is_null($data['filter_expert'])) {
			$implode[] = "expert = '" . (int)$data['filter_expert'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}


	public function getEventsByVisitor($visitor_id) {
		$event_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "avevent_author ea LEFT JOIN " . DB_PREFIX . "avevent e ON (e.event_id = ea.event_id) WHERE ea.author_id = '" . (int)$visitor_id . "' ORDER BY e.date_available ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$event_data[$row['event_id']] = $row['event_id'];
			}
		}

		return $event_data;
	}

	public function getTagsExpert($visitor_id = 0) {
		$tags = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor_tag_expert v2t LEFT JOIN " . DB_PREFIX . "tag_description td ON (v2t.tag_id = td.tag_id) WHERE v2t.visitor_id = '" . (int)$visitor_id . "' ORDER BY td.title ASC");

		foreach ($query->rows as $row) {
			$tags[] = array(
				'tag_id'	=> $row['tag_id'],
				'tag'			=> $row['title']
			);
		}

		return $tags;
	}

	public function getTagsBranch($visitor_id = 0) {
		$tags = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor_tag_branch v2t LEFT JOIN " . DB_PREFIX . "tag_description td ON (v2t.tag_id = td.tag_id) WHERE v2t.visitor_id = '" . (int)$visitor_id . "' ORDER BY td.title ASC");

		foreach ($query->rows as $row) {
			$tags[] = array(
				'tag_id'	=> $row['tag_id'],
				'tag'			=> $row['title']
			);
		}

		return $tags;
	}


	public function getExpByVisitor($visitor_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor_exp 
			WHERE 
			visitor_id = '" . (int)$visitor_id . "' 
			ORDER BY 
			main DESC, exp_id DESC");

		return $query->rows;
	}

	public function deleteExp($visitor_id, $exp_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_exp WHERE visitor_id = '" . (int)$visitor_id . "' AND exp_id = '" . (int)$exp_id . "'");
	}


}
