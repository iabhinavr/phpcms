<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../inc/databaseClass.php';
require_once '../inc/articleClass.php';
require_once '../inc/imageClass.php';
require_once '../inc/settingsClass.php';
require_once '../inc/accessClass.php';

$database = new Database();

$access_obj = new Access($database);
$article_obj = new Article($database);
$settings_obj = new Settings($database);
$image_obj = new Image($database, $settings_obj);

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $get_article = $article_obj->get_article($id);

    if($get_article["status"]) {
        $article = $get_article["result"];
        $featured_image_id = $article["image"];
        $featured_image = false;

        if($featured_image_id) {
            $get_featured_image = $image_obj->get_image($featured_image_id);

            if($get_featured_image["status"]) {
                $featured_image = $get_featured_image["result"];
            }
        }
    }
    else {
        echo json_encode($get_article);
        exit();
    }
}

if(isset($_POST['article-edit-submit'])) {

    $validate_csrf_token = validate_csrf_token($_POST['csrf-token']);

    if(!$validate_csrf_token['status']) {
        echo json_encode($validate_csrf_token);
        exit();
    }

    $authorization = $access_obj->is_authorized('article', 'update', (int)$_POST['id']);

    if(!$authorization) {
        echo json_encode(["msg" => "No access"]);
        exit();
    }

    $datetime = date('Y-m-d H:i:s');

    $data = [
        'id' => (int)$_POST['id'],
        'title' => $_POST['title'],
        'published' => $_POST['published'],
        'modified' => $datetime,
        'content' => base64_decode($_POST['content']?: ''),
        'image' => empty($_POST['image']) ? NULL : (int)$_POST['image'],
        'slug' => $_POST['slug'],
        'excerpt' => $_POST['excerpt'],
    ];

    $update = $article_obj->update_article($data);

    if(isset($update['status'])) {
        echo json_encode($update);
    }
    exit();
}

if(isset($_POST['delete-article'])) {
    $authorization = $access_obj->is_authorized('article', 'delete', (int)$_POST['id']);

    if(!$authorization) {
        echo json_encode(["msg" => "No access"]);
        exit();
    }

    $id = (int)$_POST['id'];

    $delete = $article_obj->delete_article($id);

    echo json_encode($delete);
    exit();
}

get_template('header');
get_template('topbar');

// Access Control

$authorization = $access_obj->is_authorized('article', 'update', (int)$_GET['id']);

?>

<div class="container-fluid text-left">
    <div class="row position-relative">
    
        <?php get_template('sidebar'); ?>
    
        <?php if(empty($authorization)) : ?>
            <div class="col editor-middle">
                <div class="alert alert-danger">You don't have enough permissions to access this resource</div>
            </div>
        <?php else : ?>
        
    
        <div class="flex-grow-1 col-md-auto position-relative px-2 editor-middle vh-100 overflow-y-scroll bg-light-subtle">   
    
            <form action="" id="edit-article-form">
                <input type="text" name="article-title" value="<?= $article['title'] ?>" class="mt-3 mb-3 form-control form-control-lg bg-transparent">
                <input type="hidden" name="published-date" value="<?= $article['published'] ?>">
                <input type="hidden" name="modified-date" value="<?= $article['modified'] ?>">
                <input type="hidden" name="article-image" value="<?= $article['image'] ?>">
                <input type="hidden" name="article-id" value="<?= $article['id'] ?>">
                <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div id="editorjs" class="px-4 my-2 py-3"><?php echo !empty($article['content']) ? base64_encode($article['content']) : base64_encode('{}') ?></div>
            </form>
    
        </div>
    
        <div class="col-md-2 editor-sidebar sidebar min-vh-100 p-2 position-relative bg-body-tertiary"  style="top: 47px">
            <button class="px-2 py-2 mb-2 w-100 btn btn-success" id="save-article-button">
                Save Article
            </button>
            <h3 class="fs-5 border-b py-2 mb-2">Featured Image</h3>
            <div class="featured-image-selector border mb-2" data-bs-toggle="modal" data-bs-target="#image-library-modal-fullscreen">
                <span class="" id="select-featured-image" style="background-image: <?php echo !empty($featured_image) ? 'url(../uploads/thumbnails/' . esc_html($featured_image['folder_path']) . '/' . esc_html($featured_image['file_name']) . ')' : 'none' ?>"></span>
            </div>
            <h3 class="fs-5 border-b py-2 mb-2">Slug</h3>
            <input type="text" name="article-slug" id="article-slug" class="form-control" value="<?= esc_html($article['slug']) ?>">
            <h3 class="fs-5 border-b py-2 mb-2">Excerpt</h3>
            <textarea name="article-excerpt" id="article-excerpt" class="form-control" rows="5"><?= esc_html($article['excerpt']) ?></textarea>
            <button class="btn btn-danger w-100 mt-2" id="delete-article-button" data-article-id="<?= $article['id'] ?>">
                Delete Article
            </button>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="modal fade-show" id="image-library-modal-fullscreen" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-fullscreen" id="image-library">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5">Select an Image from the Library</h2>
                </div>
                <div class="modal-body">
                    <ul class="page-nav d-flex list-unstyled">
                        <li>
                            <a id="image-library-prev" href="#" class="btn btn-secondary btn-sm me-1 disabled">Prev</a>
                        </li>
                        <li>
                            <a id="image-library-next" href="#" class="btn btn-secondary btn-sm disabled">Next</a>
                        </li>
                    </ul>
                    <ul class="row row-cols-6 gy-1 gx-1 list-unstyled" id="modal-image-list"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" id="image-library-close" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                
                
                    <button type="button" id="set-selected-image" class="btn btn-primary" data-bs-dismiss="modal" data-image-id="" data-thumbnail-src="" data-full-src="" data-action="">Set Selected Image</button>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php 

get_template('footer');