<?php

include __DIR__ . '/MainController.php';

class ArticleController extends MainController {

    private $db_con;

    public $article_obj;
    public $database;

    public function __construct(Article $article, Database $database) {

        $this->database = $database;
        $this->db_con = $this->database->db_connect();

        $this->article_obj = $article;

    }

    public function main($method, $vars = null) {

        if(method_exists($this, $method)) {
            $this->$method();
        }
    }

    public function index($vars = null) {

        $props = [];

        if(!$vars) {
            $args = [];
            $args['page_no'] = 1;
            $args['per_page'] = 5;
        };

        $articles = $this->article_obj->get_articles($args);
        
        $this->render('article-index', $props);
    }

    public function single() {

    }
}