<?php

class Database {
	
	private $hostname 		= "localhost";
	private $db_name 		= "phpcms";
	private $db_user 		= "root";
	private $db_password 	= "";
	
	public $con;
	
	public function db_connect() {
		
		$this->con = NULL;
		
		try {
			$this->con = new PDO("mysql:host=" . $this->hostname . ";dbname=" . $this->db_name, $this->db_user, $this->db_password);
			$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e) {
			
			echo $e->getMessage();
			
		}
		
		
		return $this->con;
	}
}