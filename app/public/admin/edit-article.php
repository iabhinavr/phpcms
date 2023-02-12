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

$database = new Database();
$db_con = $database->db_connect();

$article_obj = new Article($db_con);
$image_obj = new Image($db_con);

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $get_article = $article_obj->get_article($id);

    if($get_article["status"]) {
        $article = $get_article["result"];
        $featured_image_id = $article["image"];

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

get_template('header');
get_template('topbar');

?>

<div class="grid grid-cols-[200px_1fr_250px] top-12 relative">

    <?php get_template('sidebar'); ?>

    <div class="relative px-2 editor-center max-h-[90vh] overflow-y-scroll">

        <form action="" id="edit-article-form">
            <input type="text" name="article-title" value="<?= $article['title'] ?>" class="text-xl my-4 p-3 border w-full border-slate-200 focus:ring-4 focus:ring-offset-2 focus:ring-blue-400/50 focus:outline-none rounded-md">
            <input type="hidden" name="published-date" value="<?= $article['published'] ?>">
            <input type="hidden" name="modified-date" value="<?= $article['modified'] ?>">
            <input type="hidden" name="article-image" value="<?= $article['image'] ?>">
            <input type="hidden" name="article-id" value="<?= $article['id'] ?>">
            <div id="editorjs" class="px-20 shadow-md shadow-slate-200/50 border border-slate-100 rounded-lg my-2 py-3"><?php echo base64_encode($article['content']) ?></div>
        </form>

    </div>

    <div class="editor-sidebar sidebar min-h-[100vh] bg-slate-200 p-2">
        <button class="px-2 py-2 mb-2 w-full bg-emerald-500 hover:bg-emerald-600 rounded-md text-slate-100" id="save-article-button">
            Save Article
        </button>
        <h3 class=" border-b border-slate-300 py-2 mb-2">Featured Image</h3>
        <div class="h-32 bg-slate-300 border mb-2 border-slate-400 hover:bg-slate-100 bg-[url('/admin/assets/images/image-icon.svg')] bg-center bg-no-repeat cursor-pointer relative rounded-md overflow-hidden">
            <span class="absolute inset-0 bg-center bg-no-repeat bg-cover" id="select-featured-image" style="background-image: <?php echo $featured_image_id ? 'url(../uploads/thumbnails/' . $featured_image['folder_path'] . '/' . $featured_image['file_name'] . ')' : 'none' ?>"></span>
        </div>
        <h3 class=" border-b border-slate-400 py-2 mb-2">Slug</h3>
        <input type="text" name="article-slug" id="article-slug" class="bg-slate-100 border border-slate-300 rounded-md mb-2 p-2 w-full focus:outline-none focus:ring-1 focus:ring-blue-400" value="<?= $article['slug'] ?>">
        <h3 class=" border-b border-slate-400 py-2 mb-2">Excerpt</h3>
        <textarea name="article-excerpt" id="article-excerpt" class="bg-slate-100 border border-slate-300 rounded-md mb-2 p-2 w-full focus:outline-none focus:ring-1 focus:ring-blue-400 text-sm"><?= $article['excerpt'] ?></textarea>
        <button class="px-2 py-2 w-full mb-2 border border-red-500 hover:bg-red-200 rounded-md text-slate-900">
            Delete Article
        </button>
    </div>

</div>

<div class="hidden inset-0 bg-slate-900/90 z-20 p-16" id="image-library">
    <button id="image-library-close" class="absolute right-2 top-2">
        <img width="24" height="24" src="assets/images/close-icon.svg" alt="">
    </button>

    <h2 class="py-2 mb-3 border-b border-slate-600 text-slate-300">Select an Image from the Library</h2>
    <ul class="grid grid-cols-6 gap-2" id="modal-image-list"></ul>

    <button id="set-selected-image" class="absolute right-10 top-2 px-3 py-1 bg-emerald-500 rounded-md text-sm" data-image-id="" data-thumbnail-src="" data-full-src="" data-action="">Set Selected Image</button>
</div>

<?php 

get_template('footer');