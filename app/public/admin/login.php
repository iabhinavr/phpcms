<?php

session_start();

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/userClass.php';

$database = new Database();

$user_obj = new User($database);

if(isset($_POST['login'])) {
    if(!empty($_POST['username']) && !empty($_POST['password'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $authenticated = $user_obj->authenticate($username, $password);

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

<div class="min-h-[100vh] flex items-center justify-center flex-col bg-slate-200/75">

    <?php if(isset($authenticated) && $authenticated === false ) : ?>
        <div class=" bg-red-500 p-2 text-sm text-white rounded-sm mb-2">Incorrect Username or Password!</div>
    <?php endif; ?>

    <div class="flex justify-center items-center w-[320px] bg-slate-100 p-6">
        <form action="" method="post" class="flex flex-col w-full">
            <label for="username" class="pb-1">Username</label>
            <input type="text" name="username" id="username" class="rounded-sm mb-2 p-2 focus:outline-none focus:ring-1 focus:ring-blue-400">
            <label for="password" class="pb-1">Password</label>
            <input type="password" name="password" id="password" class="rounded-sm mb-2 p-2 focus:outline-none focus:ring-1 focus:ring-blue-400">
            <div class="flex justify-between mt-2">
                <div>
                    <input type="checkbox" name="rememberme" id="rememberme">
                    <label for="rememberme">Remember Me</label>
                </div>
                <input type="submit" value="Login" name="login" id="login" class="px-3 py-1 bg-emerald-500/75 hover:bg-emerald-600 rounded-sm text-slate-100 cursor-pointer">
            </div>
            
        </form>
    </div>
</div>


