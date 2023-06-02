<?php

/**
 * Template for rendering the list of articles on the blog home page
 * Requires an array of article items as prop
 */

 ?>
<section class="blog-hero position-relative d-flex flex-column align-items-center justify-content-center">
    <div class="overlay"></div>
    <div class="container position-relative z-1 text-center text-white">
        <h1 class="fs-1 text-white pb-8"><?= esc_html($props['page_title']) ?></h1>
        <p>Read latest articles</p>
    </div>
</section>
 <main class="container pt-4">
    <?php if(!empty($props['articles'])) : ?>
        <div class="row row-cols-3 g-2 align-items-stretch">
        <?php foreach ($props['articles'] as $article) : ?>
            <div class="col">
                <article class="card h-100">
                    <a href="/<?= esc_html($article['slug']) ?>" class="text-decoration-none">
                        <img src="<?= esc_html($article['featured']['file_path']) ?>" alt="" class="card-img-top">
                    </a>
                    
    
                    <div class="card-body">
                        <p class="card-text">
                            Published on <?= esc_html($article['published']) ?>
                        </p>
                        <h2 class="card-title fs-5">
                            <a href="/<?= esc_html($article['slug']) ?>" class="text-decoration-none">
                                <?= esc_html($article['title']) ?>
                            </a>
                        </h2>
                        <p class="card-text">
                            <?= esc_html($article['excerpt']) ?>
                        </p>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
        </div>
        <div class="py-2 px-2 my-2 bg-body-secondary d-flex justify-content-between align-items-center">
            <p class="mb-0">Showing <?= $props['page_no'] ?> of <?= $props['total_pages'] ?> pages</p>
            <ul class="page-nav d-flex list-unstyled mb-0">
                <li>
                    <a href="<?php 
                    echo $props['page_no'] < 2 ? '#' : (($props['page_no'] === 2) ? '/' : '/page/' . $props['page_no'] - 1) ?>" class="btn btn-sm btn-outline-primary me-2 <?php echo $props['page_no'] > 1 ? '' : 'disabled' ?>">Prev</a>
                </li>
                <li>
                    <a href="<?php echo $props['page_no'] < $props['total_pages'] ? '/page/' . $props['page_no'] + 1 : '#' ?>" class="btn btn-sm btn-outline-primary <?php echo $props['page_no'] < $props['total_pages'] ? '' : 'disabled' ?>">Next</a>
                </li>
            </ul>
        </div>
    <?php endif; ?>
 </main>