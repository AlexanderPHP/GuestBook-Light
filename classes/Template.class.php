<?php

class Template {
	
	public
		$dir = '', 						# ����������� ��� ��������
		$result = array(), 				# ������ ���������� ��������		 
		$data = array(), 				# ������ ��� ������ ����������		 
		$data_block = array(),			# ������ ��� ������ ������ 
		$source = array(), 				# �������� ���� ��������
		$delete_tags = true, 			# ������� �������������������� ���������� �� �������?		 
		$tag_start_delim = "{", 		# ����������� ��� ���������� {tagname}
		$tag_end_delim = "}", 			# ����������� ��� ���������� {tagname}
		$block_start_delim_1 = "[", 	# ����������� ��� ������������ ����� [blockname]
		$block_end_delim_1 = "]", 		# ����������� ��� ������������ ����� [blockname]
		$block_start_delim_2 = "[/", 	# ����������� ��� ������������ ����� [/blockname]
		$block_end_delim_2 = "]"; 		# ����������� ��� ������������ ����� [/blockname] 
	 
	public function __construct($dir) {
		if (is_dir($dir)) {
			$this->dir = $dir;
		} else {
			$this->trigger_error('class dirrectory ' . $dir . ' not found');
		}
	}
	
	 # �������� �������
	 
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
	
	 # ��������� ����������. ���� ���������� �� �������, �� ��� ���������� �� ������� ��� ����������
	 
	public function set($name = '', $value = '', $template = '') {
		if ($name && $template) {
			$this->data["$template"]["$name"] = $value;
		}
	}
	
	 # ������� ����� � ��������� �������
	 
	public function set_block($name, $value, $template) {
		$this->data_block["$template"]["$name"] = $value;
	}
	
	 # ������� �������� ��� ���������� �������	 
	 
	public function clear($template) {
		$this->data["$template"] = array();
		$this->data_block["$template"] = array();
	}
	
	 # ���������� �������
 
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
	
	# ����� ������

	public function trigger_error($error_msg, $error_type = E_USER_ERROR) {
		trigger_error('Template error: ' . $error_msg, $error_type);
	}
}
?>