<?php

class Settings {
    private $database;
    private $con;

    public function __construct(Database $database) {
        $this->database = $database;
        $this->con = $this->database->db_connect();
    }

    public function get_option($key) {
        try {
            $stmt = $this->con->prepare("SELECT option_value FROM settings WHERE option_key = :key");
            $stmt->bindParam(":key", $key, PDO::PARAM_STR);
            if($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if($result) {
                    return ["status" => true, "result" => $result];
                }
            }
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => "database error"];
        }
        return ["status" => false, "result" => "could not get the option"];
    }

    public function add_option($key, $value) {
        try {
            $stmt = $this->con->prepare("INSERT INTO settings (option_key, option_value) values (:key, :value)");
            $stmt->bindParam(":key", $key, PDO::PARAM_STR);
            $stmt->bindParam(":value", $value, PDO::PARAM_STR);
            if($stmt->execute()) {
                
                return ["status" => true, "result" => "option saved"];
                
            }
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => "database error"];
        }
        return ["status" => false, "result" => "could not save the option"];
    }

    public function update_option($key, $value) {
        try {
            $stmt = $this->con->prepare("UPDATE settings SET option_value = :value WHERE option_key = :key");
            $stmt->bindParam(":key", $key, PDO::PARAM_STR);
            $stmt->bindParam(":value", $value, PDO::PARAM_STR);
            if($stmt->execute()) {
                
                return ["status" => true, "result" => "option updated"];
                
            }
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => "database error"];
        }
        return ["status" => false, "result" => "could not update the option"];
    }

    public function delete_option($key) {
        try {
            $stmt = $this->con->prepare("DELETE FROM settings WHERE option_key = :key");
            $stmt->bindParam(":key", $key, PDO::PARAM_STR);
            if($stmt->execute()) {
                
                return ["status" => true, "result" => "option deleted"];
                
            }
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => "database error"];
        }
        return ["status" => false, "result" => "could not delete the option"];
    }
}