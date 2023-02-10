<?php

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/articleClass.php';

$database = new Database();
$db_con = $database->db_connect();

$article = new Article($db_con);

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $current_article = $article->get_article($id);
}

if(isset($_POST['article-edit-submit'])) {

    $data = [
        'id' => (int)$_POST['id'],
        'title' => $_POST['title'],
        'published' => $_POST['published'],
        'modified' => $_POST['modified'],
        'content' => $_POST['content'],
        'image' => $_POST['image'],
    ];

    $update = $article->update_article($data);

    if(isset($update['status'])) {
        echo json_encode($update);
    }
    exit();
}

get_template('header');
get_template('topbar');

?>

<div class="grid grid-cols-[200px_1fr_200px] top-12 relative">

    <?php get_template('sidebar'); ?>

    <div class="relative px-2 editor-center max-h-[90vh] overflow-y-scroll">

        <form action="" id="edit-article-form">
            <input type="text" name="article-title" value="<?= $current_article['title'] ?>" class="text-xl my-4 p-3 border w-full border-slate-200 focus:ring-4 focus:ring-offset-2 focus:ring-blue-400/50 focus:outline-none rounded-md">
            <input type="hidden" name="published-date" value="<?= $current_article['published'] ?>">
            <input type="hidden" name="modified-date" value="<?= $current_article['modified'] ?>">
            <input type="hidden" name="article-image" value="1">
            <input type="hidden" name="article-id" value="<?= $current_article['id'] ?>">
            <div id="editorjs" class="shadow-md shadow-slate-200/50 border border-slate-100 rounded-lg my-2 py-3"></div>
        </form>

    </div>

    <div class="editor-sidebar sidebar min-h-[100vh] bg-slate-200 p-2">
        <button class="px-2 py-2 mb-2 w-full bg-emerald-500 hover:bg-emerald-600 rounded-md text-slate-100" id="save-article-button">
            Save Article
        </button>
        <h3 class=" border-b border-slate-300 py-2 px-3 mb-2">Featured Image</h3>
        <div class="h-32 bg-slate-300 border mb-2 border-slate-400 hover:bg-slate-100 cursor-pointer relative rounded-md">
            <span class="absolute inset-0 bg-[url('/admin/assets/images/image-icon.svg')] bg-center bg-no-repeat"></span>
        </div>
        <h3 class=" border-b border-slate-400 py-2 px-3 mb-2">Slug</h3>
        <input type="text" name="article-slug" id="article-slug" class="bg-slate-100 border border-slate-300 rounded-md mb-2 p-2 w-full focus:outline-none focus:ring-1 focus:ring-blue-400">
        <button class="px-2 py-2 w-full mb-2 border border-red-500 hover:bg-red-200 rounded-md text-slate-900">
            Delete Article
        </button>
    </div>

</div>



<?php 

get_template('footer');