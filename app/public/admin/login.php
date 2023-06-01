<?php

session_start();

include('inc/functions.php');

require_once '../inc/databaseClass.php';
require_once '../inc/userClass.php';

$database = new Database();

$user_obj = new User($database);

if(isset($_POST['login'])) {
    if(!empty($_POST['username']) && !empty($_POST['password'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $authenticated = $user_obj->authenticate($username, $password);

        generate_csrf_token();

        if($authenticated) {

            $get_user = $user_obj->get_user_by_username($username);
            if($get_user["status"]) {
                $current_user = $get_user["result"];
                $_SESSION['username'] = $current_user['username'];
                $_SESSION['first_name'] = $current_user['first_name'];
                $_SESSION['email'] = $current_user['email'];
                $_SESSION['role'] = $current_user['role'];
                header('Location:articles.php');
            }
            else {
                $authenticated = false;
            }

            
            exit();
        }
    }
}

get_template('header');

?>

<div class="min-vh-100 d-flex align-items-center justify-content-center flex-column bg-body-secondary">

    <?php if(isset($authenticated) && $authenticated === false ) : ?>
        <div class=" bg-red-500 p-2 text-sm text-white rounded-sm mb-2">Incorrect Username or Password!</div>
    <?php endif; ?>

    <div class="d-flex justify-content-center align-items-center w-[320px] bg-slate-100 p-6">
        <form action="" method="post" class="d-flex flex-column w-100">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            

            <div class="mb-3 form-check">
                <input type="checkbox" name="rememberme" id="rememberme" class="form-check-input">
                <label for="rememberme" class="form-check-label">Remember Me</label>
            </div>
            <input type="submit" value="Login" name="login" id="login" class="btn btn-primary">
            
        </form>
    </div>
</div>


