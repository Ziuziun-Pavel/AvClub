<?php
class Visitor {
	private $hash_key = "av_c1b";

	private $visitor_id;
	private $b24id;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if(
			!empty($this->request->cookie['expert_id']) 
			&& !empty($this->request->cookie['expert_data']) 
			&& $this->request->cookie['expert_data'] === $this->hashId($this->request->cookie['expert_id'])
		) {
			$visitor_id = $this->request->cookie['expert_id'];
		}

		if(!empty($visitor_id)) {
			$visitor_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor WHERE visitor_id = '" . (int)$visitor_id . "' AND status = '1'");
			if ($visitor_query->num_rows) {

				$this->visitor_id = $visitor_query->row['visitor_id'];
				$this->b24id = $visitor_query->row['b24id'];

				$this->updateExpertCookie();
			}else{
				$this->logout();
			}
		}

	}

	public function login($visitor_id) {

		$visitor_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor WHERE visitor_id = '" . (int)$visitor_id . "' AND status = '1'");
		if ($visitor_query->num_rows) {

			$this->visitor_id = $visitor_query->row['visitor_id'];
			$this->b24id = $visitor_query->row['b24id'];

			$this->updateExpertCookie();

			return true;
		}else{
			return false;
		}

	}

	public function logout() {

		setcookie("expert_id", '', time() - 3600 * 24 * 30, '/');
		setcookie("expert_data", '', time() - 3600 * 24 * 30, '/');

		$this->visitor_id = 0;
		$this->b24id = 0;
		
	}

	public function isLogged() {
		return $this->visitor_id;
	}

	public function getId() {
		return $this->visitor_id;
	}

	public function getB24id() {
		return $this->b24id;
	}

	private function updateExpertCookie() {
		setcookie("expert_id", $this->visitor_id, time() + 3600 * 24 * 30, '/');
		setcookie("expert_data", $this->hashId($this->visitor_id), time() + 3600 * 24 * 30, '/');
	}

	private function hashId($id = 0) {
		return sha1($this->hash_key . $id . $this->hash_key . sha1($this->hash_key . sha1($id)));
	}

}
