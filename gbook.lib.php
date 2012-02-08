<?php
@ini_set('error_reporting', E_ALL);
if(!defined('GBOOK')) die('Die, Die, Die');

function autoload($class) {
	include_once(__DIR__ . '/classes/' . $class . '.class.php');
}
spl_autoload_register("autoload");

$gbook = new Main();
$gbook->is_logged_in(); // залогинен?

	# Установка сортировки сообшений

	function setSortMessage($sort){
			switch($sort){
				case 'descending': $_SESSION['sort'] = 'descending';break;
				case 'ascending': $_SESSION['sort'] = 'ascending';break;
				default: $_SESSION['sort'] = 'descending';break;
			}
		header('Location: index.php');
	}
	
	# Вывод меню сортировки, в зависимости от выбранного метода сортировки сообщений
	
 	function SortSelected(){
			if(isset($_SESSION['sort'])){
				switch($_SESSION['sort']){
					case 'descending': $options = '<option value="descending" selected="selected">По убываню</option><option value="ascending">По возрастанию</option>';break;					
					case 'ascending': $options = '<option value="descending">По убываню</option><option value="ascending"  selected="selected">По возрастанию</option>';break;					
				}
				return $options;
			} else
				return '<option value="descending">По убываню</option><option value="ascending">По возрастанию</option>';
	}
	
	# Замена смайлов и bad-слов на соответствующие им значения в файле data.dt
	
	function Replacer($text){
		global $gbook;
 			$data = file('data/data.dt',FILE_IGNORE_NEW_LINES);
			$separator = '--separator--';
			$delimiter = array_search($separator,$data);
			if(!$delimiter){
				exit('Ошибка чтения data.dt. Укажите разделитель '. $separator);
			} else {
				$hotwords = array_slice($data,0,$delimiter);
				$smiles = array_slice($data,$delimiter+1);
				foreach($smiles as $value){
					list($sm[],$img[]) = explode('|',$value);
				}
			}
			$replace = str_replace($sm,$img,$text);
			$replace = str_replace($hotwords,$gbook->config['CENSORESHIP'],$replace);
		return $replace;
	}
	
	# Замена BB-кодов на соответствующие им html-теги
	
 	function bbcode($text) {
			$str_search = array(
				'/\[b\](.+?)\[\/b\]/is',
				'/\[i\](.+?)\[\/i\]/is',
				'/\[s\](.+?)\[\/s\]/is',
				'/\[u\](.+?)\[\/u\]/is',
		//		'/\[url=(.+?)\](.+?)\[\/url\]/is',
		//		'/\[url\](.+?)\[\/url\]/is',
		//		'/\[img\](.+?)\[\/img\]/is',
		//		'/\[size=(.+?)\](.+?)\[\/size\]/is',
				'/\[color=(.+?)\](.+?)\[\/color\]/is');
			$str_replace = array(
				'<b>\\1</b>',
				'<span style="font-style:italic">\\1</span>',
				'<span style="text-decoration:line-through">\\1</span>',
				'<span style="text-decoration:underline">\\1</span>',
		//		'<a href="\\1">\\2</a>',
		//		'<a href="\\1">\\1</a>',
		//		'<img src="\\1" />',
		//		'<span style="font-size:\\1pt">\\2</span>',
				'<span style="color:\\">\\2</span>');
			return preg_replace($str_search, $str_replace, $text);
	}

?>