<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../inc/databaseClass.php';
require_once '../inc/articleClass.php';
require_once '../inc/accessClass.php';

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

    $validate_csrf_token = validate_csrf_token($_POST['csrf-token']);

    if(!$validate_csrf_token['status']) {
        echo json_encode($validate_csrf_token);
        exit();
    }

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
<div class="container-fluid text-left">
<div class="row position-relative">

    <?php get_template('sidebar'); ?>
    <div class="col-md-10 position-relative editor-middle bg-light-subtle">

        <?php if(empty($authorization)) : ?>
            <div class="alert alert-danger">You don't have enough permissions to access this resource</div>
            <?php get_template('footer'); ?>
            <?php exit(); ?>
        <?php endif; ?>

        <h1 class="fs-2 pb-3 pt-3 border-bottom mb-3">Articles</h1>

        <h2 class="fs-4 mb-2">Add Article</h2>

        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" class="mb-4 row g-3 align-items-center">
            <div class="col-auto">
                <input type="text" name="article-title" id="article-title" class="form-control" placeholder="Title">
            </div>
            <div class="col-auto">
                <input type="submit" value="Add Article" name="add-article" class="btn btn-primary">
            </div>
            <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
        </form>

        <table class="table table-striped">

            <thead>
                <tr class="[&>th]:border-b text-left [&>th]:p-2">
                    <th>Title</th>
                    <th>Published On</th>
                    <th>Modified</th>
                    <th>Slug</th>
                    <th>Author</th>
                </tr>
            </thead>
            

            <tbody>
                <?php foreach($articles as $article) : ?>
                    <tr>
                        <td>
                            <a class="text-decoration-none" href="edit-article.php?id=<?= $article['id']; ?>">
                                <?= $article['title']; ?>
                            </a>
                        </td>

                        <?php
                        $datePublishedObj = DateTime::createFromFormat('Y-m-d H:i:s', $article['published']);
                        $formattedPublishedDate = $datePublishedObj->format('M j, Y @ ga')
                        ?>
                        <td>
                            <?= $formattedPublishedDate ?>
                        </td>
                        <?php
                        $today = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                        $dateModifiedObj = DateTime::createFromFormat('Y-m-d H:i:s', $article['modified']);
                        $dateInterval = $dateModifiedObj->diff($today);
                        $interval = $dateInterval->format("%a");
                        if((int)$interval === 0) {
                            $interval_text = 'Today';
                        }
                        else if((int)$interval === 1) {
                            $interval_text = 'Yesterday';
                        }
                        else {
                            $interval_text = $interval . ' days ago';
                        }
                        ?>
                        <td><?= $interval_text ?></td>
                        <td>
                            <a href="/<?= $article['slug'] ?>" target="_blank"><?= $article['slug']; ?></a>
                            
                        </td>
                        <td>
                            <?= $article['author']; ?>
                        </td>
                    </tr>
                    
                <?php endforeach; ?>
            </tbody>
            
        </table>

        <div class="py-2 px-2 my-2 bg-body-secondary d-flex justify-content-between align-items-center">
            <p class="mb-0">Showing <?= $args['page_no'] ?> of <?= $total_pages ?> pages</p>
            <ul class="page-nav d-flex list-unstyled mb-0">
                <li>
                    <a href="<?php echo $page_no > 1 ? 'articles.php?page_no=' . $page_no - 1 : '#' ?>" class="btn btn-outline-primary btn-sm me-2 <?php echo $page_no > 1 ? '' : 'disabled' ?>">Prev</a>
                </li>
                <li>
                    <a href="<?php echo $page_no < $total_pages ? 'articles.php?page_no=' . $page_no + 1 : '#' ?>" class="btn btn-outline-primary btn-sm <?php echo $page_no < $total_pages ? '' : 'disabled' ?>">Next</a>
                </li>
            </ul>
        </div>

    </div>
    
</div>
</div>

<?php

get_template('footer');