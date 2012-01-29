<?php

class Template {
	
	public
		$dir = '', 						# Дирректория для шаблонов
		$result = array(), 				# Данные компиляции шаблонов		 
		$data = array(), 				# Данные для замены переменных		 
		$data_block = array(),			# Данные для замены блоков 
		$source = array(), 				# Исходные коды шаблонов
		$delete_tags = true, 			# Удалять неидентифицированные переменные из шаблона?		 
		$tag_start_delim = "{", 		# Открывающий тэг переменной {tagname}
		$tag_end_delim = "}", 			# Закрывающий тэг переменной {tagname}
		$block_start_delim_1 = "[", 	# Открывающий тэг открывающего блока [blockname]
		$block_end_delim_1 = "]", 		# Закрывающий тэг открывающего блока [blockname]
		$block_start_delim_2 = "[/", 	# Открывающий тэг закрывающего блока [/blockname]
		$block_end_delim_2 = "]"; 		# Закрывающий тэг закрывающего блока [/blockname] 
	 
	public function __construct($dir) {
		if (is_dir($dir)) {
			$this->dir = $dir;
		} else {
			$this->trigger_error('class dirrectory ' . $dir . ' not found');
		}
	}
	
	 # Загрузка шаблона
	 
	public function open($template, $name) {
		if (is_file($this->dir . '/' . $template)) {
			$this->source["$name"] = file_get_contents($this->dir . '/' . $template);
			
			if (!isset($this->data["$name"])) $this->data["$name"] = array();
			if (!isset($this->data_block["$name"])) $this->data_block["$name"] = array();
		} else {
			$this->trigger_error('open template ' . $template . ' not found');
			$this->source["$name"] = '';
		}
	}
	
	 # Установка переменной. Если переменная не указана, то она вырезается из шаблона при компиляции
	 
	public function set($name = '', $value = '', $template = '') {
		if ($name && $template) {
			$this->data["$template"]["$name"] = $value;
		}
	}
	
	 # Задание блока в указанном шаблоне
	 
	public function set_block($name, $value, $template) {
		$this->data_block["$template"]["$name"] = $value;
	}
	
	 # Очистка значений для указанного шаблона	 
	 
	public function clear($template) {
		$this->data["$template"] = array();
		$this->data_block["$template"] = array();
	}
	
	 # Компиляция шаблона
 
	public function compile($template, $return = true) {
		if (isset($this->source["$template"])) {

			$replace = isset($this->data["$template"]) ? $this->data["$template"] : array();
			$block_replace = isset($this->data_block["$template"]) ? $this->data_block["$template"] : array();
			
			$result = $this->source["$template"];
			
			$result = preg_replace('|' . preg_quote($this->block_start_delim_1) . '(.*?)' . preg_quote($this->block_end_delim_1) . '(.*?)' . preg_quote($this->block_start_delim_2) . '\\1' . preg_quote($this->block_end_delim_2) . '|se', "isset(\$block_replace['\\1']) ? ((\$block_replace['\\1']) ? \$block_replace['\\1'] : '\\2') : ''", $result);
			$result = preg_replace('|' . preg_quote($this->tag_start_delim) . '(.*?)' . preg_quote($this->tag_end_delim) . '|se', $var = "isset(\$replace['\\1']) ? \$replace['\\1'] : (\$this->delete_tags ? '' : '\{\\1\}')",  $result);
			$result = stripslashes($result);
			
			$this->result["$template"] = $result;
			$this->clear($template);
			
			if ($return) {
				return ($result);
			}
		} else {
			$this->trigger_error('compile template ' . $template . ' not open');
			$this->result["$template"] = false;
			
			if ($return) {
				return false;
			}
		}
	}
	
	# Вывод ошибок

	public function trigger_error($error_msg, $error_type = E_USER_ERROR) {
		trigger_error('Template error: ' . $error_msg, $error_type);
	}
}
?>