<?php
class Main extends Auth{
	public $config, $Db;
	
	public function __construct(){
		parent::__construct();
		$this->config = parse_ini_file('./data/global_config.cfg');
		$this->Db = dataBase::getInstance();
	}

	public function save($who, $msg, $name='', $email=''){
			$ip = $_SERVER['REMOTE_ADDR'];
			$dt = time();
			switch($who){
				case 'a':
						$this->Db->query("INSERT INTO msgs(name,email,msg,ip,datetime) VALUES('{$this->data['admin_name']}','{$this->data['admin_email']}',:msg,:ip,:dt)",array(":msg"=>"$msg",":ip"=>"$ip",":dt"=>"$dt"));
							break;
				case 'g': 	
						if(isset($_COOKIE['UNIQID'])) {
							$uniqid = $this->clear($_COOKIE['UNIQID']);
							$this->Db->query("INSERT INTO msgs(name,email,msg,uniqid,ip,datetime) VALUES(:name,:email,:msg,:uniqid,:ip,:dt)",array(":name"=>"$name",":email"=>"$email",":msg"=>"$msg",":uniqid"=>"$uniqid",":ip"=>"$ip",":dt"=>"$dt"));
						} else {
							$uniqid = md5(uniqid());
							setcookie("UNIQID",$uniqid,time()+86400);
							$this->Db->query("INSERT INTO msgs(name,email,msg,uniqid,ip,datetime) VALUES(:name,:email,:msg,:uniqid,:ip,:dt)",array(":name"=>"$name",":email"=>"$email",":msg"=>"$msg",":uniqid"=>"$uniqid",":ip"=>"$ip",":dt"=>"$dt"));
						}
						break;
			}
	}

	public function getMessage($start, $perpage){
			if(isset($_SESSION['sort'])){
				switch($_SESSION['sort']){
					case 'ascending': $sql = "SELECT id, name, email, msg, uniqid, ip, datetime FROM msgs ORDER BY id ASC LIMIT {$start}, {$perpage}";break;
					case 'descending': $sql = "SELECT id, name, email, msg, uniqid, ip, datetime FROM msgs ORDER BY id DESC LIMIT {$start}, {$perpage}";break;
					default: $sql = "SELECT id, name, email, msg, uniqid, ip, datetime FROM msgs ORDER BY id DESC LIMIT {$start}, {$perpage}";break;
				}
			} else
				$sql = "SELECT id, name, email, msg, uniqid, ip, datetime FROM msgs ORDER BY id DESC LIMIT {$start}, {$perpage}";

		return $this->Db->query($sql,false,true)->fetchAll(PDO::FETCH_ASSOC);;
		
	}
	
	public function deleteMessage($all, $id=''){
			if(!$all)
				$this->Db->query('DELETE FROM msgs WHERE id = :id',array(':id'=>$id));
			else
				$this->Db->query('DELETE FROM msgs');
		}
	
	public function is_uniqid($id){
				$result = $this->Db->query('SELECT uniqid FROM msgs WHERE id = :id',array(':id'=>$id),true)->fetch(PDO::FETCH_NUM);
				$uniqid = $_COOKIE['UNIQID'];
				 if($result[0] == $uniqid)
					return true;
				else
					return false;
	}
	
	public function PagCount(){
			$result = $this->Db->query('SELECT COUNT(*) AS count FROM msgs',false,true)->fetch(PDO::FETCH_ASSOC);
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
		$data = $this->Db->query('SELECT data FROM bans',false,true)->fetchAll(PDO::FETCH_NUM);
		$banip = '';
		if($data !== false){
			foreach($data as $ip){		
				$banip .= $ip[0]."\n";
			}
			file_put_contents($this->config['PATH_TO_FILE_BANS'],$banip);
		}
		
	}
	
	public function addIP($ip){
		$data = $ip;
		if(filter_var($ip, FILTER_VALIDATE_IP))
			$dt = $this->Db->query('INSERT INTO bans(data) VALUES(:data)',array(':data'=>$data));
		else
			return false;
	}
}
?>