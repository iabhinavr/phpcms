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

    public function add_user($data) {
        // check if username or email already exists

        $stmt = $this->con->prepare("SELECT count(*) FROM users WHERE username = :username");
        $stmt->bindParam(":username", $data['username'], PDO::PARAM_STR);
        $stmt->execute();

        $count_username = $stmt->fetchColumn();

        if($count_username > 0) {
            return ["status" => false, "result" => "Username already exists"];
        }

        // check if email already exists

        $stmt = $this->con->prepare("SELECT count(*) FROM users WHERE email = :email");
        $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
        $stmt->execute();

        $count_email = $stmt->fetchColumn();

        if($count_email > 0) {
            return ["status" => false, "result" => "Email already exists"];
        }

        $repassword = $this->verify_password_retype($data['password'], $data['repassword']);

        if($repassword['status'] === false) {
            return ["status" => false, "result" => "Retyped password does not match"];
        }

        switch($data['role']) {
            case 1:
                $role = 'admin';
                break;
            case 2:
                $role = 'editor';
                break;
            default:
                $role = 'editor';
        }

        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            $stmt = $this->con->prepare("INSERT INTO users (first_name, email, username, password, role) values (:first_name, :email, :username, :password, :role)");
            $stmt->bindParam(":first_name", $data['first_name'], PDO::PARAM_STR);
            $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(":username", $data['username'], PDO::PARAM_STR);
            $stmt->bindParam(":password", $password_hash, PDO::PARAM_STR);
            $stmt->bindParam(":role", $role, PDO::PARAM_STR);

            $add = $stmt->execute();

            if($add) {
                return ["status" => true, "result" => "New user added has been added successfully"];
            }
            return ["status" => false, "result" => "Error adding new user"];

        }
        catch(PDOException $e) {
            return ["status" => false, "result" => $e->getMessage()];
        }

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

    public function change_password($data) {

        $id = (int)$data['id'];

        $verify_existing = $this->verify_existing_password($id, $data['existing']);

        if(!($verify_existing["status"])) {
            return $verify_existing;
        }

        $verify_retype = $this->verify_password_retype($data['new'], $data['retype']);

        if(!($verify_retype["status"])) {
            return $verify_retype;
        }

        $verify_password_strength = $this->verify_password_strength($data['new']);

        if(!($verify_password_strength["status"])) {
            return $verify_password_strength;
        }

        $hashed_password = password_hash($data['new'], PASSWORD_DEFAULT);

        try {
            $stmt = $this->con->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id);
            if($stmt->execute()) {
                return ["status" => true, "result" => "password changed successfully"];
            }
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => "some error occurred"];
        }

        return ["status" => false, "result" => "could not change password"];
    }

    private function verify_existing_password($id, $exiting_password) {
        $id = (int)$id;
        try {
            $stmt = $this->con->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            if($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if($result) {
                    if(password_verify($exiting_password, $result['password'])) {
                        return ["status" => true, "result" => "existing password matched"];
                    }
                }
            }
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => "some error occurred"];
        }
        return ["status" => false, "result" => "existing password does not match"];
    }

    private function verify_password_retype($new, $retype) {
        if($new === $retype) {
            return ["status" => true, "result" => "retyped password matched"];
        }
        return ["status" => false, "result" => "retyped password does not match"];
    }

    private function verify_password_strength($new) {
        if(strlen($new) < 8) {
            return ["status" => false, "result" => "password must be at least 8 chars long"];
        }
        return ["status" => true, "result" => "password is strong enough"];
    }

    public function update_user($data) {
        try {
            $stmt = $this->con->prepare("UPDATE users SET first_name = :first_name WHERE id = :id");
            $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT);
            $stmt->bindParam(":first_name", $data['first_name'], PDO::PARAM_STR);

            if($stmt->execute()) {
                return ["status" => true, "result" => "User updated"];
            }
            return ["status" => false, "result" => "Error updating user"];
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => $e->getMessage()];
        }
    }


}