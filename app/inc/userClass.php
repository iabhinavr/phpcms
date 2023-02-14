<?php

class User {
    private $database;
    private $con;

    public $id;
    public $username;
    public $password;
    public $first_name;
    public $email;
    public $role;

    public function __construct(Database $database) {
        $this->database = $database;
        $this->con = $this->database->db_connect();
    }

    public function get_user_by_id($id) {
        try {
            $stmt = $this->con->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!empty($result)) {
                return ["status" => true, "result" => $result];
            }

            return ["status" => false, "result" => "Error fetching article"];
        }
        catch (PDOException $e) {
            return ["status" => false, "result" => $e->getMessage()];
        }
    }

    public function get_user_by_username($username) {
        try {
            $stmt = $this->con->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!empty($result)) {
                return ["status" => true, "result" => $result];
            }

            return ["status" => false, "result" => "Error fetching article"];
        }
        catch (PDOException $e) {
            return ["status" => false, "result" => $e->getMessage()];
        }
    }

    public function get_users($args = []) {

        if(empty($args)) {
            $args = [
             "per_page" => 25,
             "page_no" => 1,
            ];
         }
 
         $limit = $args['per_page'];
         $offset = ($args['page_no'] - 1) * $limit;

        try {
            $stmt = $this->con->prepare("SELECT * FROM users ORDER BY id DESC LIMIT :limit OFFSET :offset");
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function get_user_count() {
        $count = $this->con->query("SELECT count(*) FROM users")->fetchColumn();
        return $count;
    }

    public function authenticate($username, $password) {
        $authenticated = false;

        try {
            $stmt = $this->con->prepare("SELECT password FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result) {
                if(password_verify($password, $result['password'])) {
                    $authenticated = true;
                }
            }
        }
        catch(PDOException $e) {
            return false;
        }

        return $authenticated;
    }

    public function update_user($id) {

    }



}