<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/articleClass.php';
require_once '../../inc/imageClass.php';
require_once '../../inc/accessClass.php';

$database = new Database();

$access_obj = new Access($database);
$article_obj = new Article($database);
$image_obj = new Image($database);

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
        echo $get_article["result"];
        exit();
    }
}

if(isset($_POST['article-edit-submit'])) {

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

<div class="grid grid-cols-[200px_1fr_250px] top-12 relative">

    <?php get_template('sidebar'); ?>

    <?php if(empty($authorization)) : ?>
        <div class="px-2 py-4">
            <p class="p-2 bg-red-500/75 text-white rounded-sm">You don't have enough permissions to access this resource</p>
        </div>
        
    </div>
        <?php get_template('footer'); ?>
        <?php exit(); ?>
    <?php endif; ?>

    <div class="relative px-2 editor-center max-h-[90vh] overflow-y-scroll">

        <form action="" id="edit-article-form">
            <input type="text" name="article-title" value="<?= $article['title'] ?>" class="text-xl my-4 p-3 border w-full border-slate-200 focus:ring-4 focus:ring-offset-2 focus:ring-blue-400/50 focus:outline-none rounded-md">
            <input type="hidden" name="published-date" value="<?= $article['published'] ?>">
            <input type="hidden" name="modified-date" value="<?= $article['modified'] ?>">
            <input type="hidden" name="article-image" value="<?= $article['image'] ?>">
            <input type="hidden" name="article-id" value="<?= $article['id'] ?>">
            <div id="editorjs" class="px-20 shadow-md shadow-slate-200/50 border border-slate-100 rounded-lg my-2 py-3"><?php echo !empty($article['content']) ? base64_encode($article['content']) : base64_encode('{}') ?></div>
        </form>

    </div>

    <div class="editor-sidebar sidebar min-h-[100vh] bg-slate-200 p-2">
        <button class="px-2 py-2 mb-2 w-full bg-emerald-500 hover:bg-emerald-600 rounded-md text-slate-100" id="save-article-button">
            Save Article
        </button>
        <h3 class=" border-b border-slate-300 py-2 mb-2">Featured Image</h3>
        <div class="h-32 bg-slate-300 border mb-2 border-slate-400 hover:bg-slate-100 bg-[url('/admin/assets/images/image-icon.svg')] bg-center bg-no-repeat cursor-pointer relative rounded-md overflow-hidden">
            <span class="absolute inset-0 bg-center bg-no-repeat bg-cover" id="select-featured-image" style="background-image: <?php echo !empty($featured_image) ? 'url(../uploads/thumbnails/' . $featured_image['folder_path'] . '/' . $featured_image['file_name'] . ')' : 'none' ?>"></span>
        </div>
        <h3 class=" border-b border-slate-400 py-2 mb-2">Slug</h3>
        <input type="text" name="article-slug" id="article-slug" class="bg-slate-100 border border-slate-300 rounded-md mb-2 p-2 w-full focus:outline-none focus:ring-1 focus:ring-blue-400" value="<?= $article['slug'] ?>">
        <h3 class=" border-b border-slate-400 py-2 mb-2">Excerpt</h3>
        <textarea name="article-excerpt" id="article-excerpt" class="bg-slate-100 border border-slate-300 rounded-md mb-2 p-2 w-full focus:outline-none focus:ring-1 focus:ring-blue-400 text-sm"><?= $article['excerpt'] ?></textarea>
        <button class="px-2 py-2 w-full mb-2 border border-red-500 hover:bg-red-200 rounded-md text-slate-900" id="delete-article-button" data-article-id="<?= $article['id'] ?>">
            Delete Article
        </button>
    </div>

</div>

<div class="hidden inset-0 bg-slate-900/90 z-20 p-16" id="image-library">
    <button id="image-library-close" class="absolute right-2 top-2">
        <img width="24" height="24" src="assets/images/close-icon.svg" alt="">
    </button>

    <div class="container mx-auto lg:max-w-4xl">
        <div class="flex justify-between items-center">
            <h2 class="py-2 mb-3 border-b border-slate-600 text-slate-300">Select an Image from the Library</h2>
            <ul class="page-nav flex">
                <li>
                    <a id="image-library-prev" href="#" class="text-xs px-2 py-1 mr-1 block rounded-md bg-slate-400 pointer-events-none">Prev</a>
                </li>
                <li>
                    <a id="image-library-next" href="#" class="text-xs px-2 py-1 block rounded-md bg-slate-400 pointer-events-none">Next</a>
                </li>
            </ul>
        </div>
        
        <ul class="grid grid-cols-4 gap-2" id="modal-image-list"></ul>
    </div>
    

    <button id="set-selected-image" class="absolute right-10 top-2 px-3 py-1 bg-emerald-500 rounded-md text-sm" data-image-id="" data-thumbnail-src="" data-full-src="" data-action="">Set Selected Image</button>
</div>

<?php 

get_template('footer');