<?php
class ModelSearchSearch extends Model {

	public function getResults($data = array()) {

		$search = trim(preg_replace('/\s+/', ' ', $data['filter_search']));

		$words = array_unique(explode(' ', $search));

		$coeff_title=round((20/count($words)),2);
		$coeff_text=round((10/count($words)),2); 

		$sql_journal = "SELECT jd.journal_id as `id`, j.date_available, (
		IF (LCASE(jd.title) LIKE '%" . $this->db->escape($search) . "%', 60, 0) 
		+ IF (LCASE(jd.description) LIKE '%" . $this->db->escape($search) . "%', 40, 0) ";
		if(count($words) > 1) {
			foreach($words as $word) {
				$sql_journal .= " 
				+ IF (LCASE(jd.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . (int)$coeff_title . ", 0) 
				+ IF (LCASE(jd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . (int)$coeff_text . ", 0) ";
			}
		}
		$sql_journal .= ") AS `relevant`, 
		'journal' as `type` 
		FROM " . DB_PREFIX . "journal_description jd LEFT JOIN " . DB_PREFIX . "journal j ON (j.journal_id = jd.journal_id) WHERE j.date_available <= NOW() AND j.status = '1' HAVING `relevant` > 0 ";


		$sql_event = "SELECT ed.event_id as id, e.date_available, (
		IF (LCASE(ed.title) LIKE '%" . $this->db->escape($search) . "%', 60, 0) 
		+ IF (LCASE(ed.description) LIKE '%" . $this->db->escape($search) . "%', 40, 0) ";
		if(count($words) > 1) {
			foreach($words as $word) {
				$sql_event .= " 
				+ IF (LCASE(ed.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . (int)$coeff_title . ", 0) 
				+ IF (LCASE(ed.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . (int)$coeff_text . ", 0) ";
			}
		}
		$sql_event .= " ) AS `relevant`, 
		'event' as `type` 
		FROM " . DB_PREFIX . "avevent_description ed LEFT JOIN " . DB_PREFIX . "avevent e ON (e.event_id = ed.event_id) WHERE (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) AND e.status = '1' HAVING `relevant` > 0 ";


		$sql_master = "SELECT md.master_id as id, m.date_available, (
		IF (LCASE(md.title) LIKE '%" . $this->db->escape($search) . "%', 60, 0) 
		+ IF (LCASE(md.description) LIKE '%" . $this->db->escape($search) . "%', 40, 0) ";
		if(count($words) > 1) {
			foreach($words as $word) {
				$sql_master .= " 
				+ IF (LCASE(md.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . (int)$coeff_title . ", 0) 
				+ IF (LCASE(md.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . (int)$coeff_text . ", 0) ";
			}
		}
		$sql_master .= ") AS `relevant`, 
		'master' as `type`  
		FROM " . DB_PREFIX . "master_description md LEFT JOIN " . DB_PREFIX . "master m ON (m.master_id = md.master_id) WHERE m.date_available > NOW() AND m.status = '1' HAVING `relevant` > 0 ";


		$sql_company = "SELECT c.company_id as id, NOW() as date_available, (
		IF (LCASE(cd.title) LIKE '%" . $this->db->escape($search) . "%', 60, 0) 
		+ IF (LCASE(cd.description) LIKE '%" . $this->db->escape($search) . "%', 40, 0) ";
		if(count($words) > 1) {
			foreach($words as $word) {
				$sql_company .= " 
				+ IF (LCASE(cd.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . (int)$coeff_title . ", 0) 
				+ IF (LCASE(cd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%', " . (int)$coeff_text . ", 0) ";
			}
		}
		$sql_company .= ") AS `relevant`, 
		'company' as `type`  
		FROM " . DB_PREFIX . "company_description cd LEFT JOIN " . DB_PREFIX . "company c ON (c.company_id = cd.company_id) WHERE c.status = '1' HAVING `relevant` > 0 ";


		$sql = "";

		if(!empty($data['filter_type'])) {
			switch($data['filter_type']) {
				case 'journal':
				$sql = $sql_journal;
				break;

				case 'event':
				$sql = $sql_event;
				break;

				case 'master':
				$sql = $sql_master;
				break;

				case 'company':
				$sql = $sql_company;
				break;

				default:
				$sql = $sql_journal . " UNION " . $sql_event . " UNION " . $sql_master;
			}
		}else{
			$sql = $sql_journal . " UNION " . $sql_event . " UNION " . $sql_master;
		}
		
		if(!empty($data['filter_type']) && $data['filter_type'] === 'company') {
			$sql .= " ORDER BY `relevant` DESC, cd.title ASC";
		}else{
			if (isset($data['sort']) && $data['sort'] === 'date_available') {
				$sql .= " ORDER BY `date_available` DESC, `relevant` DESC";
			}else{
				$sql .= " ORDER BY `relevant` DESC, `date_available` DESC";
			}
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

	public function getTotalResults($data = array()) {

		$search = trim(preg_replace('/\s+/', ' ', $data['filter_search']));

		$words = array_unique(explode(' ', $search));

		$sql_journal = "SELECT jd.journal_id as `id`, j.date_available FROM " . DB_PREFIX . "journal_description jd LEFT JOIN " . DB_PREFIX . "journal j ON (j.journal_id = jd.journal_id) WHERE j.date_available <= NOW() AND j.status = '1' ";
		$implode = array();
		foreach ($words as $word) {
			$implode[] = "LCASE(jd.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
			$implode[] = "LCASE(jd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
		}
		if ($implode) {
			$sql_journal .= " AND ( ";
			$sql_journal .= implode(" OR ", $implode) . "";
			$sql_journal .= " ) ";
		}

		$sql_event = "SELECT ed.event_id as id, e.date_available	FROM " . DB_PREFIX . "avevent_description ed LEFT JOIN " . DB_PREFIX . "avevent e ON (e.event_id = ed.event_id) WHERE (e.date >= CURRENT_DATE() OR e.date_stop >= CURRENT_DATE()) AND e.status = '1' ";
		$implode = array();
		foreach ($words as $word) {
			$implode[] = "LCASE(ed.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
			$implode[] = "LCASE(ed.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
		}
		if ($implode) {
			$sql_event .= " AND ( ";
			$sql_event .= implode(" OR ", $implode) . "";
			$sql_event .= " ) ";
		}

		$sql_master = "SELECT md.master_id as id, m.date_available FROM " . DB_PREFIX . "master_description md LEFT JOIN " . DB_PREFIX . "master m ON (m.master_id = md.master_id) WHERE m.date_available > NOW() AND m.status = '1' ";
		$implode = array();
		foreach ($words as $word) {
			$implode[] = "LCASE(md.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
			$implode[] = "LCASE(md.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
		}
		if ($implode) {
			$sql_master .= " AND ( ";
			$sql_master .= implode(" OR ", $implode) . "";
			$sql_master .= " ) ";
		}

		$sql_company = "SELECT cd.company_id as id FROM " . DB_PREFIX . "company_description cd LEFT JOIN " . DB_PREFIX . "company c ON (c.company_id = cd.company_id) WHERE c.status = '1' ";
		$implode = array();
		foreach ($words as $word) {
			$implode[] = "LCASE(cd.title) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
			$implode[] = "LCASE(cd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
		}
		if ($implode) {
			$sql_company .= " AND ( ";
			$sql_company .= implode(" OR ", $implode) . "";
			$sql_company .= " ) ";
		}


		$sql = "SELECT item.id, COUNT(*) as total FROM (";
		
		if(!empty($data['filter_type'])) {
			switch($data['filter_type']) {
				case 'journal':
				$sql .= $sql_journal;
				break;

				case 'event':
				$sql .= $sql_event;
				break;

				case 'master':
				$sql .= $sql_master;
				break;

				case 'company':
				$sql .= $sql_company;
				break;

				default:
				$sql .= $sql_journal . " UNION " . $sql_event . " UNION " . $sql_master;
			}
		}else{
			$sql .= $sql_journal . " UNION " . $sql_event . " UNION " . $sql_master;
		}

		$sql .= " ) as item";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}
