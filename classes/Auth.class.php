<?php

# Класс авторизации
 
class Auth{
	public $auth; # статус (1 - администратор, 0 - соответственно гость)
	protected $data; # данные администратора
	
 	public function __construct(){
			$this->data = $this->SelectLPE();
	}
	
	# Метод авторизации
	
	public function Login($l,$p){
			if($l == $this->data['admin_name'] && md5($p) == $this->data['admin_password']){
				setcookie('GPASS',md5($p), time()+999999);
				$this->auth = 1;
				header('Location: index.php');
			} else {
				$this->auth = 0;
				var_dump(md5($p),$this->data['admin_password']);
			}
	}
	
	# Выхода из аккаунта
	
	public function Logout(){
			setcookie('GPASS','',time()-3600);
			$this->auth = 0;
			header('Location: index.php');
	}

	# Проверка на "залогиненность"
	
	public function is_logged_in(){
			if(isset($_COOKIE['GPASS']) && $_COOKIE['GPASS'] == $this->data['admin_password'])
				$this->auth = 1;
			else
				$this->auth = 0;
	}
	
	# Использование админ. данных
	
	public function ReturnData($value){
			if(isset($this->data["$value"]))
				return $this->data["$value"];
			else
				return false;
	}

	# Запрос на загрузку админ. данных
	
	private function SelectLPE(){		
		return dataBase::getInstance()->query("SELECT admin_name,admin_password,admin_email FROM data",false,true)->fetch(PDO::FETCH_ASSOC);
	}
	
}
?>