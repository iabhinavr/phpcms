<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/userClass.php';
require_once '../../inc/accessClass.php';

$database = new Database();

$access_obj = new Access($database);
$user_obj = new User($database);

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $get_user = $user_obj->get_user_by_id($id);

    if($get_user["status"]) {
        $user = $get_user["result"];
    }
    else {
        echo $get_user["result"];
        exit();
    }
}

if(isset($_POST['user-edit-submit'])) {

    $authorization = $access_obj->is_authorized('article', 'update', (int)$_POST['id']);

    if(!$authorization) {
        echo json_encode(["msg" => "No access"]);
    }

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

    $update = $user_obj->update_user($data);

    if(isset($update['status'])) {
        echo json_encode($update);
    }
    exit();
}

get_template('header');
get_template('topbar');

// Access Control

$authorization = $access_obj->is_authorized(
    ($user['role'] === 'admin') ? 'admin_user' : 'user', 'read', (int)$_GET['id']);

?>

<div class="grid grid-cols-[200px_1fr] top-12 relative">

    <?php get_template('sidebar'); ?>

    <div class="px-4 py-3">

        <?php if(empty($authorization)) : ?>
            <p class="p-2 bg-red-500/75 text-white rounded-sm">You don't have enough permissions to access this resource</p>
            <?php get_template('footer'); ?>
            <?php exit(); ?>
        <?php endif; ?>

        <h1 class="text-2xl pb-2 border-b mb-2">Edit User</h1>

        <form action="" id="edit-user-form">
            <div class="my-2 mb-3 grid grid-cols-[200px_1fr_100px]">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" value="<?= $user['first_name'] ?>" class="py-1 px-2 border border-slate-200 focus:ring-4 focus:ring-offset-2 focus:ring-blue-400/50 focus:outline-none rounded-md">
            </div>

            <div class="my-2 mb-3 grid grid-cols-[200px_1fr_100px]">
                <label for="username">User Name</label>
                <input type="text" name="username" id="username" value="<?= $user['username'] ?>" class="py-1 px-2 border border-slate-200 focus:ring-4 focus:ring-offset-2 focus:ring-blue-400/50 focus:outline-none rounded-md">
            </div>
            
            <div class="my-2 mb-3 grid grid-cols-[200px_1fr_100px]">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= $user['email'] ?>" class="py-1 px-2 border border-slate-200 focus:ring-4 focus:ring-offset-2 focus:ring-blue-400/50 focus:outline-none rounded-md">
            </div>

            <div class="my-2 mb-3 grid grid-cols-[200px_1fr_100px]">
                <span>Role</span> <span><?= $user['role'] ?> </span>
            </div>
            
            <input type="hidden" name="user-id" value="<?= $user['id'] ?>">
        </form>

    </div>

</div>


<?php 

get_template('footer');