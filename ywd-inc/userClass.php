<?php
require "lib/password.php";
class User {
	
	private $con;
	
	public $username;
	
	public $password;
	public $retype_password;
	public $new_password;
	public $current_password;
	public $hash;
	
	public $first_name;
	public $last_name;
	
	public $email;
	
	function __construct($db_con) {
		$this->con = $db_con;
	}
	
	public function register(){
		$this->validate_newuser();
		$this->hash = password_hash($this->password, PASSWORD_BCRYPT);
		
		try 
		{
			$stmt = $this->con->prepare("INSERT INTO users (username, password, first_name, last_name, email) values (:username, :password, :first_name, :last_name, :email)");
			$stmt->bindParam(":username", $this->username, PDO::PARAM_STR, 255);
			$stmt->bindParam(":password", $this->hash, PDO::PARAM_STR, 255);
			$stmt->bindParam(":first_name", $this->first_name, PDO::PARAM_STR, 255);
			$stmt->bindParam(":last_name", $this->last_name, PDO::PARAM_STR, 255);
			$stmt->bindParam(":email", $this->email, PDO::PARAM_STR, 255);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
				
	}
	
	private function validate_newuser() {
		
	}
	
	public function authenticate() {
		$login_auth = false;
		try
		{
			$stmt = $this->con->prepare("SELECT password FROM users WHERE username = :username");
			$stmt->bindParam(":username", $this->username, PDO::PARAM_STR, 255);
			$stmt->execute();
			
			while($result = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				if(password_verify($this->password, $result['password']))
				{
					$login_auth = true;
				}
				else
				{
					$login_auth = false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		return $login_auth;
	}
	
	public function readuser() {
		try {
			$stmt = $this->con->prepare("SELECT * FROM users WHERE username = :username");
			$stmt->bindParam(":username", $this->username, PDO::PARAM_STR, 255);
			$stmt->execute();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->first_name 	= $result['first_name'];
				$this->last_name 	= $result['last_name'];
				$this->email 		= $result['email'];
				$this->hash 		= $result['password'];
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function update_user() {
		if(password_verify($this->current_password, $this->hash))
		{
			if($this->new_password === $this->retype_password)
			{
				$this->hash = password_hash($this->new_password, PASSWORD_BCRYPT);
				$stmt = $this->con->prepare("UPDATE users set password = :password WHERE username = :username");
				$stmt->bindParam(":password", $this->hash, PDO::PARAM_STR, 255);
				$stmt->bindParam(":username", $this->username, PDO::PARAM_STR, 255);
				$user_save = $stmt->execute();
				if($user_save) {
					$pass_status = "Password successfully changed";
					return $pass_status;
				}
					
			}
			else {
				$pass_status = "Passwords don't match";
				return $pass_status;
			}
		}
		else {
			$pass_status = "Current password wrong";
			return $pass_status;
		}
	}
}