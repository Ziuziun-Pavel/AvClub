<?php
class Wishlist {
	private $data = array();
	private $registry;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->session = $registry->get('session');
		$this->visitor = $registry->get('visitor');
		$this->db = $registry->get('db');


		// Remove all the expired wishlists with no customer ID
		$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_wish WHERE visitor_id = '0' AND date_added < DATE_SUB(NOW(), INTERVAL 1 MONTH)");

		if ($this->visitor->getId()) {
			// We want to change the session ID on all the old items in the customers cart
			$this->db->query("UPDATE " . DB_PREFIX . "visitor_wish SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE visitor_id = '" . (int)$this->visitor->getId() . "'");

			// Once the customer is logged in we want to update the customers cart
			$wish_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor_wish WHERE visitor_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

			foreach ($wish_query->rows as $wish) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_wish WHERE wish_id = '" . (int)$wish['wish_id'] . "'");

				$this->add($wish['journal_id']);
			}
		}
	}

	public function getKeyList() {
		$journal_data = array();

		$list = $this->getList();
		foreach($list as $item) {
			$journal_data[] = $item['journal_id'];
		}

		return $journal_data;
	}
	public function getList() {
		$journal_data = array();

		$wish_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "visitor_wish WHERE visitor_id = '" . (int)$this->visitor->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		foreach ($wish_query->rows as $wish) {
			$stock = true;

			$wish_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "journal j LEFT JOIN " . DB_PREFIX . "journal_description jd ON (j.journal_id = jd.journal_id) WHERE jd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND j.date_available <= NOW() AND j.status = '1' AND j.journal_id = '" . (int)$wish['journal_id'] . "'");

			if ($wish_query->num_rows) {
				$journal_data[] = array(
					'wish_id'         => $wish['wish_id'],
					'journal_id'      => $wish_query->row['journal_id'],
					'title'           => $wish_query->row['title'],
					'preview'         => $wish_query->row['preview'],
					'image'           => $wish_query->row['image'],
					'type'           	=> $wish_query->row['type'],
				);
			} else {
				$this->remove($wish['wish_id']);
			}
		}

		return $journal_data;
	}

	public function add($journal_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "visitor_wish WHERE visitor_id = '" . (int)$this->visitor->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND journal_id = '" . (int)$journal_id . "'");

		if (!$query->row['total']) {
			$this->db->query("INSERT " . DB_PREFIX . "visitor_wish SET visitor_id = '" . (int)$this->visitor->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "', journal_id = '" . (int)$journal_id . "', date_added = NOW()");
		}
	}

	public function remove($journal_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_wish WHERE journal_id = '" . (int)$journal_id . "' AND visitor_id = '" . (int)$this->visitor->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function clear() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "visitor_wish WHERE visitor_id = '" . (int)$this->visitor->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function count() {
		$query = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "visitor_wish WHERE visitor_id = '" . (int)$this->visitor->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		return $query->row['total'];
	}

}
