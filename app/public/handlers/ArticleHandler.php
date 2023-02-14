<?php

class ArticleHandler extends MainHandler {

    private $db_con;

    public $article_obj;
    public $database;
    public $editorParser;

    public function __construct(
        Article $article, 
        Database $database,
        EditorParser $editorParser) {

        $this->database = $database;
        $this->db_con = $this->database->db_connect();

        $this->article_obj = $article;
        $this->editorParser = $editorParser;

    }

    public function main($method, $vars = null) {

        if(method_exists($this, $method)) {
            $this->$method();
        }
    }

    public function index($vars = null) {

        $props = [
            "page_title" => "Read Articles"
        ];

        $this->render('header', $props);

        if(!$vars) {
            $args = [];
            $args['page_no'] = 1;
            $args['per_page'] = 5;
        };

        $articles = $this->article_obj->get_articles($args);
        
        $props['articles'] = [];

        foreach ($articles as $a) {
            $id = $a['id'];
            $props['articles'][$id]['title'] = $a['title'];

            if(!empty($a['content'])) {
                $json2html = $this->editorParser->json2html($a['content']);
            }
            else {
                $json2html = '';
            }

            
            $props['articles'][$id]['content'] = $json2html;
            
        }
        // var_dump($props);
        $this->render('article-index', $props);
    }

    public function single() {

    }
}