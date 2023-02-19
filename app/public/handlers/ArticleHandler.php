<?php

class ArticleHandler extends MainHandler {

    private $db_con;

    public $articleObj;
    public $imageObj;
    public $databaseObj;
    public $editorParser;

    public function __construct(
        Article $articleObj, 
        Database $databaseObj,
        Image $imageObj,
        EditorParser $editorParser) {

        $this->databaseObj = $databaseObj;
        $this->db_con = $this->databaseObj->db_connect();

        $this->articleObj = $articleObj;
        $this->imageObj = $imageObj;
        $this->editorParser = $editorParser;

    }

    public function main($method, $vars = null) {

        if(method_exists($this, $method)) {
            $this->$method($vars);
        }
    }

    public function index($vars = null) {

        $props = [
            "page_title" => "Blog"
        ];

        $props['description'] = '';

        $args = [
            'page_no' => 1,
            'per_page' => 5,
        ];

        if($vars) {
           $args['page_no'] = (int)$vars['page_no'];
        }

        $article_count = $this->articleObj->get_article_count();

        $props['total_pages'] = 
            ($article_count % $args['per_page'] === 0) ? 
            floor($article_count / $args['per_page']) : 
            floor($article_count / $args['per_page']) + 1;

        $props['page_no'] = $args['page_no'];
        
        if($args['page_no'] > 1) {
            $props['page_title'] = 'Page ' . $args['page_no'] . ' of ' . $props['total_pages'] . ' | Blog';
        }

        $articles = $this->articleObj->get_articles($args);
        
        $props['articles'] = [];

        foreach ($articles as $a) {
            $id = $a['id'];

            $props['articles'][$id]['title'] = $a['title'];
            $props['articles'][$id]['excerpt'] = $a['excerpt'];

            $publishedObj = DateTime::createFromFormat('Y-m-d H:i:s', $a['published']);
            $published = $publishedObj->format('M j, Y');

            $props['articles'][$id]['published'] = $published;
            $props['articles'][$id]['modified'] = $a['modified'];
            $props['articles'][$id]['slug'] = $a['slug'];
            $props['articles'][$id]['image'] = $a['image'];

            $props['articles'][$id]['featured'] = [
                'file_path' => '/assets/images/default-image.jpg',
            ];

            if($a['image']) {
                $get_image = $this->imageObj->get_image($a['image']);

                if($get_image['status']) {
                    $props['articles'][$id]['featured'] = [
                        'file_path' => '/uploads/thumbnails/' . $get_image['result']['folder_path'] . '/' . $get_image['result']['file_name'],
                    ];
                }
            }

            if(!empty($a['content'])) {
                $json2html = $this->editorParser->json2html($a['content']);
            }
            else {
                $json2html = '';
            }

            
            $props['articles'][$id]['content'] = $json2html;
            
        }
        // var_dump($props);
        $this->render('header', $props);
        $this->render('article-index', $props);
    }

    public function single($vars) {

        $get_article = $this->articleObj->get_article_by_slug($vars['name']);

        if(!$get_article['status']) {
            // show 404
            $this->render('error-404');
            return;
        }

        $article = $get_article['result'];

        $props['page_title'] = $article['title'];
        $props['description'] = $article['excerpt'];
        $props['article'] = $article;

        $modifiedObj = DateTime::createFromFormat('Y-m-d H:i:s', $article['modified']);
        $modified = $modifiedObj->format('M j, Y');
        $props['article']['modified'] = $modified;

        $props['article']['featured'] = [
            'file_path' => '/assets/images/default-image.jpg',
        ];

        if($article['image']) {
            $get_image = $this->imageObj->get_image($article['image']);

            if($get_image['status']) {
                $props['article']['featured'] = [
                    'file_path' => '/uploads/thumbnails/' . $get_image['result']['folder_path'] . '/' . $get_image['result']['file_name'],
                ];
            }
        }

        $props['article']['html'] = $this->editorParser->json2html($article['content']);
        
        $this->render('header', $props);

        // var_dump($props);
        $this->render('article-single', $props);
    }
}