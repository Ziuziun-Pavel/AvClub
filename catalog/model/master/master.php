<?php
class ModelMasterMaster extends Model {
	public function updateViewed($master_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "master SET viewed = (viewed + 1) WHERE master_id = '" . (int)$master_id . "'");
	}

	public function getMaster($master_id) {
		$this->load->model('visitor/visitor');

        $query = $this->db->query("
            SELECT DISTINCT *, md.title AS title, m.image 
            FROM " . DB_PREFIX . "master m
            LEFT JOIN " . DB_PREFIX . "visitor a ON (a.visitor_id = m.author_id)
            LEFT JOIN " . DB_PREFIX . "master_description md ON (m.master_id = md.master_id)
            WHERE 
            m.master_id = '" . (int)$master_id . "' 
            AND md.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND m.status = '1' 
            AND DATE_ADD(m.date_available, INTERVAL 60 MINUTE) > NOW()
        ");

		if ($query->num_rows) {
			$author_info = $this->model_visitor_visitor->getVisitor($query->row['author_id'], $query->row['author_exp']);

			return array(
				'master_id'  			 	=> $query->row['master_id'],
				'title'        	    => $query->row['title'],
				'author'        	  => $query->row['name'],
				'exp'        	    	=> !empty($author_info['exp']) ? $author_info['exp'] : '',
				'preview'     			=> $query->row['preview'],
				'description'  	    => $query->row['description'],
				'meta_title'     	  => $query->row['meta_title'],
				'meta_h1'         	=> $query->row['meta_h1'],
				'meta_description' 	=> $query->row['meta_description'],
				'meta_keyword'     	=> $query->row['meta_keyword'],
				'link'            	=> $query->row['link'],
				'type'            	=> $query->row['type'],
				'image'            	=> $query->row['image'],
				'logo'            	=> $query->row['logo'],
				'date_available'   	=> $query->row['date_available'],
				'date'   						=> date('d.m.Y', strtotime($query->row['date_available'])),
				'time'   						=> date('H:i', strtotime($query->row['date_available'])),
				'status'           	=> $query->row['status']
			);
		} else {
			return false;
		}
	}

	public function getMasters($data = array()) {
		$sql = "SELECT m.master_id FROM " . DB_PREFIX . "master m ";

		if (!empty($data['filter_company'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "master_company mc ON (m.master_id = mc.master_id) ";
		}

        $sql .= " LEFT JOIN " . DB_PREFIX . "master_description md ON (m.master_id = md.master_id) 
          WHERE 
          md.language_id = '" . (int)$this->config->get('config_language_id') . "' 
          AND m.status = '1' 
          AND DATE_ADD(m.date_available, INTERVAL 60 MINUTE) > NOW() ";

		if (!empty($data['filter_company'])) {
			$sql .= " AND mc.company_id = '" . (int)$data['filter_company'] . "'";
		}

		if (!empty($data['filter_type'])) {
			$sql .= " AND m.type = '" . $this->db->escape($data['filter_type']) . "'";
		}
		
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "md.title LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR md.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "md.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			$sql .= ")";
		}

		$sql .= " GROUP BY m.master_id";

		$sql .= " ORDER BY m.date_available ASC, LCASE(md.title) ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$master_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$master_data[$result['master_id']] = $this->getMaster($result['master_id']);
		}

		return $master_data;
	}


	public function getMasterLayoutId($master_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master_to_layout WHERE master_id = '" . (int)$master_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}


	public function getTotalMasters($data = array()) {
		$sql = "SELECT COUNT(DISTINCT m.master_id) AS total FROM " . DB_PREFIX . "master m ";

		if (!empty($data['filter_company'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "master_company mc ON (m.master_id = mc.master_id) ";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "master_description md ON (m.master_id = md.master_id) 
		WHERE 
		md.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND m.status = '1' 
		AND m.date_available <= NOW() ";

		if (!empty($data['filter_company'])) {
			$sql .= " AND mc.company_id = '" . (int)$data['filter_company'] . "'";
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "md.title LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR md.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "md.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}
			$sql .= ")";
		}


		$query = $this->db->query($sql);

		return $query->row['total'];
	}

    public function getCompaniesByMaster($master_id) {
        $company_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master_company ac 
			LEFT JOIN " . DB_PREFIX . "company c ON (ac.company_id = c.company_id) 
			LEFT JOIN " . DB_PREFIX . "company_description cd ON (ac.company_id = cd.company_id) 
			WHERE 
			ac.master_id = '" . (int)$master_id . "' 
			ORDER BY 
			ac.master_id ASC");

        if($query->num_rows) {
            foreach($query->rows as $row) {
                $company_data[] = array(
                    'company_id'	=> $row['company_id'],
                    'title'				=> $row['title'],
                    'activity'				=> $row['activity'],
                    'description'				=> $row['description'],
                    'image'				=> $row['image'],
                    'status'			=> $row['status'],
                );
            }
        }

        return $company_data;
    }

    public function getAuthorsByMaster($master_id) {
        $this->load->model('visitor/visitor');

        $author_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master_expert a 
			LEFT JOIN " . DB_PREFIX . "visitor v ON (a.author_id = v.visitor_id) 
			WHERE 
			a.master_id = '" . (int)$master_id . "' 
			AND v.image IS NOT NULL AND v.image <> ''
			ORDER BY a.master_id ASC");

        if($query->num_rows) {
            foreach($query->rows as $row) {
                $author_info = $this->model_visitor_visitor->getVisitor($row['visitor_id'], $row['author_exp']);

                $author_data[] = array(
                    'author_id'	=> $row['visitor_id'],
                    'name'			=> $row['name'],
                    'image'			=> $row['image'],
                    'expert'		=> $row['expert'],
                    'exp'				=> !empty($author_info['exp']) ? $author_info['exp'] : '',
                );
            }
        }

        return $author_data;
    }

    public function getSpeakerByMaster($master_id) {
        $this->load->model('visitor/visitor');

        $author_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "master a 
			LEFT JOIN " . DB_PREFIX . "visitor v ON (a.author_id = v.visitor_id) 
			WHERE 
			a.master_id = '" . (int)$master_id . "' 
			AND v.image IS NOT NULL AND v.image <> ''
			ORDER BY a.master_id ASC");

        if($query->num_rows) {
            foreach($query->rows as $row) {
                $author_info = $this->model_visitor_visitor->getVisitor($row['visitor_id'], $row['author_exp']);

                $author_data[] = array(
                    'author_id'	=> $row['visitor_id'],
                    'name'			=> $row['name'],
                    'image'			=> $row['image'],
                    'expert'		=> $row['expert'],
                    'exp'				=> !empty($author_info['exp']) ? $author_info['exp'] : '',
                );
            }
        }

        return $author_data;
    }

    public function getRelated($master_id) {
        $tag_list = array();

        $sql = "SELECT t2.journal_id, count(*) as cnt FROM " . DB_PREFIX . "master_tag t 
		LEFT JOIN " . DB_PREFIX . "journal_tag t2 ON (t.tag_id = t2.tag_id) 
		LEFT JOIN " . DB_PREFIX . "journal m ON (m.journal_id = t2.journal_id) 
		WHERE t.master_id = '".(int)$master_id."' 
		AND m.status = '1' 
		AND m.date_available <= NOW()  
		GROUP BY t2.journal_id 
		ORDER BY cnt DESC, t2.journal_id DESC 
		LIMIT 0,4";

        $query = $this->db->query($sql);

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

    public function getMasterTagsByB24Id($data) {
        $master_tag_data = array();

        if (!empty($data['tags'])) {
            foreach ($data['tags'] as $tag_id) {
                $query = $this->db->query("SELECT tag FROM " . DB_PREFIX . "b24_tags WHERE tag_id = '" . (int)$tag_id . "'");

                if ($query->num_rows > 0) {
                    $master_tag_data[] = $query->row['tag'];
                }
            }

            $tag_ids = array();
            foreach ($master_tag_data as $title) {
                $tag_query = $this->db->query("SELECT tag_id FROM " . DB_PREFIX . "tag_description WHERE title = '" . $this->db->escape($title) . "'");

                if ($tag_query->num_rows > 0) {
                    $tag_ids[] = $tag_query->row['tag_id'];
                }
            }

            return $tag_ids;
        }
    }





}
