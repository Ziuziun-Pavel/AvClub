<?php
class ModelThemesetUrl extends Model {

	public function getUrl($string, $prefix = '') {

		$unique_url = '';

		$string = html_entity_decode($string);

		$string = $this->cyrillicToLatinaFromRus($string);

		$string = mb_strtolower($this->clearWasteChars($string), 'UTF-8');

		$valid = false;

		$i = 0;

		while(false === $valid){
			
			$unique_url = $prefix ? $prefix . '-' . $string : $string;
			if($i > 0) $unique_url .= "-$i";

			$sql = "SELECT `url_alias_id` FROM `" . DB_PREFIX . "url_alias` WHERE `keyword`='" .  $this->db->escape($unique_url) . "'";

			$check_url = $this->db->query($sql);
			if ( 0 == $check_url->num_rows ) {
				$valid = true;
			}
			
			$i++;
		}

		return $unique_url;
	}


	private function cyrillicToLatinaFromRus($string, $gost = false) {
		// https://habrahabr.ru/post/187778/
		
		if($gost) {
			$replace = array(
				"А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
				"Е"=>"E","е"=>"e","Ё"=>"E","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z",
				"И"=>"I","и"=>"i",
				"Й"=>"I","й"=>"i","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n",
				"О"=>"O","о"=>"o", "П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t",
				"У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f","Х"=>"H","х"=>"h","Ц"=>"Tc","ц"=>"tc","Ч"=>"Ch","ч"=>"ch",
				"Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch","Ы"=>"Y","ы"=>"y",
				"Э"=>"E","э"=>"e","Ю"=>"Iu","ю"=>"iu","Я"=>"Ia","я"=>"ia","ъ"=>"","ь"=>"",
				"«"=>"", "»"=>"", "„"=>"", "“"=>"", "“"=>"", "”"=>"", "\•"=>"",
				
			);
		} else {
			$arStrES = array("ае","уе","ое","ые","ие","эе","яе","юе","ёе","ее","ье","ъе","ый","ий");
			$arStrOS = array("аё","уё","оё","ыё","иё","эё","яё","юё","ёё","её","ьё","ъё","ый","ий");
			$arStrRS = array("а$","у$","о$","ы$","и$","э$","я$","ю$","ё$","е$","ь$","ъ$","@","@");

			$replace = array(
				"А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
				"Е"=>"Ye","е"=>"e","Ё"=>"Ye","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z",
				"И"=>"I","и"=>"i",
				"Й"=>"Y","й"=>"y","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n",
				"О"=>"O","о"=>"o","П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t",
				"У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f","Х"=>"H","х"=>"h","Ц"=>"Ts","ц"=>"ts","Ч"=>"Ch","ч"=>"ch",
				"Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch","Ъ"=>"","ъ"=>"","Ы"=>"Y","ы"=>"y","Ь"=>"","ь"=>"",
				"Э"=>"E","э"=>"e","Ю"=>"Yu","ю"=>"yu","Я"=>"Ya","я"=>"ya","@"=>"y","$"=>"ye",
				"«"=>"", "»"=>"", "„"=>"", "“"=>"", "“"=>"", "”"=>"", "\•"=>"",

			);

			$string = str_replace($arStrES, $arStrRS, $string);
			$string = str_replace($arStrOS, $arStrRS, $string);

		}

		return iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
	}

	private function clearWasteChars($str){
		$str = trim($str);
		$str = preg_replace('|_|','-',$str);
    $str = preg_replace('![^\w\d\s\-]*!u','',$str); // u - чтобы в том числе № и другие не ASCII-символы
    $str = preg_replace('/\s+/', '-', $str); // Убрать двойные пробелы
    $str = preg_replace('| |','-',$str); // Заменить одинарные пробелы на тире
    $str = preg_replace('|-+|','-',$str); // Заменить поторяющиеся тире на единичное
    $str = preg_replace( array('!^-!', '!-$!'),array('', ''), $str); // Убрать тире в начале и в конце строки

    return $str;
  }


}