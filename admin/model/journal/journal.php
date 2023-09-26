<?php
class ModelJournalJournal extends Model {
	public function addJournal($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "journal SET 
			date_available = '" . $this->db->escape($data['date_available']) . "', 
			image = '" . $this->db->escape($data['image']) . "', 
			image_show = '" . (int)$data['image_show'] . "', 
			video = '" . $this->db->escape($data['video']) . "', 
			link = '" . $this->db->escape($data['link']) . "', 
			type = '" . $this->db->escape($data['type']) . "', 
			sort_order = '" . (int)$data['sort_order'] . "', 
			author_id = '" . (!empty($data['author_id']) ? (int)$data['author_id'] : 0) . "', 
			author_exp = '" . (!empty($data['author_exp']) ? (int)$data['author_exp'] : 0) . "', 
			master_id = '" . (int)$data['master_id'] . "', 
			master_old = '" . (int)$data['master_old'] . "', 
			status = '0' 
			");

		$journal_id = $this->db->getLastId();

		foreach ($data['journal_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "journal_description SET 
				journal_id = '" . (int)$journal_id . "', 
				language_id = '" . (int)$language_id . "', 
				title = '" . $this->db->escape($value['title']) . "', 
				description = '" . $this->db->escape($value['description']) . "', 
				preview = '" . $this->db->escape($value['preview']) . "', 
				meta_title = '" . $this->db->escape($value['meta_title']) . "', 
				meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', 
				meta_description = '" . $this->db->escape($value['meta_description']) . "', 
				meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "' 
				");
		}

		if (isset($data['copy'])) {
			foreach ($data['copy'] as $copy) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_copy SET journal_id = '" . (int)$journal_id . "', sort_order = '" . (int)$copy['sort_order'] . "', text = '" . $this->db->escape($copy['text']) . "'");
			}
		}

		if (isset($data['tag'])) {
			foreach ($data['tag'] as $tag_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_tag SET journal_id = '" . (int)$journal_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}
		if (isset($data['tag_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['tag_new'] as $tag_name) {
				$tag_id = $this->model_tag_tag->addTagByName($tag_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_tag SET journal_id = '" . (int)$journal_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}

		if (isset($data['column'])) {
			foreach($data['column'] as $column) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_column SET 
					journal_id = '" . (int)$journal_id . "', 
					column_id = '" . (int)$column['column_id']. "', 
					size = '" . json_encode($column['size']) . "', 
					text_left = '" . $this->db->escape($column['text_left']) . "', 
					text_right = '" . $this->db->escape($column['text_right']) . "' 
					");
			}
		}

		if (isset($data['case'])) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "journal_case SET 
				journal_id = '" . (int)$journal_id . "', 
				template = '" . (int)$data['case']['template'] . "', 
				bottom = '" . (int)$data['case']['bottom'] . "', 
				title = '" . $this->db->escape($data['case']['title']) . "', 
				description = '" . $this->db->escape($data['case']['description']) . "', 
				logo = '" . $this->db->escape($data['case']['logo']) . "' 
				");

			$case_id = $this->db->getLastId();

			if (isset($data['case']['attr'])) {
				foreach ($data['case']['attr'] as $case_attr) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "journal_case_attr SET case_id = '" . (int)$case_id . "', journal_id = '" . (int)$journal_id . "', sort_order = '" . (int)$case_attr['sort_order'] . "', catalog = '" . (int)$case_attr['catalog'] . "', title = '" . $this->db->escape($case_attr['title']) . "', text = '" . $this->db->escape($case_attr['text']) . "'");
				}
			}

		}

		$gallery_list = array(1,2,3,4,5);
		foreach($gallery_list as $gallery) {
			if (isset($data['gallery_' . $gallery])) {
				foreach ($data['gallery_' . $gallery] as $key => $image) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "journal_gallery SET gallery_id = '" . (int)$gallery . "', journal_id = '" . (int)$journal_id . "', image = '" . $this->db->escape($image) . "', sort_order = '" . (int)$key . "'");
				}
			}
		}

		if(!empty($data['experts'])) {
			foreach($data['experts'] as $key=>$expert) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_expert SET 
					author_id = '" . (int)$expert['author_id'] . "',  
					author_exp = '" . (int)$expert['exp_id'] . "', 
					journal_id = '" . (int)$journal_id . "'");
			}
		}

		

		if (isset($data['journal_layout'])) {
			foreach ($data['journal_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_to_layout SET journal_id = '" . (int)$journal_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'journal_id=" . (int)$journal_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('journal');

		return $journal_id;
	}

	public function editJournal($journal_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "journal SET 
			date_available = '" . $this->db->escape($data['date_available']) . "', 
			image = '" . $this->db->escape($data['image']) . "', 
			image_show = '" . (int)$data['image_show'] . "', 
			video = '" . $this->db->escape($data['video']) . "', 
			link = '" . $this->db->escape($data['link']) . "', 
			type = '" . $this->db->escape($data['type']) . "', 
			sort_order = '" . (int)$data['sort_order'] . "', 
			author_id = '" . (!empty($data['author_id']) ? (int)$data['author_id'] : 0) . "', 
			author_exp = '" . (!empty($data['author_exp']) ? (int)$data['author_exp'] : 0) . "', 
			master_id = '" . (int)$data['master_id'] . "', 
			master_old = '" . (int)$data['master_old'] . "' 
			WHERE journal_id = '" . (int)$journal_id . "' 
			");

		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_description WHERE journal_id = '" . (int)$journal_id . "'");

		foreach ($data['journal_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "journal_description SET journal_id = '" . (int)$journal_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', preview = '" . $this->db->escape($value['preview']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_copy WHERE journal_id = '" . (int)$journal_id . "'");
		if (isset($data['copy'])) {
			foreach ($data['copy'] as $copy) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_copy SET journal_id = '" . (int)$journal_id . "', sort_order = '" . (int)$copy['sort_order'] . "', text = '" . $this->db->escape($copy['text']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_tag WHERE journal_id = '" . (int)$journal_id . "'");
		if (isset($data['tag'])) {
			foreach ($data['tag'] as $tag_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_tag SET journal_id = '" . (int)$journal_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}
		if (isset($data['tag_new'])) {
			$this->load->model('tag/tag');
			foreach ($data['tag_new'] as $tag_name) {
				$tag_id = $this->model_tag_tag->addTagByName($tag_name);
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_tag SET journal_id = '" . (int)$journal_id . "', tag_id = '" . (int)$tag_id . "'");
			}
		}	

		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_column WHERE journal_id = '" . (int)$journal_id . "'");
		if (isset($data['column'])) {
			foreach($data['column'] as $column) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_column SET 
					journal_id = '" . (int)$journal_id . "', 
					column_id = '" . (int)$column['column_id']. "', 
					size = '" . json_encode($column['size']) . "', 
					text_left = '" . $this->db->escape($column['text_left']) . "', 
					text_right = '" . $this->db->escape($column['text_right']) . "' 
					");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_case WHERE journal_id = '" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_case_attr WHERE journal_id = '" . (int)$journal_id . "'");
		if (isset($data['case'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "journal_case SET journal_id = '" . (int)$journal_id . "', template = '" . (int)$data['case']['template'] . "', bottom = '" . (int)$data['case']['bottom'] . "', title = '" . $this->db->escape($data['case']['title']) . "', description = '" . $this->db->escape($data['case']['description']) . "', logo = '" . $this->db->escape($data['case']['logo']) . "'");

			$case_id = $this->db->getLastId();

			if (isset($data['case']['attr'])) {
				foreach ($data['case']['attr'] as $case_attr) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "journal_case_attr SET case_id = '" . (int)$case_id . "', journal_id = '" . (int)$journal_id . "', sort_order = '" . (int)$case_attr['sort_order'] . "', catalog = '" . (int)$case_attr['catalog'] . "', title = '" . $this->db->escape($case_attr['title']) . "', text = '" . $this->db->escape($case_attr['text']) . "'");
				}
			}
		}

		$gallery_list = array(1,2,3,4,5);
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_gallery WHERE journal_id = '" . (int)$journal_id . "'");
		foreach($gallery_list as $gallery) {
			if (isset($data['gallery_' . $gallery])) {
				foreach ($data['gallery_' . $gallery] as $key => $image) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "journal_gallery SET gallery_id = '" . (int)$gallery . "', journal_id = '" . (int)$journal_id . "', image = '" . $this->db->escape($image) . "', sort_order = '" . (int)$key . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_expert WHERE journal_id = '" . (int)$journal_id . "'");
		if(!empty($data['experts'])) {
			foreach($data['experts'] as $key=>$expert) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_expert SET 
					author_id = '" . (int)$expert['author_id'] . "',  
					author_exp = '" . (int)$expert['exp_id'] . "', 
					journal_id = '" . (int)$journal_id . "'");
			}
		}


		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_to_layout WHERE journal_id = '" . (int)$journal_id . "'");

		if (isset($data['journal_layout'])) {
			foreach ($data['journal_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "journal_to_layout SET journal_id = '" . (int)$journal_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'journal_id=" . (int)$journal_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'journal_id=" . (int)$journal_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('journal');
	}


	public function saveJournal($journal_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "journal SET 
			status = '" . (int)$data['status'] . "' 
			WHERE journal_id = '" . (int)$journal_id . "' 
			");

		$this->cache->delete('journal');
	}

	public function deleteJournal($journal_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal WHERE journal_id = '" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_expert WHERE journal_id = '" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_description WHERE journal_id = '" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_to_layout WHERE journal_id = '" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'journal_id=" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_copy WHERE journal_id = '" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_tag WHERE journal_id = '" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_column WHERE journal_id = '" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_case WHERE journal_id = '" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_case_attr WHERE journal_id = '" . (int)$journal_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "journal_gallery WHERE journal_id = '" . (int)$journal_id . "'");
		$this->cache->delete('journal');
	}

	public function getColumn($journal_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal_column WHERE journal_id = '" . (int)$journal_id . "'  ORDER BY column_id ASC");

		return $query->rows;
	}

	public function getGallery($journal_id, $gallery_id) {
		$data = array();

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "journal_gallery WHERE journal_id = '" . (int)$journal_id . "' AND gallery_id = '" . (int)$gallery_id . "'  ORDER BY sort_order ASC");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				$data[] = $row['image'];
			}
		}

		return $data;
	}

	public function getJournal($journal_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'journal_id=" . (int)$journal_id . "' LIMIT 1) AS keyword FROM " . DB_PREFIX . "journal WHERE journal_id = '" . (int)$journal_id . "'");

		return $query->row;
	}

	public function getJournals($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "journal j LEFT JOIN " . DB_PREFIX . "journal_description jd ON (j.journal_id = jd.journal_id) WHERE jd.language_id = '" . (int)$this->config->get('config_language_id') . "'";


			if (!empty($data['filter_type'])) {
				$sql .= " AND j.type = '" . $this->db->escape($data['filter_type']) . "' ";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " AND jd.title LIKE '%" . $this->db->escape($data['filter_name']) . "%' ";
			}

			if(isset($data['filter_master_old']) && !is_null($data['filter_master_old'])){
				$sql .= " AND j.master_old = '" . (int)$data['filter_master_old'] . "' ";
			}

			$sort_data = array(
				'jd.title',
				'j.date_available',
				'j.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY jd.title";
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
			$journal_data = $this->cache->get('journal.' . (int)$this->config->get('config_language_id'));

			if (!$journal_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal j LEFT JOIN " . DB_PREFIX . "journal_description jd ON (j.journal_id = jd.journal_id) WHERE jd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY jd.title");

				$journal_data = $query->rows;

				$this->cache->set('journal.' . (int)$this->config->get('config_language_id'), $journal_data);
			}

			return $journal_data;
		}
	}

	public function getJournalDescriptions($journal_id) {
		$journal_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal_description WHERE journal_id = '" . (int)$journal_id . "'");

		foreach ($query->rows as $result) {
			$journal_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'preview'      => $result['preview'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_h1'          => $result['meta_h1'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $journal_description_data;
	}

	public function getCase($journal_id) {
		$case_data = array();
		$case_attr = array();

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "journal_case WHERE journal_id = '" . (int)$journal_id . "'");

		if($query->num_rows) {

			$query_attr = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "journal_case_attr WHERE journal_id = '" . (int)$journal_id . "' AND case_id='" . (int)$query->row['case_id'] . "' ORDER BY sort_order ASC");
			if($query_attr->num_rows) {
				foreach($query_attr->rows as $attr) {
					$case_attr[] = array(
						'title'					=>	$attr['title'],
						'text'					=>	$attr['text'],
						'catalog'				=>	$attr['catalog'],
						'sort_order'		=>	$attr['sort_order'],
					);
				}
			}
			$case_data = array(
				'title'					=>	$query->row['title'],
				'description'		=>	$query->row['description'],
				'logo'					=>	$query->row['logo'],
				'template'			=>	$query->row['template'],
				'bottom'				=>	$query->row['bottom'],
				'attr'					=>	$case_attr
			);
		}

		return $case_data;
	}

	public function getCopies($journal_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal_copy WHERE journal_id = '" . (int)$journal_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}


	public function getJournalLayouts($journal_id) {
		$journal_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal_to_layout WHERE journal_id = '" . (int)$journal_id . "'");

		foreach ($query->rows as $result) {
			$journal_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $journal_layout_data;
	}


	public function getJournalExperts($journal_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal_expert WHERE journal_id = '" . (int)$journal_id . "'");

		return $query->rows;
	}

	public function getJournalTags($journal_id) {
		$journal_tag_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal_tag jt LEFT JOIN " . DB_PREFIX . "tag_description td ON (jt.tag_id = td.tag_id) WHERE journal_id = '" . (int)$journal_id . "' ORDER BY td.title ASC");

		foreach ($query->rows as $row) {
			$journal_tag_data[] = array(
				'tag_id'	=> $row['tag_id'],
				'tag'			=> $row['title']
			);
		}

		return $journal_tag_data;
	}

	public function getTotalJournals($data=array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "journal j LEFT JOIN " . DB_PREFIX . "journal_description jd ON (j.journal_id = jd.journal_id) WHERE jd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

		if (!empty($data['filter_type'])) {
			$sql .= " AND j.type = '" . $this->db->escape($data['filter_type']) . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND jd.title LIKE '%" . $this->db->escape($data['filter_name']) . "%' ";
		}

		if(isset($data['filter_master_old']) && !is_null($data['filter_master_old'])){
			$sql .= " AND j.master_old = '" . (int)$data['filter_master_old'] . "' ";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalJournalsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "journal_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}