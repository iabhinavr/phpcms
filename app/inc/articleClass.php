<?php

class Article {
    private $con;

    public $id;
    public $title;
    public $content;
    public $published;
    public $modified;
    public $image;

    function __construct ($db_con) {
        $this->con = $db_con;
    }

    public function get_article($id) {
        try {
            $stmt = $this->con->prepare("SELECT * FROM articles WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function get_articles($args = []) {

        if(empty($args)) {
            $args = [
                "limit" => 10,
                "offset" => 0
            ];
        }
        try {
            $stmt = $this->con->prepare("SELECT * FROM articles LIMIT :limit OFFSET :offset");
            $stmt->bindParam(":limit", $args["limit"], PDO::PARAM_INT);
            $stmt->bindParam(":offset", $args["offset"], PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_article($data) {
        try {
            $stmt = $this->con->prepare("UPDATE articles SET title = :title, content = :content, published = :published, modified = :modified, image = :image WHERE id = :id");
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':content', $data['content'], PDO::PARAM_STR);
            $stmt->bindParam(':published', $data['published'], PDO::PARAM_STR);
            $stmt->bindParam(':modified', $data['modified'], PDO::PARAM_STR);
            $stmt->bindParam(':image', $data['image'], PDO::PARAM_INT);

            $update = $stmt->execute();

            if($update) {
                return ["status" => true, "message" => "updated successfully"];
            }
            else {
                return ["status" => false, "message" => "error saving data"];
            }
        }
        catch(PDOException $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    public function add_article() {

    }

    public function delete_article() {

    }
}