<?php

/**
 * Template for rendering the list of articles on the blog home page
 * Requires an array of article items as prop
 */

 ?>

 <main>
    <?php if(!empty($props['articles'])) : ?>
        <?php foreach ($props['articles'] as $article) : ?>
            <h2><?= $article['title'] ?></h2>
            <p><?php echo($article['content']) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
 </main>