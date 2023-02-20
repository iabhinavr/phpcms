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

$user_count = $user_obj->get_user_count();

$args = [
    'per_page' => 10,
    'page_no' => 1,
];

if(isset($_GET['page_no'])) {
    $args['page_no'] = (int)$_GET['page_no'];
}

$page_no = $args['page_no'];
$total_pages = 
    ($user_count % $args['per_page'] === 0) ? 
    floor($user_count / $args['per_page']) : 
    floor($user_count / $args['per_page']) + 1;

$users = $user_obj->get_users($args);

get_template('header');
get_template('topbar');

// Access Control

$access['resource_type'] = 'image';
$access['action'] = 'read';
$access['resource_identifier'] = NULL;

$authorization = $access_obj->is_authorized(
    $access['resource_type'], 
    $access['action'], 
    $access['resource_identifier']);

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
    
            <h1 class="text-2xl pb-2 border-b mb-2">Users</h1>
    
    
            <table class="table table-striped">
    
                <thead>
                    <tr class="[&>th]:border-b text-left [&>th]:p-2">
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
    
    
                <tbody>
                    <?php foreach($users as $user) : ?>
                        <tr class="[&>td]:border-b text-left [&>td]:p-2 hover:bg-slate-100 [&>td>a:hover]:underline [&>td>a]:text-sky-600">
                            <td>
                                <a href="edit-user.php?id=<?= $user['id']; ?>">
                                    <?= $user['first_name']; ?>
                                </a>
                            </td>
                            <td>
                                <?= $user['username']; ?>
                            </td>
                            <td><?= $user['email']; ?></td>
                            <td><?= $user['role']; ?></td>
                        </tr>
    
                    <?php endforeach; ?>
                </tbody>
    
            </table>
    
            <div class="py-2 px-2 my-2 bg-body-secondary d-flex justify-content-between align-items-center">
                <p class="mb-0">Showing <?= $args['page_no'] ?> of <?= $total_pages ?> pages</p>
                <ul class="page-nav d-flex list-unstyled mb-0">
                    <li>
                        <a href="<?php echo $page_no > 1 ? 'users.php?page_no=' . $page_no - 1 : '#' ?>" class="btn btn-outline-primary me-2 <?php echo $page_no > 1 ? 'bg-slate-300' : 'bg-slate-200 pointer-events-none' ?>">Prev</a>
                    </li>
                    <li>
                        <a href="<?php echo $page_no < $total_pages ? 'users.php?page_no=' . $page_no + 1 : '#' ?>" class="btn btn-outline-primary <?php echo $page_no < $total_pages ? 'bg-slate-300' : 'bg-slate-200 pointer-events-none' ?>">Next</a>
                    </li>
                </ul>
            </div>
    
        </div>
    
    </div>
</div>

<?php

get_template('footer');