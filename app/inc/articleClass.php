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

            if(!empty($result)) {
                return ["status" => true, "result" => $result];
            }

            return ["status" => false, "result" => "Error fetching article"];
        }
        catch (PDOException $e) {
            return ["status" => false, "result" => $e->getMessage()];
        }
    }

    public function get_articles($args = []) {

        if(empty($args)) {
            $args = [
             "per_page" => 25,
             "page_no" => 1,
            ];
         }
 
         $limit = $args['per_page'];
         $offset = ($args['page_no'] - 1) * $limit;

        try {
            $stmt = $this->con->prepare("SELECT * FROM articles ORDER BY id DESC LIMIT :limit OFFSET :offset");
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

    public function get_article_count() {
        $count = $this->con->query("SELECT count(*) FROM articles")->fetchColumn();
        return $count;
    }

    public function update_article($data) {

        $slug = $this->process_slug($data);
        try {
            $stmt = $this->con->prepare("UPDATE articles SET title = :title, content = :content, published = :published, modified = :modified, image = :image, slug = :slug, excerpt = :excerpt WHERE id = :id");
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':content', $data['content'], PDO::PARAM_STR);
            $stmt->bindParam(':published', $data['published'], PDO::PARAM_STR);
            $stmt->bindParam(':modified', $data['modified'], PDO::PARAM_STR);
            $stmt->bindParam(':image', $data['image'], PDO::PARAM_INT);
            $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
            $stmt->bindParam(':excerpt', $data['excerpt'], PDO::PARAM_STR);

            $update = $stmt->execute();

            if($update) {
                return ["status" => true, "result" => "Article updated successfully"];
            }
            else {
                return ["status" => false, "result" => "error saving data"];
            }
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => $e->getMessage()];
        }
    }

    public function add_article($data) {
        $slug = $this->process_slug($data);

        try {
            $stmt = $this->con->prepare("INSERT INTO articles (title, content, published, modified, image, slug, excerpt) values (:title, :content, :published, :modified, :image, :slug, :excerpt)");
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':published', $data['published'], PDO::PARAM_STR);
            $stmt->bindParam(':modified', $data['modified'], PDO::PARAM_STR);
            $stmt->bindParam(':image', $data['image']);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':excerpt', $data['excerpt']);

            $insert = $stmt->execute();

            if($insert) {
                return ["status" => true, "result" => "Article successfully added", "stmt" => $stmt, "insert_id" => $this->con->lastInsertId()];
            }

            return ["status" => false, "result" => "Error adding article"];
        }
        catch(PDOException $e) {
            return ["status" => false, "result" => $e->getMessage()];
        }
    }

    public function delete_article() {

    }

    private function process_slug($data) {

        $unique_id = uniqid();
        $slug = $data['slug'];

        if(!empty($slug)) {
            try {
                $stmt = $this->con->prepare("SELECT count(*) FROM articles WHERE slug = :slug AND id != :id");
                $stmt->bindParam(":slug", $slug, PDO::PARAM_STR);
                $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT);
                $result = $stmt->execute();
                $row_count = $stmt->fetchColumn();

                if($row_count === 0) {
                    return $slug;
                }
                else {
                    return $slug . '-' . $unique_id;
                }
                

            }
            catch(PDOException $e) {
                return $e->getMessage();
            }
        }
        else {
            return $unique_id;
        }
    }
}