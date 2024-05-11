<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../inc/databaseClass.php';
require_once '../inc/imageClass.php';
require_once '../inc/settingsClass.php';
require_once '../inc/accessClass.php';

$database = new Database();
$access_obj = new Access($database);
$settings_obj = new Settings($database);
$image_obj = new Image($database, $settings_obj);

if(isset($_POST['image-save'])) {

    $authorization = $access_obj->is_authorized('image', 'update', (int)$_POST['id']);

    if(!$authorization) {
        echo json_encode(["msg" => "No access"]);
        exit();
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
        exit();
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
        echo json_encode($get_image);
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

$authorization = $access_obj->is_authorized('image', 'read',(int)$_GET['id']);

?>

<div class="container-fluid text-left">
    <div class="row position-relative">
    
        <?php get_template('sidebar'); ?>

        <?php if(empty($authorization)) : ?>
            <div class="editor-middle flex-grow-1 col-md-8 position-relative px-2 editor-center max-vh-90 overflow-y-scroll bg-light-subtle">
                <div class="alert alert-danger">You don't have enough permissions to access this resource</div>
            </div>

        <?php else : ?>

            <div class="editor-middle flex-grow-1 col-md-8 position-relative px-2 editor-center max-vh-90 overflow-y-scroll bg-light-subtle">
    
                <h1 class="fs-2 pb-3 pt-3 border-bottom mb-3">
                    <?php
                    echo $current_image['title'] ? esc_html($current_image['title']) : esc_html($current_image['file_name']);
                    ?>
                </h1>
        
                <img src="../uploads/fullsize/<?= esc_html($current_image['folder_path']) ?>/<?= esc_html($current_image['file_name']) ?>" alt="" class="w-100">
            
            </div>
    
            <div class="col-md-2 editor-sidebar sidebar min-vh-100 bg-body-secondary p-2 position-relative">
                <button class="px-2 py-2 mb-2 w-100 btn btn-success" id="save-image-button">
                    Save Image
                </button>
                <h3 class="fs-5 border-b py-2 mb-2">Thumbnail</h3>
                <div class="border mb-2 sidebar-thumbnail">
                    <span style="background-image: url(<?= $thumbnail ?>)"></span>
                </div>
                <h3 class="fs-5 border-b py-2 mb-2">Image Title</h3>
                <input type="text" name="image-title" id="image-title" class="form-control" value="<?= esc_html($current_image['title']) ?>">
                <input type="hidden" name="image-id" value="<?= $current_image['id'] ?>">
                <button class="btn btn-danger w-100 mt-2" id="delete-image-button" data-image-id="<?= $current_image['id'] ?>">
                    Delete Image
                </button>
            </div>
        <?php endif; ?> 
    
    </div>
</div>



<?php 

get_template('footer');