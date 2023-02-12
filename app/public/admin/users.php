<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/userClass.php';

$database = new Database();
$db_con = $database->db_connect();

$user_obj = new User($db_con);

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

?>

<div class="grid grid-cols-[200px_1fr] top-12 relative">

    <?php get_template('sidebar'); ?>
    <div class="px-4 py-3">
        <h1 class="text-2xl pb-2 border-b mb-2">Users</h1>


        <table class="table-auto border-collapse w-full text-sm">

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

        <div class="p-2 my-2 bg-slate-200/50 flex justify-between items-center">
            <p class="text-sm italic">Showing <?= $args['page_no'] ?> of <?= $total_pages ?> pages</p>
            <ul class="page-nav flex">
                <li>
                    <a href="<?php echo $page_no > 1 ? 'users.php?page_no=' . $page_no - 1 : '#' ?>" class=" text-xs px-2 py-1 mr-1 block rounded-md <?php echo $page_no > 1 ? 'bg-slate-300' : 'bg-slate-200 pointer-events-none' ?>">Prev</a>
                </li>
                <li>
                    <a href="<?php echo $page_no < $total_pages ? 'users.php?page_no=' . $page_no + 1 : '#' ?>" class="text-xs px-2 py-1 block rounded-md <?php echo $page_no < $total_pages ? 'bg-slate-300' : 'bg-slate-200 pointer-events-none' ?>">Next</a>
                </li>
            </ul>
        </div>

    </div>
    
</div>