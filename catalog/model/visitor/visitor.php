<?php
class ModelVisitorVisitor extends Model {
	public function getVisitor($visitor_id, $exp_id = 0) {
		$visitor_info = array();

		$sql = "SELECT DISTINCT 
		v.visitor_id, 
		v.name, 
		v.image,  
		v.expert,  
		v.company_id 
		FROM " . DB_PREFIX . "visitor v 
		WHERE 
		v.visitor_id = '" . (int)$visitor_id . "' ";

		$query = $this->db->query($sql);

		if($query->num_rows) {

			$exp_list = array(
				'main'	=> '',
				'num'		=> ''
			);

			$sql_exp = "SELECT * FROM " . DB_PREFIX . "visitor_exp ve 
			WHERE 
			ve.visitor_id = '" . (int)$visitor_id . "'
			AND ( 
			ve.exp_id = " . (int)$exp_id . " 
			OR ve.main = '1' ) 
			";

			$query_exp = $this->db->query($sql_exp);

			if($query_exp->num_rows) {
				foreach($query_exp->rows as $row) {
					if($row['main']) {
						$exp_list['main'] = $row['exp'];
					}else{
						$exp_list['num'] = $row['exp'];
					}
				}	
			}

			//$exp = !empty($exp_list['num']) ? $exp_list['num'] : $exp_list['main'];
			$exp = $exp_list['main'];

			$visitor_info = array(
				'visitor_id'		=> $query->row['visitor_id'],
				'name'					=> $query->row['name'],
				'expert'				=> $query->row['expert'],
				'image'					=> $query->row['image'],
				'company_id'		=> $query->row['company_id'],
				'exp'						=> $this->mb_ucfirst($exp),
			);
		}

		return $visitor_info;
	}

	private function mb_ucfirst($string, $encoding = 'UTF-8'){
		$strlen = mb_strlen($string, $encoding);
		$firstChar = mb_substr($string, 0, 1, $encoding);
		$then = mb_substr($string, 1, $strlen - 1, $encoding);
		return mb_strtoupper($firstChar, $encoding) . $then;
	}
}