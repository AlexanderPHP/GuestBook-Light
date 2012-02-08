<?php
class dataBase{
	static public $instance = null;
	private $result, $db;
	
	const DB_NAME = 'data/gbook.db'; // DB PATH
	private function __construct() {
		# если БД существет, просто подключаемся к ней
			if(file_exists(self::DB_NAME)){
				$this->db = new PDO("sqlite:".self::DB_NAME);
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} else {
				# иначе создаем и наполняем нужными данными		
				try {
						$this->db = new PDO("sqlite:".self::DB_NAME);
						$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$sql[] = "CREATE TABLE msgs(
													id INTEGER PRIMARY KEY,
													name TEXT,
													email TEXT,
													msg TEXT,
													uniqid TEXT,
													datetime INTEGER,
													ip TEXT)";
						$sql[] = "CREATE TABLE data(
													admin_name TEXT,
													admin_password TEXT,
													admin_email TEXT)";
						$sql[] = "CREATE TABLE bans(data TEXT)";
						$sql[] = "INSERT INTO data(admin_name,admin_password,admin_email) VALUES('Admin','" . md5('pass') . "','localhost@localhost')";
						$this->db->beginTransaction();
						foreach($sql as $tsql){
							$this->query($tsql);
						}
						$this->db->commit();
						$this->db = null;
						die("База Данных успешно создана!<br>Обновите вашу страничку!");
					} catch (PDOException $err) {
						$this->db->rollBack();
						die('<br /><br /><br /><font face="Verdana"><div align="center"><fieldset style="width: 66%; border: 4px solid white; background: #35BDF5;"><div align="left"><b>[</b>PHP PDO Error ' . strval($err->getCode()) . '<b>]</b></div> <table border="0"><tr><td align="right"><b><u>Message:</u></b></td><td><i>' . $err->getMessage() . '</i></td></tr><tr><td align="right"><b><u>Code:</u></b></td><td><i>' . strval($err->getCode()) . '</i></td></tr><tr><td align="right"><b><u>File:</u></b></td><td><i>' . $err->getFile() . '</i></td></tr><tr><td align="right"><b><u>Line:</u></b></td><td><i>' . strval($err->getLine()) . '</i></td></tr></table></fieldset></div></font>');
					}
			}
	}
	
	# реализация Singleton
	
	public static function getInstance(){
				self::$instance = new dataBase();
		return self::$instance;
	}
	
	# Подготовка и выполнение запросов

	public function query($query,$bind=false,$return=false){
		try{
			if(!$bind){
				$this->result = $this->db->prepare($query);
				$this->result->execute();
			} elseif(is_array($bind)){
				$this->result = $this->db->prepare($query);
				$this->result->execute($bind);
			} else
				return false;			
			if($return)
				return $this->result;
 		} catch (PDOException $err) {
			die('<br /><br /><br /><font face="Verdana"><div align="center"><fieldset style="width: 66%; border: 4px solid white; background: #35BDF5;"><div align="left"><b>[</b>PHP PDO Error ' . strval($err->getCode()) . '<b>]</b></div> <table border="0"><tr><td align="right"><b><u>Message:</u></b></td><td><i>' . $err->getMessage() . '</i></td></tr><tr><td align="right"><b><u>Code:</u></b></td><td><i>' . strval($err->getCode()) . '</i></td></tr><tr><td align="right"><b><u>File:</u></b></td><td><i>' . $err->getFile() . '</i></td></tr><tr><td align="right"><b><u>Line:</u></b></td><td><i>' . strval($err->getLine()) . '</i></td></tr></table></fieldset></div></font>');
		}
	}
	
	# небольшая надстройка над PDO::Fetch()
	
	public function fetch($fetch_style = PDO::FETCH_BOTH, $all = false){
			if(!empty($this->result)){
				if(!$all)
					return $this->result->fetch($fetch_style);
				else
					return $this->result->fetchAll($fetch_style);
			} else 
				return false;
	}

	# разрыв соединения с БД при удалении объекта класса
	
	public function __destruct(){
			$this->db = null;
	}
}
?>