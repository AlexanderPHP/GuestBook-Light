<?php
class Auth extends dataBase{
	public $auth;
	protected $data;
	
 	public function __construct(){
			parent::__construct();
			$this->data = $this->SelectLPE();
	}
	
	public function Login($l,$p){
			if($l == $this->data['admin_name'] && $p == $this->data['admin_password']){
				setcookie('GPASS',md5($p), time()+999999);
				$this->auth = 1;
				header('Location: index.php');
			} else {
				$this->auth = 0;
			}
	}
	
	public function Logout(){
			setcookie('GPASS','',time()-3600);
			$this->auth = 0;
			header('Location: index.php');
	}

	public function is_logged_in(){
			if(isset($_COOKIE['GPASS']) && $_COOKIE['GPASS'] == md5($this->data['admin_password']))
				$this->auth = 1;
			else
				$this->auth = 0;
	}
	
	public function ReturnData($value){
			if(isset($this->data["$value"]))
				return $this->data["$value"];
			else
				return false;
	}

	private function SelectLPE(){		
		return dataBase::query("SELECT admin_name,admin_password,admin_email FROM data",false,true)->fetch(PDO::FETCH_ASSOC);
	}
	
}
?>