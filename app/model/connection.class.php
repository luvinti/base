<?php

abstract class Connection {

	private static $instance;

	protected $user; 
	protected $password; 
	protected $host; 
	protected $database; 
	protected $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8",);

	public function __construct($user, $password, $host, $database) {
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->database = $database;
    }

	private function connect(){
		$conn = new PDO("mysql:host=$this->host;
			                   dbname=$this->database;charset=utf8", 
			                   $this->user, 
			                   $this->password, 
			                   $this->options
        );
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
		return $conn;
	}


	public function runQuery($sql){
		$stm = $this->connect()->prepare($sql);
		$stm->execute();
		return $stm;
	}

	public function runSelect($sql){
		$stm = $this->connect()->prepare($sql);
		$stm->execute();
		return $stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public function runRow($sql){
		$stm = $this->connect()->prepare($sql);
		$stm->execute();
		return $stm->rowCount();
	}

	public function runQueryFetch($sql){
		$stm = $this->connect()->prepare($sql);
		$stm->execute();
		return $stm->fetch(PDO::FETCH_ASSOC);
	}

	public function getConnection() {
		return $this->connect();
	}

	public function insert($insert, $values){
	  $sql = "INSERT INTO vw_ultimas_datas (".implode(", ", $insert).") VALUES ('".implode("', '", $values)."')";
	  $stm = $this->connect()->prepare($sql);
	  $stm->execute();
	  return $stm;
	}
      
}