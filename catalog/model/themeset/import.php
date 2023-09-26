<?php
class ModelThemesetImport extends Model {

	public function importFirst($articles) {
		$count = 0;

		foreach($articles as $item) {
			$type = !empty($item['type']) ? $item['type'] : 'article';
			$master_old = 0;

			if($type === 'video_mk') {
				$type = 'video';
				$master_old = 1;
			}


			$this->db->query("INSERT INTO " . DB_PREFIX . "import SET 
				type = '" . $this->db->escape($type) . "' 
				, title = '" . $this->db->escape($item['title']) . "' 
				, description = '' 
				, video = '' 
				, master_old = '" . (int)$master_old . "' 
				, preview = '' 
				, image = '' 
				, preview_image = '' 
				, detail_image = '' 
				, gallery = '' 
				, author = '' 
				, source = '' 
				, link = '" . $this->db->escape($item['link']) . "' 
				, code = '' 
				, date_available = NOW() 
				, flag_old = '0' 
				, flag_text = '0' 
				, flag_tag = '0' 
				, flag_source = '0' 
				, flag_import = '0' 
				");
			$count++;
		}
		
		return $count;
	}


	public function updateCode() {
		$count = 0;

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "import WHERE code = ''");
		if($query->num_rows) {
			foreach($query->rows as $row) {
				$path = explode(' ', trim(str_replace('/',' ',$row['link'])));
				$code = array_pop($path);
				if($code) {
					$this->db->query("UPDATE " . DB_PREFIX . "import SET code = '" . $this->db->escape($code) . "' WHERE import_id='" . (int)$row['import_id']. "'");
					$count++;
				}
			}
		}

		return $count;
	}

	


	public function importLinksFromOld() {
		$data = array(
			'count'	=> 0,
			'total'	=> 0
		);

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "import i");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}
		
		// search by code
		$sql1 = "SELECT i.import_id, ep.VALUE 
		FROM " . DB_PREFIX . "import i 
		LEFT JOIN " . DB_PREFIX . "b_iblock_element old ON (i.code = old.CODE) 
		LEFT JOIN " . DB_PREFIX . "b_iblock_element_property ep ON (ep.IBLOCK_ELEMENT_ID = old.ID) 
		WHERE old.ID > 0 AND ep.IBLOCK_PROPERTY_ID = '8'";

		
		$sql2 = "SELECT i.import_id, ep.VALUE 
		FROM " . DB_PREFIX . "import i 
		LEFT JOIN " . DB_PREFIX . "b_iblock_element old ON (i.title = old.NAME) 
		LEFT JOIN " . DB_PREFIX . "b_iblock_element_property ep ON (ep.IBLOCK_ELEMENT_ID = old.ID) 
		WHERE i.title = old.NAME AND ep.IBLOCK_PROPERTY_ID = '8'";


		$query = $this->db->query($sql1 . " UNION " . $sql2);
		foreach($query->rows as $row) {
			
			// update import
			$this->db->query("UPDATE " . DB_PREFIX . "import SET  
				source = '" . $this->db->escape($row['VALUE']) . "' 
				WHERE import_id='" . (int)$row['import_id']. "'");
			$data['count']++;
		}

		return $data;
	}

	public function updateLinksFromOld_() {
		$data = array(
			'count'	=> 0,
			'total'	=> 0
		);

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "import i WHERE i.flag_source = '0' AND i.source <> '' AND i.journal_id > 0");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}
		

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "import i WHERE i.flag_source = '0' AND i.source <> '' AND i.journal_id > 0");

		foreach($query->rows as $row) {
			
			// update description
			$description = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "journal_description jd WHERE jd.journal_id = '" . (int)$row['journal_id']. "' AND jd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

			if($description->num_rows) {
				$new_descr = $description->row['description'] . '<div class="author"><strong>Источник:</strong> ' . $row['source'] . '</div>';
				$this->db->query("UPDATE " . DB_PREFIX . "journal_description SET 
				description = '" .  $this->db->escape($new_descr) . "' 
				WHERE journal_id='" . (int)$row['journal_id']. "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
			}

			
			// update import
			$this->db->query("UPDATE " . DB_PREFIX . "import SET  
				flag_source = '1' 
				WHERE import_id='" . (int)$row['import_id']. "'");

			$data['count']++;
		}

		return $data;
	}


	public function updateShowImages_() {
		$data = array(
			'count'	=> 0,
			'total'	=> 0
		);

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "import i WHERE i.detail_image <> '' AND i.journal_id > 0 AND i.type NOT LIKE 'video' AND i.gallery LIKE '[]'");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}
		

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "import i WHERE i.detail_image <> '' AND i.journal_id > 0 AND i.type NOT LIKE 'video' AND i.gallery LIKE '[]'");

		foreach($query->rows as $row) {
			
			
			// update import
			$this->db->query("UPDATE " . DB_PREFIX . "journal SET  
				image_show = '1' 
				WHERE journal_id='" . (int)$row['journal_id']. "'");

			$data['count']++;
		}

		return $data;
	}


	public function importImagesFromOld() {
		$data = array(
			'count'	=> 0,
			'total'	=> 0
		);

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "import i");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}
		
		// search by code
		$sql1 = "SELECT i.import_id, (SELECT CONCAT(bf.SUBDIR, '/', bf.FILE_NAME) FROM " . DB_PREFIX . "b_file bf WHERE bf.ID = old.DETAIL_PICTURE) as detail_image, (SELECT CONCAT(bf2.SUBDIR, '/', bf2.FILE_NAME) FROM " . DB_PREFIX . "b_file bf2 WHERE bf2.ID = old.PREVIEW_PICTURE) as preview_image FROM " . DB_PREFIX . "import i LEFT JOIN " . DB_PREFIX . "b_iblock_element old ON (i.code = old.CODE) WHERE old.ID > 0";
		
		$sql2 = "SELECT i.import_id, (SELECT CONCAT(bf.SUBDIR, '/', bf.FILE_NAME) FROM " . DB_PREFIX . "b_file bf WHERE bf.ID = old.DETAIL_PICTURE) as detail_image, (SELECT CONCAT(bf2.SUBDIR, '/', bf2.FILE_NAME) FROM " . DB_PREFIX . "b_file bf2 WHERE bf2.ID = old.PREVIEW_PICTURE) as preview_image FROM " . DB_PREFIX . "import i LEFT JOIN " . DB_PREFIX . "b_iblock_element old ON (i.title = old.NAME) WHERE i.title = old.NAME";


		$query = $this->db->query($sql1 . " UNION " . $sql2);
		foreach($query->rows as $row) {
			
			// update import
			$this->db->query("UPDATE " . DB_PREFIX . "import SET  
				detail_image = '" . $this->db->escape($row['detail_image']) . "', 
				preview_image = '" . $this->db->escape($row['preview_image']) . "' 
				WHERE import_id='" . (int)$row['import_id']. "'");
			$data['count']++;
		}

		return $data;
	}

	public function importTextFromOld() {
		$data = array(
			'count'	=> 0,
			'total'	=> 0
		);

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "import i WHERE i.flag_old = '0'");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}
		
		// search by code
		$sql1 = "SELECT i.import_id, i.type, old.ID, old.PREVIEW_TEXT, old.DETAIL_TEXT, old.DATE_CREATE, old.XML_ID, 
		(SELECT CONCAT(bf.SUBDIR, '/', bf.FILE_NAME) FROM " . DB_PREFIX . "b_file bf WHERE bf.ID = old.DETAIL_PICTURE) as detail_image, 
		(SELECT CONCAT(bf2.SUBDIR, '/', bf2.FILE_NAME) FROM " . DB_PREFIX . "b_file bf2 WHERE bf2.ID = old.PREVIEW_PICTURE) as preview_image, 
		(SELECT ep2.VALUE FROM " . DB_PREFIX . "b_iblock_element_property ep2 WHERE ep2.IBLOCK_ELEMENT_ID = old.ID  AND ep2.IBLOCK_PROPERTY_ID = '8') as source 
		FROM " . DB_PREFIX . "import i LEFT JOIN " . DB_PREFIX . "b_iblock_element old ON (i.code = old.CODE) WHERE i.flag_old = '0' AND old.ID > 0";
		
		$sql2 = "SELECT i.import_id, i.type, old.ID, old.PREVIEW_TEXT, old.DETAIL_TEXT, old.DATE_CREATE, old.XML_ID, 
		(SELECT CONCAT(bf.SUBDIR, '/', bf.FILE_NAME) FROM " . DB_PREFIX . "b_file bf WHERE bf.ID = old.DETAIL_PICTURE) as detail_image, 
		(SELECT CONCAT(bf2.SUBDIR, '/', bf2.FILE_NAME) FROM " . DB_PREFIX . "b_file bf2 WHERE bf2.ID = old.PREVIEW_PICTURE) as preview_image, 
		(SELECT ep2.VALUE FROM " . DB_PREFIX . "b_iblock_element_property ep2 WHERE ep2.IBLOCK_ELEMENT_ID = old.ID  AND ep2.IBLOCK_PROPERTY_ID = '8') as source  
		FROM " . DB_PREFIX . "import i LEFT JOIN " . DB_PREFIX . "b_iblock_element old ON (i.title = old.NAME) WHERE i.flag_old = '0' AND i.title = old.NAME";


		$query = $this->db->query($sql1 . " UNION " . $sql2);
		foreach($query->rows as $row) {

			// author
			$author = array();
			if($row['type'] === 'opinion') {
				$author_query = $this->db->query("SELECT DISTINCT a.NAME, a.PREVIEW_TEXT as exp, bf.SUBDIR, bf.FILE_NAME FROM " . DB_PREFIX . "b_iblock_element a LEFT JOIN " . DB_PREFIX . "b_iblock_element_property ep ON (a.ID = ep.VALUE) LEFT JOIN " . DB_PREFIX . "b_file bf ON (a.DETAIL_PICTURE = bf.ID) WHERE ep.IBLOCK_ELEMENT_ID = '" . (int)$row['ID'] . "' AND ep.IBLOCK_PROPERTY_ID = '64'");
				if($author_query->num_rows) {
					$author = array(
						'name'	=> $author_query->row['NAME'],
						'exp'		=> $author_query->row['exp'],
						'image'	=> $author_query->row['FILE_NAME'] ? $author_query->row['SUBDIR'] . '/' . $author_query->row['FILE_NAME'] : '',
					);
				}	
			}

			// gallery
			$gallery = array();
			$gallery_query = $this->db->query("SELECT bf.SUBDIR, bf.FILE_NAME FROM " . DB_PREFIX . "b_file bf LEFT JOIN " . DB_PREFIX . "b_iblock_element_property ep ON (bf.ID = ep.VALUE) WHERE ep.IBLOCK_ELEMENT_ID = '" . (int)$row['ID'] . "' AND ep.IBLOCK_PROPERTY_ID = '174'");
			if($gallery_query->num_rows) {
				foreach($gallery_query->rows as $img) {
					$gallery[] = $img['SUBDIR'] . '/' . $img['FILE_NAME'];
				}
			}


			// description
			$video = '';
			if($row['type'] === 'video' && $row['XML_ID']) {
				$video .= '<iframe width="100%" height="360" src="//www.youtube.com/embed/'.$row['XML_ID'].'?feature=player_detailpage" frameborder="0" allowfullscreen></iframe><br>';
			}

			// image
			$image = '';
			if($row['detail_image'] && utf8_strlen($row['detail_image']) > 1) {
				$image = $row['detail_image'];
			}else if($row['preview_image'] && utf8_strlen($row['preview_image']) > 1) {
				$image = $row['preview_image'];
			}
			
			// update import
			$this->db->query("UPDATE " . DB_PREFIX . "import SET 
				preview = '" . $this->db->escape($row['PREVIEW_TEXT']) . "', 
				description = '" . $this->db->escape($row['DETAIL_TEXT']) . "', 
				video = '" . $this->db->escape($video) . "', 
				image = '" . $this->db->escape($image) . "', 
				detail_image = '" . $this->db->escape($row['detail_image']) . "', 
				preview_image = '" . $this->db->escape($row['preview_image']) . "', 
				source = '" . $this->db->escape($row['source']) . "', 
				gallery = '" . $this->db->escape(json_encode($gallery)) . "', 
				author = '" . $this->db->escape(json_encode($author)) . "', 
				date_available = '" . $this->db->escape($row['DATE_CREATE']) . "', 
				flag_old = '1' 
				WHERE import_id='" . (int)$row['import_id']. "'");
			$data['count']++;
		}
		



		return $data;
	}

	public function updateVideoPreview() {
		$sql1 = "SELECT i.import_id, i.type, old.ID, old.PREVIEW_TEXT, old.DETAIL_TEXT, old.DATE_CREATE, old.XML_ID, (SELECT CONCAT(bf.SUBDIR, '/', bf.FILE_NAME) FROM " . DB_PREFIX . "b_file bf WHERE bf.ID = old.DETAIL_PICTURE) as image, (SELECT CONCAT(bf2.SUBDIR, '/', bf2.FILE_NAME) FROM " . DB_PREFIX . "b_file bf2 WHERE bf2.ID = old.PREVIEW_PICTURE) as image2 FROM " . DB_PREFIX . "import i LEFT JOIN " . DB_PREFIX . "b_iblock_element old ON (i.code = old.CODE) WHERE i.type = 'video' AND old.XML_ID <> '' AND old.ID > 0";
		
		$sql2 = "SELECT i.import_id, i.type, old.ID, old.PREVIEW_TEXT, old.DETAIL_TEXT, old.DATE_CREATE, old.XML_ID, (SELECT CONCAT(bf.SUBDIR, '/', bf.FILE_NAME) FROM " . DB_PREFIX . "b_file bf WHERE bf.ID = old.DETAIL_PICTURE) as image, (SELECT CONCAT(bf2.SUBDIR, '/', bf2.FILE_NAME) FROM " . DB_PREFIX . "b_file bf2 WHERE bf2.ID = old.PREVIEW_PICTURE) as image2 FROM " . DB_PREFIX . "import i LEFT JOIN " . DB_PREFIX . "b_iblock_element old ON (i.title = old.NAME) WHERE i.type = 'video' AND old.XML_ID <> '' AND i.title = old.NAME";

		$query = $this->db->query($sql1 . " UNION " . $sql2);

		$list_sd = array(
			'-Owze8dBuZw',
			'JXtE5wOOxZs',
			'6fEnTeCeGbQ',
			'c0yEIKAgkE0',
			'0X4M6NhxTx0',
			'KLh7cNHlivU',
			'btI63OPmsEg',
			'cZ2bfQY_-dU',
			'DrIyxb1pX08',
			'KM6PQYyq2Ls',
			'Ym5V8o3fnz4',
		);
		$list_hq = array(
			'VvemT96Rozc',
			'I-99j77p0dU',
			'D75E-HbADHg',
			'Fik9rWa5vkw',
		);
		if($query->num_rows) {
			foreach($query->rows as $row) {
				if(in_array($row['XML_ID'], $list_sd)) {
					$src = 'vi/'.$row['XML_ID'].'/sddefault.jpg';
				}else if(in_array($row['XML_ID'], $list_hq)) {
					$src = 'vi/'.$row['XML_ID'].'/hqdefault.jpg';
				}else{
					$src = 'vi/'.$row['XML_ID'].'/maxresdefault.jpg';
				}

				echo 'http://img.youtube.com/' . $src . '<br>';
				
				$image = 'catalog/' . $src;
				$this->db->query("UPDATE " . DB_PREFIX . "import SET 
				image = '" . $this->db->escape($image) . "' 
				WHERE import_id='" . (int)$row['import_id']. "'");

			}
		}

	}
	public function updateImages() {
		$data = array(
			'count'	=> 0,
			'total'	=> 0
		);

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "import i WHERE i.flag_old = '1' AND i.flag_text = '0'");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "import i WHERE i.flag_old = '1' AND i.flag_text = '0'");

		$handle  = fopen(DIR_APPLICATION . 'controller/themeset/image_list.txt', 'a');

		$image = array();

		foreach($query->rows as $row) {
			// preview image
			$image = $row['image'];
			if($image) {
				$images[] = 'https://www.avclub.pro/upload/' . $image;
				$image = 'catalog/upload/' . $image;
			}

			// author image
			$author = json_decode($row['author'], true);
			if(!empty($author['image'])) {
				$images[] = 'https://www.avclub.pro/upload/' . $author['image'];
				$author['image'] = 'catalog/upload/' . $author['image'];
			}

			// gallery
			$gallery = json_decode($row['gallery'], true);
			if(!empty($gallery)) {
				foreach($gallery as $key=>$img) {
					$images[] = 'https://www.avclub.pro/upload/' . $img;
					$gallery[$key] = 'catalog/upload/' . $img;
				}
			}

			$description = $row['description'];
			$description = str_replace('src="http://www.avclub.pro','src="',$description);
			$description = str_replace('src="https://www.avclub.pro','src="',$description);

			$dom = new DOMDocument();
			@$dom->loadHTML($description);
			$nodes = $dom->getElementsByTagName('img');
			foreach($nodes as $key=>$img){
				$src = $img->getAttribute('src');

				$pos = strpos($src, '/upload');
				if($pos == 0 && $pos !== false) {
					if(!$image) {$image = 'catalog' . $src;}
					$images[] = 'https://www.avclub.pro/' . $src;
				}
			}


			$description = str_replace('src="/upload','src="/image/catalog/upload',$description);
			

			$this->db->query("UPDATE " . DB_PREFIX . "import SET 
				description = '" . $this->db->escape($description) . "', 
				gallery = '" . $this->db->escape(json_encode($gallery)) . "', 
				author = '" . $this->db->escape(json_encode($author)) . "', 
				image = '" . ($image ? $this->db->escape($image) : '') . "', 
				flag_text = '1' 
				WHERE import_id='" . (int)$row['import_id']. "'");
			$data['count']++;
		}
		echo 'Изображений: ' . count($images) . '<br>';
		$images = array_unique($images);
		echo 'Уникальных: ' . count($images) . '<br>';

		foreach($images as $src) {
			fwrite($handle, $src . "\n");
		}

		fclose($handle);

		return $data;
	}

	public function importArticles() {
		$data = array(
			'count'	=> 0,
			'total'	=> 0
		);

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "import i WHERE i.flag_old = '1' AND i.flag_text = '1' AND i.flag_import = '0'");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "import i WHERE i.flag_old = '1' AND i.flag_text = '1' AND i.flag_import = '0'");

		foreach($query->rows as $row) {			
			$author = json_decode($row['author'], true);
			$gallery = json_decode($row['gallery'], true);

			$description = $row['description'];
			if($row['video']) {
				$description = $row['video'] . $description;
			}

			$flag_source = 0;
			if($row['source']) {
				$description = $description . '<div class="author"><strong>Источник:</strong> ' . $row['source'] . '</div>';
				$flag_source = 1;
			}

			if(!empty($gallery)) {
				$description =  '<p>[gallery_1]</p>' . $description;
			}

			$author_id = 0;
			if(!empty($author)) {
				$author_info = $this->getAuthorByName($author['name']);
				if(!empty($author_info['visitor_id'])) {
					$author_id = $author_info['visitor_id'];
				}else{
					$this->db->query("INSERT INTO " . DB_PREFIX . "visitor SET name = '" . $this->db->escape($author['name']) . "', image = '" . $this->db->escape($author['image']) . "', exp = '" . $this->db->escape($author['exp']) . "', email = '" . $this->db->escape('author@avclub.pro') . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1('QRFvmIEqBS')))) . "', status = '1'");

					$author_id = $this->db->getLastId();
				}
			}

			$show_image = 0;
			if($row['detail_image'] && empty($gallery) && $row['type'] !== 'video') {
				$show_image = 1;
			}

			$this->db->query("INSERT INTO " . DB_PREFIX . "journal SET 
				date_available = '" . $this->db->escape($row['date_available']) . "', 
				image = '" . $this->db->escape($row['image']) . "', 
				image_show = '" . $show_image . "', 
				video = '', 
				master_old = '" . (int)$row['master_old'] . "', 
				link = '', 
				type = '" . $this->db->escape($row['type']) . "', 
				sort_order = '0', 
				author_id = '" . (int)$author_id . "', 
				master_id = '0', 
				status = '1'");

			$journal_id = $this->db->getLastId();

			$this->db->query("INSERT INTO " . DB_PREFIX . "journal_description SET 
				journal_id = '" . (int)$journal_id . "', 
				language_id = '" . (int)$this->config->get('config_language_id') . "', 
				title = '" . $this->db->escape($row['title']) . "', 
				description = '" . $this->db->escape($description) . "', 
				preview = '" . $this->db->escape($row['preview']) . "', 
				meta_title = '', 
				meta_h1 = '', 
				meta_description = '', 
				meta_keyword = ''");

			if(!empty($gallery)) {
				if($row['image'] && $row['detail_image']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "journal_gallery SET gallery_id = '1', journal_id = '" . (int)$journal_id . "', image = '" . $this->db->escape($row['image']) . "', sort_order = '-1'");
				}
				foreach ($gallery as $key => $image) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "journal_gallery SET gallery_id = '1', journal_id = '" . (int)$journal_id . "', image = '" . $this->db->escape($image) . "', sort_order = '" . (int)$key . "'");
				}
			}

			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'journal_id=" . (int)$journal_id . "', keyword = '" . $this->db->escape($row['code']) . "'");


			$this->db->query("UPDATE " . DB_PREFIX . "import SET 
				journal_id = '" . (int)$journal_id. "', 
				flag_import = '1', 
				flag_source = '" . (int)$flag_source. "' 
				WHERE import_id='" . (int)$row['import_id']. "'");
			$data['count']++;
		}


		return $data;
	}

	public function disableArticles() {
		$data = array(
			'total'	=> 0
		);

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "import i WHERE i.journal_id > 0");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "import i WHERE i.journal_id > 0");

		foreach($query->rows as $row) {			

			$this->db->query("UPDATE " . DB_PREFIX . "journal SET 
				status = '0' 
				WHERE journal_id='" . (int)$row['journal_id']. "'");
			
		}


		return $data;
	}

	public function removeArticles() {
		$data = array(
			'total'	=> 0,
			'count'	=> 0,
		);

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "import i WHERE i.journal_id > 0");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "import i WHERE i.journal_id > 0");

		foreach($query->rows as $row) {			

			$this->db->query("DELETE FROM " . DB_PREFIX . "journal WHERE journal_id = '" . (int)$row['journal_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "journal_description WHERE journal_id = '" . (int)$row['journal_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "journal_to_layout WHERE journal_id = '" . (int)$row['journal_id'] . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'journal_id=" . (int)$row['journal_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "journal_copy WHERE journal_id = '" . (int)$row['journal_id'] . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "journal_tag WHERE journal_id = '" . (int)$row['journal_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "journal_case WHERE journal_id = '" . (int)$row['journal_id'] . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "journal_case_attr WHERE journal_id = '" . (int)$row['journal_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "journal_gallery WHERE journal_id = '" . (int)$row['journal_id'] . "'");
			$this->cache->delete('journal');

			$data['count']++;
		}


		return $data;
	}


	public function getAuthorByName($author_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "visitor v WHERE LCASE(v.name) = '" . $this->db->escape(utf8_strtolower($author_name)) . "'");

		return $query->row;
	}



	public function tagAddNew($tag_import = array()) {
		$data = array(
			'total'	=> 0,
			'count'	=> 0,
		);

		$tag_new = array();
		$tag_list = array();

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "tag");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tag t LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach($query->rows as $row) {			
			$tag_list[$row['tag_id']] = mb_strtolower($row['title']);			
		}
		foreach($tag_import as $tag) {
			$tag_id = array_search(mb_strtolower($tag), $tag_list);
			if(!$tag_id) {
				$tag_new[] = $tag;
				$this->db->query("INSERT INTO " . DB_PREFIX . "tag SET status = '1'");
				$tag_id = $this->db->getLastId();

				$this->db->query("INSERT INTO " . DB_PREFIX . "tag_description SET 
					tag_id = '" . (int)$tag_id . "', 
					language_id = '" . (int)$this->config->get('config_language_id') . "', 
					title = '" . $this->db->escape($tag) . "', 
					description = '', 
					meta_title = '', 
					meta_h1 = '', 
					meta_description = '', 
					meta_keyword = ''");

				$data['count']++;
			}
		}

		array_multisort($tag_new, SORT_ASC);

		return $data;
	}

	public function tagImport($tag_import = array()) {
		$data = array(
			'total'	=> 0,
			'count'	=> 0,
		);

		$tag_list = array();

		$data['total'] = count($tag_import);

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tag t LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach($query->rows as $row) {			
			$tag_list[$row['tag_id']] = mb_strtolower($row['title']);			
		}

		foreach($tag_import as $page) {
			$page_query = $this->db->query("SELECT DISTINCT journal_id, import_id FROM " . DB_PREFIX . "import WHERE flag_tag = '0' AND link LIKE '" . $this->db->escape($page['link']) . "' AND journal_id > 0");

			if($page_query->num_rows) {
				$journal_id = $page_query->row['journal_id'];
				$import_id = $page_query->row['import_id'];

				$this->db->query("DELETE FROM " . DB_PREFIX . "journal_tag WHERE journal_id = '" . (int)$journal_id . "'");
				$this->db->query("UPDATE " . DB_PREFIX . "import SET flag_tag = '1' WHERE import_id = '" . (int)$import_id . "'");

				$tags = array_unique($page['tags']);
				foreach($tags as $tag) {

					$tag_id = array_search(mb_strtolower($tag), $tag_list);

					if($tag_id) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "journal_tag SET journal_id = '" . (int)$journal_id . "', tag_id = '" . (int)$tag_id . "'");
					}else {
						echo $page['link'] . ' ---- ' . $tag . '<br>';
					}

				}
				$data['count']++;	


			}

		}
		

		return $data;
	}

	public function getRedirect() {
		$data = array(
			'total'	=> 0,
			'count'	=> 0,
		);

		$query_total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "import i WHERE i.journal_id > 0");
		if($query_total->num_rows) {
			$data['total'] = $query_total->row['total'];
		}

		$query = $this->db->query("SELECT i.journal_id, i.link FROM " . DB_PREFIX . "import i WHERE i.journal_id > 0");

		if($query->num_rows) {
			foreach($query->rows as $row) {
				echo '<br>FROM ' . $row['link'] . ' TO ' . $this->url->link('journal/journal/info', 'journal_id=' . $row['journal_id']) . ' END';
				$data['count']++;	
			}
		}
		

		return $data;
	}

}