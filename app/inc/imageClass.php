<?php

class Image {
    private $con;

    public $title;
    public $type;
    public $id;
    public $file_name;
    public $folder_path;
    public $tmp_name;
    public $size;
    public $error;
    
    public $upload_date;
    public $upload_year;
    public $upload_month;
    
    public $upload_status;
    public $validation;


    public function __construct($db_con) {
        $this->con = $db_con;
    }

    public function add_image($data) {

        $this->upload_date = date("Y-m-d");
        $this->upload_year = date("Y");
        $this->upload_month = date("m");

        $this->folder_path = $this->upload_year . "/" . $this->upload_month; 

        $this->file_name = $data['file_name'];
        $this->tmp_name = $data['tmp_name'];
        $this->type =  $data['type'];
        $this->size = $data['size'];
        $this->error = $data['error'];

        $validation = false;
        $regenerated_image = false;
        $thumbnail = false;
        $write_image = false;
        $insert = false;

        $validation = $this->validate_image();

        if(!$validation["status"]) {
            return $validation; 
        }
        else {
            $regenerated_image = $this->regenerate_image(); 
        }
        

        if(!$regenerated_image["status"]) {
            return $regenerated_image;
        }
        else {
            $thumbnail = $this->generate_thumbnail();
        }

        if(!$thumbnail["status"]) {
            return $thumbnail; 
        }
        else {
            $write_image = $this->write_image($regenerated_image["image"], $thumbnail["image"]);
        }

        if(!$write_image["status"]) {
            return $write_image;
        }
        else {
            $insert = $this->insert_image();
        }

        if(!$insert["status"]) {
            return $insert;
        }
        else {
            return ["status" => true, "message" => "Image added successfully", "stmt" => $insert["stmt"], "last_insert" => $this->get_image($insert["insert_id"])];
        }

    }

    private function write_image($regenerated_image, $thumbnail) {

        if( !is_dir("../uploads/fullsize/$this->folder_path") ) {
            mkdir("../uploads/fullsize/$this->folder_path", 0777, true);
        }

        if( !is_dir("../uploads/thumbnails/$this->folder_path") ) {
            mkdir("../uploads/thumbnails/$this->folder_path", 0777, true);
        }

        $regenerated_image->setImageCompressionQuality(80);
        $thumbnail->setImageCompressionQuality(80);

        $upload_fullsize = $regenerated_image->writeImage("../uploads/fullsize/$this->folder_path/" . $this->file_name);

        $upload_thumbnail = $thumbnail->writeImage("../uploads/thumbnails/$this->folder_path/" . $this->file_name);

        if($upload_fullsize && $upload_thumbnail) {
            return ["status" => true, "message" => "Image written to disk successfully"];
        }
        return ["status" => false, "message" => "Error writing image"];
    }

    private function insert_image() {
        try {
            $stmt = $this->con->prepare("INSERT INTO images (file_name, type, upload_date, folder_path) values (:file_name, :type, :upload_date, :folder_path)");
            $stmt->bindParam(':file_name', $this->file_name, PDO::PARAM_STR);
            $stmt->bindParam(':type', $this->type, PDO::PARAM_STR);
            $stmt->bindParam(':upload_date', $this->upload_date, PDO::PARAM_STR);
            $stmt->bindParam(':folder_path', $this->folder_path, PDO::PARAM_STR);

            $insert = $stmt->execute();

            if($insert) {
                return ["status" => true, "message" => "Image successfully inserted", "stmt" => $stmt, "insert_id" => $this->con->lastInsertId()];
            }

            return ["status" => false, "message" => "Error inserting image"];
        }
        catch(PDOException $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    private function validate_image() {
        $valid_extensions = array("jpg", "jpeg", "png");

        $media_parts = explode(".", $this->file_name);
        $media_extension = end($media_parts);

        if( !in_array($media_extension, $valid_extensions)) {
            return ["status" => false, "message" => "Invalid file extension"];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $this->tmp_name);

        if($mime_type === false) {
            return ["status" => false, "message" => "Mime type error"];
        }

        $allowed_types = ["image/jpeg", "image/png", "image/jpg"];

        if(!in_array($mime_type, $allowed_types)) {
            return ["status" => false, "message" => "Mime type not supported"];
        }

        if( $this->size >= 50000000) {
            return ["status" => false, "message" => "File size should be less than 50MB"];
        }

        $this->file_name = str_replace(" ", "-", $this->file_name);

        if( !preg_match("/^[a-zA-Z0-9-_.]+$/", $this->file_name)) {
            return ["status" => false, "message" => "Invalid file name"];
        }

        if( file_exists("../uploads/fullsize/$this->upload_year/$this->upload_month/" . $this->file_name)) {
            return ["status" => false, "message" => "File already exists"];
        }

        return ["status" => true, "message" => "Image validated successfully"];

    }

    private function regenerate_image() {
        $image = new \Imagick();
        $image->readImage($this->tmp_name);
    
        if ($image) {
            $src_width = $image->getImageWidth();
            $src_height = $image->getImageHeight();
    
            $dest_width = $src_width;
            $dest_height = $src_height;
    
            $scale_image = $image->scaleImage($dest_width, $dest_height);

            if($scale_image) {
                return ["status" => true, "message" => "Successfully generated image", "image" => $image];
            }
    
        }
    
        return ["status" => false, "message" => "Error scaling image", "image" => null];
    }
    

    private function generate_thumbnail() {
        $image = new \Imagick();
        $image->readImage($this->tmp_name);
    
        if ($image) {
            $src_width = $image->getImageWidth();
            $src_height = $image->getImageHeight();
    
            $dest_width = 640;
            $dest_height = floor($dest_width * ($src_height / $src_width));
    
            $scale_image = $image->scaleImage($dest_width, $dest_height, true);
    
            if($scale_image) {
                return ["status" => true, "message" => "Successfully generated image", "image" => $image];
            }
        }
    
        return ["status" => false, "message" => "Error scaling thumbnail", "image" => null];
    }
    

    public function get_image($id) {

        $id = (int)$id;

        try {
            $stmt = $this->con->prepare("SELECT * FROM images WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!empty($result)) {
                return ["status" => true, "result" => $result];
            }
            else {
                return ["status" => false, "result" => "Error fetching image"];
            }
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => $e->getMessage()];
        }
    }

    public function get_images($args = []) {

        if(empty($args)) {
           $args = [
            "per_page" => 25,
            "page_no" => 1,
           ];
        }

        $limit = $args['per_page'];
        $offset = ($args['page_no'] - 1) * $limit;

        try {
            $stmt = $this->con->prepare("SELECT * FROM images ORDER BY id DESC LIMIT :limit OFFSET :offset");
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($result)) {
                return ["status" => true, "result" => $result];
            }
            else {
                return ["status" => false, "result" => "Error fetching data"];
            }
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => $e->getMessage()];
        }
    }

    public function get_image_count() {
        $count = $this->con->query("SELECT count(*) from images")->fetchColumn();
        return $count;
    }

    public function update_image($data) {

        $id = (int)$data['id'];
        $title = $data['title'];

        try {
            $stmt = $this->con->prepare("UPDATE images set title = :title WHERE id = :id");
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            $update = $stmt->execute();

            if($update) {
                return ["status" => true, "result" => "Image details saved successfully"];
            }
            return ["status" => false, "result" => "Error saving image details"];
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => "Some error occurred"];
        }

    }

    public function delete_image($id) {
        $id = (int)$id;

        // first, select the image from db to check if the entry exists

        $get_image = $this->get_image($id);

        if(!$get_image["status"]) {
            return ["status" => false, "result" => "Cannot delete! Image does not exist"];
        }

        $image = $get_image["result"];

        $image_path = $image['folder_path'] . "/" . $image['file_name'];

        // now delete the image

        unlink("../uploads/fullsize/$image_path");
        unlink("../uploads/thumbnails/$image_path");

        try {
            $stmt = $this->con->prepare("DELETE from images WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $delete = $stmt->execute();

            if($delete) {
                return ["status" => true, "result" => "Image deleted successfully"];
            }
            return ["status" => false, "result" => "Error deleting image"];
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => "Some error occurred"];
        }
    }


}