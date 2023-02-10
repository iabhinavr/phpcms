<?php

include('inc/functions.php');

get_template('header');
get_template('topbar');

require_once '../../inc/databaseClass.php';
require_once '../../inc/articleClass.php';

$database = new Database();
$db_con = $database->db_connect();

$article = new Article($db_con);

$articles = $article->get_articles();


?>

<div class="grid grid-cols-[200px_1fr_200px] top-12 relative">

    <?php get_template('sidebar'); ?>
    <div class="px-4 py-3">
        <h1 class="text-2xl">Articles</h1>

        <ul>
            <?php foreach($articles as $a) : ?>
                <li>
                    <a class=" underline text-blue-500" href="edit-article.php?id=<?= $a['id'] ?>"><?= $a['title'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
</div>