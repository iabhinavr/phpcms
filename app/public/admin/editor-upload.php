<?php

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/imageClass.php';

$database = new Database();
$db_con = $database->db_connect();

$image_obj = new Image($db_con);

if( isset($_FILES['editor-image-upload']) ) {
    
    $data['file_name'] = $_FILES['editor-image-upload']['name'];
    $data['tmp_name'] = $_FILES['editor-image-upload']['tmp_name'];
    $data['type'] = $_FILES['editor-image-upload']['type'];
    $data['size'] = $_FILES['editor-image-upload']['size'];
    $data['error'] = $_FILES['editor-image-upload']['error'];

    $add_image = $image_obj->add_image($data);

    echo json_encode($add_image);
    exit();
}