<?php

class Access {
    private $database;
    private $con;
    public $permissions;

    public $power;

    public function __construct(Database $database) {
        $this->database = $database;
        $this->con = $this->database->db_connect();

        $this->power = [
            'all' => 1,
            'editor' => 2,
            'admin'  => 3,
        ];
    }

    public function get_all_permissions() {
        try {
            $permissions = $this->con->query("SELECT * FROM permissions")->fetch(PDO::FETCH_ASSOC);
            return $permissions;
        }
        catch(PDOException $e) {
            return false;
        }
    }

    public function get_access_list($resource, $action) {
        try {
            $stmt = $this->con->prepare("SELECT access FROM permissions WHERE resource = :resource AND action = :action");
            $stmt->bindParam(":resource", $resource, PDO::PARAM_STR);
            $stmt->bindParam(":action", $action, PDO::PARAM_STR);
            $result = $stmt->execute();

            if($result) {
                $access_string = $stmt->fetchColumn();
                $access_string = str_replace(" ", "", $access_string);
                $access_list = explode(",", $access_string);
                return $access_list;
            }
            return false;
        }
        catch(PDOException $e) {
            return false;
        }
    }

    public function is_authorized($resource_type, $action, $resource_identifier = NULL) {

        $access_list = $this->get_access_list($resource_type, $action);

        if(!$access_list) {
            return false;
        }

        $current_user = $this->get_current_user();

        /**
         * Follows a white listing strategy to allow access
         * ------------------------------------------------
         * 
         * Here are the rules to allow access:
         * 
         * 1. If current user's role is directly in the access list array.
         * 2. If $resource_identifier is present, but role is absent in the access list,
         *    then 'owner' must be present in the access list, plus current user
         *    must be the owner
         * 3. If $resource identifier is absent, then it must be either a 
         *    create request, or a frontend request.
         * 
         *    - Create action don't have owner, so the role must be present
         *      in the access list. It's taken care of in the first condition itself.
         *    - For frontend requests, is_backend() returns false. 
         */

        // If current user's role is in the access list, allow access right away

        if(in_array($current_user['role'], $access_list)) {
            return true;
        }

        /*  $resource identifier can be NUll when 
            1) reading a list, or 
            2) creating a resource
        */
        
        if($resource_identifier) {
            if(in_array('owner', $access_list) && $this->is_owner($resource_type, $resource_identifier)) {
                return true;
            }
        }
        else {
            if(!$this->is_backend()) {
                return true;
            }
        }

        return false;
    }

    public function is_owner($resource_type, $resource_identifier) {

        switch($resource_type) {

            case 'article':
                $resource_owner = $this->get_resource_owner("author", "articles", "id", $resource_identifier);
                break;
            case 'image':
                $resource_owner = $this->get_resource_owner("author", "images", "id", $resource_identifier);
                break;

            case 'admin_user':
                $resource_owner = $this->get_resource_owner("username", "users", "id", $resource_identifier);
                break;
            case 'user':
                $resource_owner = $this->get_resource_owner("username", "users", "id", $resource_identifier);
                break;
            default:
                $resource_owner = false;
                
        }

        $current_user = $this->get_current_user();

        if($current_user['username'] === $resource_owner) {
            return true;
        }
        return false;

    }

    public function get_resource_owner($owner_field, $table_name, $where, $resource_identifier) {

        $resource_owner = false;

        $stmt = $this->con->prepare("SELECT $owner_field FROM $table_name WHERE $where = :resource_identifier");
        $stmt->bindParam(":resource_identifier", $resource_identifier, PDO::PARAM_INT);
        $result = $stmt->execute();
        if($result) {
            $resource_owner = $stmt->fetchColumn();
        }

        return $resource_owner;
    }

    public function get_current_user() {
        $current_user = [
            'username' => !empty($_SESSION['username']) ? $_SESSION['username'] : 'anonymous',
            'role' => !empty($_SESSION['role']) ? $_SESSION['role'] : 'anonymous',
        ];
    
        return $current_user;
    }

    public function is_loggedin() {
        if(!empty($_SESSION['username'])) {
            return true;
        }
        return false;
    }

    public function is_backend() {
        $host = $_SERVER['HTTP_HOST'];
        if(strpos($host, '/admin/') !== false) {
            return true;
        }
        return false;
    }
}