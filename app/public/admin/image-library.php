<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/imageClass.php';

$database = new Database();
$db_con = $database->db_connect();

$image_obj = new Image($db_con);

if( isset( $_FILES['editor-image']) ) {
    
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
    
    $data['file_name'] = $_FILES['image']['name'];
    $data['tmp_name'] = $_FILES['image']['tmp_name'];
    $data['type'] = $_FILES['image']['type'];
    $data['size'] = $_FILES['image']['size'];
    $data['error'] = $_FILES['image']['error'];

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

?>

<div class="grid grid-cols-[200px_1fr] top-12 relative">

    <?php get_template('sidebar'); ?>
    <div class="px-4 py-3">
        <h1 class="text-2xl pb-2 border-b mb-2">Image Library</h1>

        <h2 class="text-xl">Upload Image</h2>

        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="image" id="image">
            <input type="submit" value="Upload" name="image-upload" class="bg-blue-500 text-slate-100 py-2 px-3 rounded-md cursor-pointer">
        </form>

        <?php if(isset($add_image["status"])) : ?>
            <div class="upload-response-area p-2 <?php echo $add_image["status"] ? "bg-emerald-400" : "bg-red-400" ?> my-2">
                <?php echo $add_image["message"]; ?>
            </div>
        <?php endif; ?>

        <div class="library-area">

            <div class="p-2 my-2 bg-slate-200/50 flex justify-between items-center">
                <p class="text-sm italic">Showing <?= $args['page_no'] ?> of <?= $total_pages ?> pages</p>
                <ul class="page-nav flex">
                    <li>
                        <a href="<?php echo $page_no > 1 ? 'image-library.php?page_no=' . $page_no - 1 : '#' ?>" class=" text-xs px-2 py-1 mr-1 block rounded-md <?php echo $page_no > 1 ? 'bg-slate-300' : 'bg-slate-200 pointer-events-none' ?>">Prev</a>
                    </li>
                    <li>
                        <a href="<?php echo $page_no < $total_pages ? 'image-library.php?page_no=' . $page_no + 1 : '#' ?>" class="text-xs px-2 py-1 block rounded-md <?php echo $page_no < $total_pages ? 'bg-slate-300' : 'bg-slate-200 pointer-events-none' ?>">Next</a>
                    </li>
                </ul>
            </div>
            
            <ul class="image-grid grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-2 py-4">
                <?php if ($images['status'] === true) : ?>
                    <?php foreach($images['result'] as $image) : ?>
                        <?php
                        $date_arr = explode("-", $image['upload_date']);
                        $year = $date_arr[0];
                        $month = $date_arr[1];
                        ?>
                        <li class="h-32">
                            <a class="h-full w-full group relative" href="edit-image.php?id=<?= $image['id'] ?>">
                                <img class="h-full w-full object-cover object-center rounded-md" src="../uploads/thumbnails/<?= $year ?>/<?= $month ?>/<?= $image['file_name'] ?>" alt="">
                                <div class="absolute inset-0 opacity-0 group-hover:bg-slate-700/50 group-hover:opacity-100 transition-all flex justify-center items-center rounded-md">
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