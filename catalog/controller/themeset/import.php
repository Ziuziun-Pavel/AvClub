<?php
class ControllerThemesetImport extends Controller {

	// Видео
	// XML_ID FROM b_iblock_element  
	/* <iframe width="100%" height="360" src="//www.youtube.com/embed/<?=$arResult['XML_ID']?>?feature=player_detailpage" frameborder="0" allowfullscreen></iframe><br>
	*/

	/*
	Превью
	PREVIEW_PICTURE FROM b_iblock_element ---  ( хранится в b_file )
	*/

	/* 
	Кейсы
	1. b_iblock_element --- PREVIEW_PICTURE ( хранится в b_file )
	2. GET * FROM b_iblock_element_property ep LEFT JOIN b_file f ON (ep.IBLOCK_PROPERTY_ID = f.ID) WHERE IBLOCK_ELEMENT_ID = b_iblock_element.ID 
	*/


	/*
	Автор
	$query = GET DISTINCT * as author_id FROM `b_iblock_element_property` WHERE `IBLOCK_ELEMENT_ID` = $article_id AND `IBLOCK_PROPERTY_ID` = 64
	$author_id = $query->row['article_id'];
	$query = GET a.NAME, a.PREVIEW_TEXT, f.SUBDIR, f.FILE_NAME FROM `b_iblock_element` a LEFT JOIN b_file f ON (a.DETAIL_PICTURE = f.ID)
	$author = array(
		'name' => $query->row['NAME'],
		'exp' => $query->row['PREVIEW_TEXT'],
		'image'	=> 'upload/' .  $query->row['SUBDIR'] . '/' .  $query->row['FILE_NAME']
	);
	*/


	
	// Порядок выполнения импорта
	// 1 - searchDouble
	// 2 - importFirst
	// 3 - updateCode
	// 4 - importTextFromOld
	// 5 - updateImages
	// 5 - updateVideoPreview
	// 6 - importArticles

	// Теги
	// 1 - tagCheck

	public function test() {
		
	}

	// поиск новых статей
	public function searchNew() {
		include_once(DIR_APPLICATION . 'controller/themeset/import_data.php');
		include_once(DIR_APPLICATION . 'controller/themeset/import_data_new.php');

		$keys = array();
		$first = array();

		$new = array();

		foreach($articles as $article) {
			$first[$article['link']] = array(
				'link'	=> $article['link'],
				'title'	=> $article['title'],
				'type'	=> $article['type'],
			);
		}

		foreach($articles_new as $article) {
			if(!isset($first[$article['link']])) {
				$new[] = $article;
			}
		}


		foreach($new as $item) {
			if($item['type'] !== 'video_mk') {
				echo $item['type'] . ' -- ' . $item['link'] . '<br>';
			}
		}


	}

	// поиск дубликатов link
	public function searchDouble() {
		include_once(DIR_APPLICATION . 'controller/themeset/import_data_new.php');

		$keys = array();
		$double = array();

		foreach($articles_new as $article) {
			if(isset($keys[$article['link']])) {
				if(!isset($double[$article['link']])) {
					$double[$article['link']][] = $keys[$article['link']];
				}
				$double[$article['link']][] = array(
					'title'	=> $article['title'],
					'link'	=> $article['link'],
				);
			}else{
				$keys[$article['link']] = array(
					'title'	=> $article['title'],
					'link'	=> $article['link'],
				);
			}
		}

		echo '<pre>';
		print_r($double);
		echo '</pre>';


	}

	// первичное наполнение базы импорта
	public function importFirst() {
		include_once(DIR_APPLICATION . 'controller/themeset/import_data.php');
		include_once(DIR_APPLICATION . 'controller/themeset/import_data_new.php');

		$this->load->model('themeset/import');

		$keys = array();
		$first = array();

		$import = array();

		foreach($articles as $article) {
			$first[$article['link']] = array(
				'link'	=> $article['link'],
				'title'	=> $article['title'],
				'type'	=> $article['type'],
			);
		}

		foreach($articles_new as $article) {
			if(!isset($first[$article['link']])) {
				$import[] = $article;
			}
		}

		$count_import = $this->model_themeset_import->importFirst($import);

		echo 'Количество статей: ' . count($articles);
		echo '<br>Импортировано: ' . $count_import;

	}

	// формирвоание code
	public function updateCode() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->updateCode();
		echo '<br>Обновлено: ' . $count;

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

	// обновить показ изображений
	public function updateShowImages() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->updateShowImages();

		echo '<br>Обновлено: ' . $count['count'];
		echo '<br>Всего: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

	// обновить источники
	public function updateLinksFromOld() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->updateLinksFromOld();

		echo '<br>Обновлено: ' . $count['count'];
		echo '<br>Всего необновленных: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

	// импортировать источники
	public function importLinksFromOld() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->importLinksFromOld();

		echo '<br>Обновлено: ' . $count['count'];
		echo '<br>Всего пустых: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

	// достать картинки со старого сайта
	public function importImagesFromOld() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->importImagesFromOld();

		echo '<br>Обновлено: ' . $count['count'];
		echo '<br>Всего пустых: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

	// достать все поля из старой базы и поместить в базу импорта
	public function importTextFromOld() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->importTextFromOld();

		echo '<br>Обновлено: ' . $count['count'];
		echo '<br>Всего пустых: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

	// обновить изображения для видео
	public function updateVideoPreview() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->updateVideoPreview();


		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

	// обновить изображения
	public function updateImages() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->updateImages();

		echo '<br>Обновлено: ' . $count['count'];
		echo '<br>Всего пустых: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

	// импортировать статьи
	public function importArticles() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->importArticles();

		echo '<br>Имопртировано: ' . $count['count'];
		echo '<br>Всего статей: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}




	// отключить импортированные статьи
	public function disableArticles() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->disableArticles();

		echo '<br>Отключено: ' . $count['count'];
		echo '<br>Всего статей: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

	// удалить импортированные статьи
	public function removeArticles() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		$count = $this->model_themeset_import->removeArticles();

		echo '<br>Удалено: ' . $count['count'];
		echo '<br>Всего статей: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}



	// проверка на существование тегов на сайте
	public function tagAddNew() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		include_once(DIR_APPLICATION . 'controller/themeset/import_tags.php');
		
		$tag_list = array();

		foreach($add_tags as $item) {
			if(!empty($item['tags'])) {
				$tags = array_unique($item['tags']);
				foreach($tags as $tag_item) {
					if($tag_item) {
						$key_id = array_search(mb_strtolower($tag_item), $tag_list);
						if(!$key_id) {
							$tag_list[] = $tag_item;
						}
					}
				}
			}
		}

		$tag_list = array_unique($tag_list);

		$count = $this->model_themeset_import->tagAddNew($tag_list);

		echo '<br>Добавлено: ' . $count['count'];
		echo '<br>Было тегов: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}

	// импорт тегов
	public function tagImport() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		include_once(DIR_APPLICATION . 'controller/themeset/import_tags.php');
		
		$count = $this->model_themeset_import->tagImport($add_tags);

		echo '<br>Добавлено: ' . $count['count'];
		echo '<br>Было ссылок: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}


	// список редиректов
	public function getRedirect() {
		$time_start = microtime(true);

		$this->load->model('themeset/import');
		
		$count = $this->model_themeset_import->getRedirect();

		echo '<br>Добавлено: ' . $count['count'];
		echo '<br>Было ссылок: ' . $count['total'];

		$time_end = microtime(true);
		$time_delta = $time_end - $time_start;
		echo '<br><br>' . $time_delta . ' сек.';
	}



}
