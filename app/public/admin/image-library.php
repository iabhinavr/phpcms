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

$access_obj = new Access($database);
$image_obj = new Image($database);

if( isset( $_FILES['editor-image']) ) {

    $authorization = $access_obj->is_authorized('image', 'create', NULL);

    if(!$authorization) {
        echo json_encode(["msg" => "No access"]);
        exit();
    }
    
    $data['file_name'] = $_FILES['editor-image']['name'];
    $data['tmp_name'] = $_FILES['editor-image']['tmp_name'];
    $data['type'] = $_FILES['editor-image']['type'];
    $data['size'] = $_FILES['editor-image']['size'];
    $data['error'] = $_FILES['editor-image']['error'];

    $add_image = $image_obj->add_image($data);

    $result = [
        "success" => 0
    ];

    if($add_image['status']) {

        if($add_image["last_insert"]["status"]) {
            $last_insert = $add_image["last_insert"]["result"];

            $url = '../uploads/fullsize/' . $last_insert['folder_path'] . '/' . $last_insert['file_name'];
    
            $result = [
                "success" => 1,
                "file" => [
                    "url" => $url,
                ],
            ];
        }

    }

    echo json_encode($result);
    exit();
}

if( isset( $_POST['image-upload']) ) {

    $authorization = $access_obj->is_authorized('image', 'create', NULL);

    if(!$authorization) {
        echo json_encode(["msg" => "No access"]);
        exit();
    }
    
    $data['file_name'] = $_FILES['image']['name'];
    $data['tmp_name'] = $_FILES['image']['tmp_name'];
    $data['type'] = $_FILES['image']['type'];
    $data['size'] = $_FILES['image']['size'];
    $data['error'] = $_FILES['image']['error'];
    $data['author'] = $access_obj->get_current_user()['username'];

    $add_image = $image_obj->add_image($data);

    // header('Location:image-library.php');
    // exit();
}

if( isset( $_POST['fetch-images'])) {

    $args = [
        "per_page" => (int)$_POST['per_page'],
        "page_no" => (int)$_POST['page_no']
    ];

    
    $images = $image_obj->get_images($args);

    echo json_encode($images);
    exit();
}

if(isset($_POST['get_image_count'])) {
    $image_count = $image_obj->get_image_count();

    echo json_encode(["image_count" => $image_count]);
    exit();
}

$image_count = $image_obj->get_image_count();

$args = [
    'per_page' => 12,
    'page_no' => 1,
];

if(isset($_GET['page_no'])) {
    $args['page_no'] = (int)$_GET['page_no'];
}

$page_no = $args['page_no'];
$total_pages = 
    ($image_count % $args['per_page'] === 0) ? 
    floor($image_count / $args['per_page']) : 
    floor($image_count / $args['per_page']) + 1;

$images = $image_obj->get_images($args);

get_template('header');
get_template('topbar');

// Access Control

$authorization = $access_obj->is_authorized('image', 'read', NULL);

?>

<div class="container-fluid text-left">
<div class="row position-relative">

    <?php get_template('sidebar'); ?>
    <div class="col-md-10 position-relative"  style="top:47px">

        <?php if(empty($authorization)) : ?>
            <div class="alert alert-danger">You don't have enough permissions to access this resource</div>
            <?php get_template('footer'); ?>
            <?php exit(); ?>
        <?php endif; ?>

        <h1 class="fs-2 pb-3 pt-3 border-bottom mb-3">Image Library</h1>

        <h2 class="fs-4 mb-2">Upload Image</h2>

        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data" class="mb-4 row g-3 align-items-center">
            <div class="col-auto">
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <div class="col-auto">
                <input type="submit" value="Upload" name="image-upload" class="btn btn-primary">
            </div>
            
            
        </form>

        <?php if(isset($add_image["status"])) : ?>
            <div class="upload-response-area alert <?php echo $add_image["status"] ? "alert-success" : "alert-danger" ?> my-2">
                <?php echo $add_image["message"]; ?>
            </div>
        <?php endif; ?>

        <div class="library-area">

            <div class="py-2 px-2 my-2 bg-body-secondary d-flex justify-content-between align-items-center">
                <p class="mb-0">Showing <?= $args['page_no'] ?> of <?= $total_pages ?> pages</p>
                <ul class="page-nav d-flex list-unstyled mb-0">
                    <li>
                        <a href="<?php echo $page_no > 1 ? 'image-library.php?page_no=' . $page_no - 1 : '#' ?>" class="btn btn-outline-primary btn-sm me-2 <?php echo $page_no > 1 ? '' : 'disabled' ?>">Prev</a>
                    </li>
                    <li>
                        <a href="<?php echo $page_no < $total_pages ? 'image-library.php?page_no=' . $page_no + 1 : '#' ?>" class="btn btn-outline-primary btn-sm me-2 <?php echo $page_no < $total_pages ? '' : 'disabled' ?>">Next</a>
                    </li>
                </ul>
            </div>
            
            
            <ul class="image-grid row row-cols-4 gy-1 gx-1 list-unstyled">
                <?php if ($images['status'] === true) : ?>
                    <?php foreach($images['result'] as $image) : ?>
                        <?php
                        $date_arr = explode("-", $image['upload_date']);
                        $year = $date_arr[0];
                        $month = $date_arr[1];
                        ?>
                        <li class="col">
                            <a class="img-lib-link d-block position-relative w-100" href="edit-image.php?id=<?= $image['id'] ?>">
                                <img class="h-100 w-100 object-fit-cover object-center rounded-md" src="../uploads/thumbnails/<?= $year ?>/<?= $month ?>/<?= $image['file_name'] ?>" alt="">
                                <div class="img-overlay-edit">
                                    <img src="assets/images/edit-icon.svg" alt="" class="">
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            
        </div>
    </div>
    
</div>
</div>