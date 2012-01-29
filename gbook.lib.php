<?php
@ini_set('error_reporting', E_ALL);
if(!defined('GBOOK')) die('Die, Die, Die');

function autoload($class) {
	include_once(__DIR__ . '/classes/' . $class . '.class.php');
}
spl_autoload_register("autoload");

$gbook = new Main();
$gbook->is_logged_in(); // залогинен?

	function setSortMessage($sort){
			if($sort == 'descending')
				$_SESSION['sort'] = 'descending';
			elseif($sort == 'ascending')
				$_SESSION['sort'] = 'ascending';
			else
				$_SESSION['sort'] = 'descending';
			header('Location: index.php');
	}
	
 	function SortSelected(){
			if(isset($_SESSION['sort'])){
				if($_SESSION['sort'] == 'descending')
					$selected = '<option value="descending" selected="selected">По убываню</option>
								 <option value="ascending">По возрастанию</option>';
				elseif($_SESSION['sort'] == 'ascending')
					$selected = '<option value="descending">По убываню</option>
								 <option value="ascending"  selected="selected">По возрастанию</option>';
			} else
					$selected = '<option value="descending">По убываню</option>
								 <option value="ascending">По возрастанию</option>';
		return $selected;
	}
	
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