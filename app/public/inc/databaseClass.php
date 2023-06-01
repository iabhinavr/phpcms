<?php

class Database {
    
    private $hostname = "mysql";
    private $db_name = "phpcms";
    private $db_user = "abhinav";
    private $db_password = "password";

    public $con;

    public function db_connect() {
        $this->con = NULL;

        try {
            $this->con = new PDO("mysql:host=" . $this->hostname . ";dbname=" . $this->db_name, $this->db_user, $this->db_password);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $this->con;
    }
}