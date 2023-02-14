<?php

class ArticleController extends MainController {

    public $article_obj;

    public function __construct() {

        $database = new Database();
        $db_con = $database->db_connect();

        $article_obj = new Article($db_con);
        $this->article_obj = $article_obj;

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