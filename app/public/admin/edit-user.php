<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../inc/databaseClass.php';
require_once '../inc/userClass.php';
require_once '../inc/accessClass.php';

$database = new Database();

$access_obj = new Access($database);
$user_obj = new User($database);

if(isset($_POST['edit-user-submit'])) {

    $data = [
        'first_name' => $_POST['first_name'],
        'id' => (int)$_POST['id'],
    ];

    $get_user = $user_obj->get_user_by_id($data['id']);

    if($get_user['status']) {
        $user = $get_user['result'];
    }
    else {
        echo json_encode($get_user);
        exit();
    }

    $authorization = $access_obj->is_authorized(($user['role'] === 'admin') ? 'admin_user' : 'user', 'update', (int)$_POST['id']);

    if(!$authorization) {
        echo json_encode(["status" => false, "result" => "Not enough permissions"]);
        exit();
    }

    $update = $user_obj->update_user($data);

    if($update['status']) {
        header('Location:edit-user.php?id=' . (int)$_POST['id']);
        exit();
    }
    else {
        echo $update['result'];
        exit();
    }
    
}

if(isset($_POST['password-change-submit'])) {

    $data = [
        'id' => (int)$_POST['id'],
        'existing' => $_POST['existing'],
        'new' => $_POST['new'],
        'retype' => $_POST['retype'],
    ];

    $get_user = $user_obj->get_user_by_id($data['id']);

    if($get_user['status']) {
        $user = $get_user['result'];
    }
    else {
        echo json_encode($get_user);
        exit();
    }

    if($user['role'] === 'admin') {
        $resource_type = 'admin_user';
    }
    else {
        $resource_type = 'user';
    }

    $authorization = $access_obj->is_authorized($resource_type, 'update', $data['id']);

    if(!$authorization) {
        echo "No access";
        exit();
    }

    $change_password = $user_obj->change_password($data);
    echo json_encode($change_password);
    exit();
    
}

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

get_template('header');
get_template('topbar');

// Access Control

$authorization = $access_obj->is_authorized(
    ($user['role'] === 'admin') ? 'admin_user' : 'user', 'read', (int)$_GET['id']);

?>

<div class="container-fluid text-left">
    <div class="row position-relative">
    
        <?php get_template('sidebar'); ?>
    
        <div class="editor-middle col-md-10 bg-light-subtle">
    
            <?php if(empty($authorization)) : ?>
                <div class="alert alert-danger">You don't have enough permissions to access this resource</div>
                <?php get_template('footer'); ?>
                <?php exit(); ?>
            <?php endif; ?>
    
            <h1 class="fs-2 pb-3 pt-3 border-bottom mb-3">Edit User</h1>
    
            <h2 class="fs-4 pb-2">Basic Details</h2>
            <form action="<?= esc_html($_SERVER['PHP_SELF']) ?>" id="edit-user-form" method="post">
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="<?= esc_html($user['first_name']) ?>" class="form-control">
                </div>
    
                <div class="mb-3">
                    <label for="username" class="form-label">User Name</label>
                    <input type="text" name="username" id="username" value="<?= esc_html($user['username']) ?>" class="form-control" disabled>
                </div>
    
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="<?= esc_html($user['email']) ?>" class="form-control" disabled>
                </div>
    
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <input type="text" name="role" id="role" value="<?= esc_html($user['role']) ?>" class="form-control" disabled>
                </div>
    
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <button type="submit" name="edit-user-submit" class="btn btn-primary">Save</button>
            </form>

            <h2 class="fs-4 pb-2 mt-4">Change Password</h2>

            <form action="" id="change-password-form">
                <div class="mb-3">
                    <label for="existing-password" class="form-label">Existing Password</label>
                    <input type="password" name="existing-password" id="existing-password" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="new-password" class="form-label">New Password</label>
                    <input type="password" name="new-password" id="new-password" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="retype-password" class="form-label">Retype New Password</label>
                    <input type="password" name="retype-password" id="retype-password" class="form-control">
                </div>
                <input type="hidden" name="user-id" value="<?= $user['id'] ?>">
                <button type="submit" class="btn btn-danger">Change Password</button>
            </form>
    
        </div>
    
    </div>
</div>


<?php 

get_template('footer');