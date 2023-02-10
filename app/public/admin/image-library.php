<?php

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/imageClass.php';

$database = new Database();
$db_con = $database->db_connect();

$image_obj = new Image($db_con);

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

$images = $image_obj->get_images();

get_template('header');
get_template('topbar');

?>

<div class="grid grid-cols-[200px_1fr_200px] top-12 relative">

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
            
            <ul class="image-grid grid grid-cols-4 gap-2 py-4">
                <?php if ($images['status'] === true) : ?>
                    <?php foreach($images['result'] as $image) : ?>
                        <?php
                        $date_arr = explode("-", $image['upload_date']);
                        $year = $date_arr[0];
                        $month = $date_arr[1];
                        ?>
                        <li class="h-32">
                            <a class="h-full w-full group relative" href="edit-image.php?id=<?= $image['id'] ?>">
                                <img class="h-full w-full object-cover object-center " src="../uploads/thumbnails/<?= $year ?>/<?= $month ?>/<?= $image['file_name'] ?>" alt="">
                                <div class="absolute inset-0 opacity-0 group-hover:bg-slate-700/50 group-hover:opacity-100 transition-all flex justify-center items-center">
                                    <img src="assets/images/edit-icon.svg" alt="">
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
</div>