<?php
class dataBase{
	static private $_db, $result;
	const DB_NAME = 'data/gbook.db'; // DB PATH
	public function __construct() {
			if(!file_exists(self::DB_NAME)){
				try {
					self::$_db = new PDO("sqlite:".self::DB_NAME);
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
					$sql[] = "INSERT INTO data(admin_name,admin_password,admin_email) VALUES('Admin','pass','localhost@localhost')";
					foreach($sql as $tsql){
						dataBase::query($tsql);
					}
					self::$_db = null;
					exit("База Данных успешно создана!<br>Обновите вашу страничку!");
				} catch (PDOException $err) {
					$trace = '<table border="0">';
					foreach ($err->getTrace() as $a => $b) {
						foreach ($b as $c => $d) {
							if ($c == 'args') {
								foreach ($d as $e => $f) {
									$trace .= '<tr><td><b>' . strval($a) . '#</b></td><td align="right"><u>args:</u></td> <td><u>' . $e . '</u>:</td><td><i>' . $f . '</i></td></tr>';
								}
							} else {
								$trace .= '<tr><td><b>' . strval($a) . '#</b></td><td align="right"><u>' . $c . '</u>:</td><td></td><td><i>' . $d . '</i></td>';
							}
						}
					}
					$trace .= '</table>';
					echo '<br /><br /><br /><font face="Verdana"><div align="center"><fieldset style="width: 66%; border: 4px solid white; background: #35BDF5;"><div align="left"><b>[</b>PHP PDO Error ' . strval($err->getCode()) . '<b>]</b></div> <table border="0"><tr><td align="right"><b><u>Message:</u></b></td><td><i>' . $err->getMessage() . '</i></td></tr><tr><td align="right"><b><u>Code:</u></b></td><td><i>' . strval($err->getCode()) . '</i></td></tr><tr><td align="right"><b><u>File:</u></b></td><td><i>' . $err->getFile() . '</i></td></tr><tr><td align="right"><b><u>Line:</u></b></td><td><i>' . strval($err->getLine()) . '</i></td></tr><tr><td align="right"><b><u>Trace:</u></b></td><td><br />' . $trace . '</td></tr></table></fieldset></div></font>';
					exit();
				}
			} else {
				self::$_db = new PDO("sqlite:".self::DB_NAME);
			}
	}
	
	static public function query($query,$bind=false,$return=false){
			if(!$bind){
				self::$result = self::$_db->prepare($query);
			} elseif(is_array($bind)){
				self::$result = self::$_db->prepare($query);
				foreach($bind as $k=>$v){
					self::$result->bindValue("$k","$v");
				}
			} else
				return false;
			
			self::$result->execute();
			if($return)
				return self::$result;
	}

	public function fetch($fetch_style = PDO::FETCH_BOTH, $all = false){
			if(!empty($this->result)){
				if(!$all)
					return $this->result->fetch($fetch_style);
				else
					return $this->result->fetchAll($fetch_style);
			} else 
				return false;
	}
	
	public function __destruct(){
			dataBase::$_db = null;
	}
}
?>