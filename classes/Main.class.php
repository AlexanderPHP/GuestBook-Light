<?php

class Main extends Auth{
	public $config;
	
	public function __construct(){
		parent::__construct();
		$this->config = parse_ini_file('./data/global_config.cfg');
	}

	public function save($who, $msg, $name='', $email=''){
			$ip = $_SERVER['REMOTE_ADDR'];
			$dt = time();
			switch($who){
				case 'a':
						dataBase::getInstance()->query("INSERT INTO msgs(name,email,msg,ip,datetime) VALUES('{$this->data['admin_name']}','{$this->data['admin_email']}',:msg,:ip,:dt)",array(":msg"=>"$msg",":ip"=>"$ip",":dt"=>"$dt"));
							break;
				case 'g': 	
						if(isset($_COOKIE['UNIQID'])) {
							$uniqid = $this->clear($_COOKIE['UNIQID']);
							dataBase::getInstance()->query("INSERT INTO msgs(name,email,msg,uniqid,ip,datetime) VALUES(:name,:email,:msg,:uniqid,:ip,:dt)",array(":name"=>"$name",":email"=>"$email",":msg"=>"$msg",":uniqid"=>"$uniqid",":ip"=>"$ip",":dt"=>"$dt"));
						} else {
							$uniqid = md5(uniqid());
							setcookie("UNIQID",$uniqid,time()+86400);
							dataBase::getInstance()->query("INSERT INTO msgs(name,email,msg,uniqid,ip,datetime) VALUES(:name,:email,:msg,:uniqid,:ip,:dt)",array(":name"=>"$name",":email"=>"$email",":msg"=>"$msg",":uniqid"=>"$uniqid",":ip"=>"$ip",":dt"=>"$dt"));
						}
						break;
			}
	}

	public function getMessage($start, $perpage){
			if(isset($_SESSION['sort'])){
				switch($_SESSION['sort']){
					case 'ascending': $query = dataBase::getInstance()->query("SELECT id, name, email, msg, uniqid, ip, datetime FROM msgs ORDER BY id ASC LIMIT $start, $perpage",false,true);break;
					case 'descending': $query = dataBase::getInstance()->query("SELECT id, name, email, msg, uniqid, ip, datetime FROM msgs ORDER BY id DESC LIMIT $start, $perpage",false,true);break;
					default: $query = dataBase::getInstance()->query("SELECT id, name, email, msg, uniqid, ip, datetime FROM msgs ORDER BY id DESC LIMIT $start, $perpage",false,true);break;
				}
			} else
				$query = dataBase::getInstance()->query("SELECT id, name, email, msg, uniqid, ip, datetime FROM msgs ORDER BY id DESC LIMIT $start, $perpage",false,true);

		return $query->fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	public function deleteMessage($all, $id=''){
			if(!$all)
				dataBase::getInstance()->query("DELETE FROM msgs WHERE id = {$id}");
			else
				dataBase::getInstance()->query("DELETE FROM msgs");
		}
	
	public function is_uniqid($id){
				$result = dataBase::getInstance()->query("SELECT uniqid FROM msgs WHERE id = {$id}",false,true)->fetch(PDO::FETCH_NUM);
				$uniqid = $_COOKIE["UNIQID"];
				 if($result[0] == $uniqid)
					return true;
				else
					return false;
	}
	
	public function PagCount(){
			$sql = "SELECT COUNT(*) AS count FROM msgs";
			$result = dataBase::getInstance()->query($sql,false,true)->fetch(PDO::FETCH_ASSOC);
		return $result['count'];
	}
	
	public function clear($data){
			$data = trim($data);
			$data = htmlspecialchars($data, ENT_QUOTES);
			if (get_magic_quotes_gpc())
				$data = stripslashes($data);
		return $data;
	}
	
	public function updBans(){
		$data = dataBase::getInstance()->query("SELECT data FROM bans",false,true)->fetch(PDO::FETCH_NUM, true);
		$banip = '';
		foreach($data as $ip){
			$banip .= $ip[0] . "\n";
		}
		file_put_contents($this->config['PATH_TO_FILE_BANS'],$banip);
	}
}
?>