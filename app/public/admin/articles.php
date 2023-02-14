<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/articleClass.php';
require_once '../../inc/accessClass.php';

$database = new Database();

$access_obj = new Access($database);

$article_obj = new Article($database);

if(isset($_POST['add-article'])) {
    $datetime = date('Y-m-d H:i:s');

    $data = [
        'title' => $_POST['article-title'],
        'published' => $datetime,
        'modified' => $datetime,
        'content' => '',
        'image' => NULL,
        'slug' => '',
        'excerpt' => '',
        'author' => $access_obj->get_current_user()['username'],
    ];

    $add_article = $article_obj->add_article($data);

    if($add_article['status']) {
        header('Location: edit-article.php?id=' . $add_article['insert_id']);
        exit();
    }
    echo json_encode($add_article);
    exit();
}

$article_count = $article_obj->get_article_count();

$args = [
    'per_page' => 10,
    'page_no' => 1,
];

if(isset($_GET['page_no'])) {
    $args['page_no'] = (int)$_GET['page_no'];
}

$page_no = $args['page_no'];
$total_pages = 
    ($article_count % $args['per_page'] === 0) ? 
    floor($article_count / $args['per_page']) : 
    floor($article_count / $args['per_page']) + 1;

$articles = $article_obj->get_articles($args);

get_template('header');
get_template('topbar');

// Access Control

$authorization = $access_obj->is_authorized('article', 'read', NULL);

?>

<div class="grid grid-cols-[200px_1fr] top-12 relative">

    <?php get_template('sidebar'); ?>
    <div class="px-4 py-3">

        <?php if(empty($authorization)) : ?>
            <p class="p-2 bg-red-500/75 text-white rounded-sm">You don't have enough permissions to access this resource</p>
            <?php get_template('footer'); ?>
            <?php exit(); ?>
        <?php endif; ?>

        <h1 class="text-2xl pb-2 border-b mb-2">Articles</h1>

        <h2 class="text-xl mb-2">Add Article</h2>

        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" class="mb-4">
            <input type="text" name="article-title" id="article-title" class="bg-slate-100 border border-slate-300 rounded-md mr-2 p-2 focus:outline-none focus:ring-1 focus:ring-blue-400 inline-block" placeholder="Title">
            <input type="submit" value="Add Article" name="add-article" class="bg-blue-500 text-slate-100 py-2 px-3 rounded-md cursor-pointer">
        </form>

        <table class="table-auto border-collapse w-full text-sm">

            <thead>
                <tr class="[&>th]:border-b text-left [&>th]:p-2">
                    <th>Title</th>
                    <th>Published Date</th>
                    <th>Modified Date</th>
                    <th>Slug</th>
                    <th>Featured Image</th>
                </tr>
            </thead>
            

            <tbody>
                <?php foreach($articles as $article) : ?>
                    <tr class="[&>td]:border-b text-left [&>td]:p-2 hover:bg-slate-100 [&>td>a:hover]:underline [&>td>a]:text-sky-600">
                        <td>
                            <a href="edit-article.php?id=<?= $article['id']; ?>">
                                <?= $article['title']; ?>
                            </a>
                        </td>
                        <td>
                            <?= $article['published']; ?>
                        </td>
                        <td><?= $article['modified']; ?></td>
                        <td><?= $article['slug']; ?></td>
                        <td>
                            <a href="edit-image.php?id=<?= $article['image']; ?>">
                                <?= $article['image']; ?>
                            </a>
                        </td>
                    </tr>
                    
                <?php endforeach; ?>
            </tbody>
            
        </table>

        <div class="p-2 my-2 bg-slate-200/50 flex justify-between items-center">
            <p class="text-sm italic">Showing <?= $args['page_no'] ?> of <?= $total_pages ?> pages</p>
            <ul class="page-nav flex">
                <li>
                    <a href="<?php echo $page_no > 1 ? 'articles.php?page_no=' . $page_no - 1 : '#' ?>" class=" text-xs px-2 py-1 mr-1 block rounded-md <?php echo $page_no > 1 ? 'bg-slate-300' : 'bg-slate-200 pointer-events-none' ?>">Prev</a>
                </li>
                <li>
                    <a href="<?php echo $page_no < $total_pages ? 'articles.php?page_no=' . $page_no + 1 : '#' ?>" class="text-xs px-2 py-1 block rounded-md <?php echo $page_no < $total_pages ? 'bg-slate-300' : 'bg-slate-200 pointer-events-none' ?>">Next</a>
                </li>
            </ul>
        </div>

    </div>
    
</div>