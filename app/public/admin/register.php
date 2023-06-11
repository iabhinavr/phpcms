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

if(isset($_POST['add-user-submit'])) {
    $data = array(
        'first_name' => $_POST['new-user-firstname'],
        'email' => $_POST['new-user-email'],
        'username' => $_POST['new-user-username'],
        'password' => $_POST['new-user-password'],
        'repassword' => $_POST['new-user-repassword'],
        'role' => (int)$_POST['new-user-role']
    );

    $authorization = $access_obj->is_authorized(((int)$data['role'] === 1) ? 'admin_user' : 'user', 'create', NULL);

    if(!$authorization) {
        echo json_encode(["status" => false, "result" => "Not enough permissions"]);
        exit();
    }

    $add_user = $user_obj->add_user($data);
}

$authorization = $access_obj->is_authorized('user', 'create', NULL);

get_template('header');
get_template('topbar');

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

            <h1 class="fs-2 pb-3 pt-3 border-bottom mb-3">Add New User</h1>

            <?php if(!empty($add_user)) : ?>
                <div class="alert <?= $add_user['status'] ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                    <?= $add_user['result']; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo esc_html($_SERVER['PHP_SELF']) ?>" method="post" id="add-user-form">
                <div class="mb-3">
                    <label for="new-user-firstname">First Name</label>
                    <input type="text" class="form-control" name="new-user-firstname" id="new-user-firstname" value="">
                </div>
                <div class="mb-3">
                    <label for="new-user-email">Email</label>
                    <input type="text" class="form-control" name="new-user-email" id="new-user-email" value="">
                </div>
                <div class="mb-3">
                    <label for="new-user-username">Username</label>
                    <input type="text" class="form-control" name="new-user-username" id="new-user-username" value="">
                </div>
                <div class="mb-3">
                    <label for="new-user-password">Password</label>
                    <input type="password" class="form-control" name="new-user-password" id="new-user-password" value="">
                </div>
                <div class="mb-1">
                    <label for="new-user-repassword">Retype Password</label>
                    <input type="password" class="form-control" name="new-user-repassword" id="new-user-repassword" value="">
                </div>
                <div id="password-help" class="form-text mb-3">Ideally, password should be at least 8 characters long, include letter, numbers, and special characters</div>
                <div class="mb-3">
                    <select class="form-select" name="new-user-role" id="new-user-role">
                        <option value="2">Editor</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="add-user-submit" id="add-user-submit">Add User</button>
            </form>
        </div>
    </div>
</div>

<?php

get_template('footer');