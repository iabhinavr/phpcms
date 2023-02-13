<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/imageClass.php';
require_once '../../inc/accessClass.php';

$database = new Database();
$db_con = $database->db_connect();

$access_obj = new Access($db_con);

$image_obj = new Image($db_con);

if(isset($_POST['image-save'])) {

    $authorization = $access_obj->is_authorized('image', 'update', (int)$_POST['id']);

    if(!$authorization) {
        echo json_encode(["msg" => "No access"]);
    }

    $data = [
        'id' => (int)$_POST['id'],
        'title' => $_POST['title'],
    ];

    $update = $image_obj->update_image($data);

    echo json_encode($update);
    exit();

}

if(isset($_POST['image-delete'])) {

    $authorization = $access_obj->is_authorized('image', 'delete', (int)$_POST['id']);

    if(!$authorization) {
        echo json_encode(["msg" => "No access"]);
    }

    $id = (int)$_POST['id'];

    $delete = $image_obj->delete_image($id);

    echo json_encode($delete);
    exit();
}

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $get_image = $image_obj->get_image($id);

    if($get_image["status"]) {
        $current_image = $get_image["result"];
        $fullsize = get_home_url() . 
                    "/uploads/fullsize/" . 
                    $current_image['folder_path'] ."/" . 
                    $current_image['file_name'];

        $thumbnail = get_home_url() . 
                    "/uploads/thumbnails/" . 
                    $current_image['folder_path'] ."/" . 
                    $current_image['file_name'];
    }
    else {
        echo $get_image["result"];
        exit();
    }
}
else {
    echo "Invalid request";
    exit();
}

get_template('header');
get_template('topbar');

// Access Control

$authorization = $access_obj->is_authorized('image', 'update',(int)$_GET['id']);

?>

<div class="grid grid-cols-[200px_1fr_200px] top-12 relative">

    <?php get_template('sidebar'); ?>

    <div class="relative px-2 py-4 editor-center max-h-[90vh] overflow-y-scroll">

        <?php if(empty($authorization)) : ?>
            <p class="p-2 bg-red-500/75 text-white rounded-sm">You don't have enough permissions to access this resource</p>
            <?php get_template('footer'); ?>
            <?php exit(); ?>
        <?php endif; ?>

        <h1 class="text-2xl py-3">
            <?php
            echo $current_image['title'] ? $current_image['title'] : $current_image['file_name'];
            ?>
        </h1>

        <img src="../uploads/fullsize/<?= $current_image['folder_path'] ?>/<?= $current_image['file_name'] ?>" alt="">
    </div>

    <div class="editor-sidebar sidebar min-h-[100vh] bg-slate-200 p-2">
        <button class="px-2 py-2 mb-2 w-full bg-emerald-500 hover:bg-emerald-600 rounded-md text-slate-100" id="save-image-button">
            Save Image
        </button>
        <h3 class=" border-b border-slate-300 py-2 px-3 mb-2">Thumbnail</h3>
        <div class="h-32 bg-slate-300 border mb-2 border-slate-400 hover:bg-slate-100 cursor-pointer relative rounded-md">
            <span class="absolute inset-0 bg-center bg-no-repeat bg-cover rounded-md" style="background-image: url(<?= $thumbnail ?>)"></span>
        </div>
        <h3 class=" border-b border-slate-400 py-2 px-3 mb-2">Image Title</h3>
        <input type="text" name="image-title" id="image-title" class="bg-slate-100 border border-slate-300 rounded-md mb-2 p-2 w-full focus:outline-none focus:ring-1 focus:ring-blue-400" value="<?= $current_image['title'] ?>">
        <input type="hidden" name="image-id" value="<?= $current_image['id'] ?>">
        <button class="px-2 py-2 w-full mb-2 border border-red-500 hover:bg-red-200 rounded-md text-slate-900" id="delete-image-button" data-image-id="<?= $current_image['id'] ?>">
            Delete Image
        </button>
    </div>

</div>



<?php 

get_template('footer');