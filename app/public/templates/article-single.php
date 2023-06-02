<?php

/**
 * Template for rendering the single article page
 * Requires the article data as a prop
 */

 ?>

 <main>
    <?php if(!empty($props['article'])) : ?>

        <article class="post-content">
            <section class="blog-hero position-relative d-flex flex-column align-items-center justify-content-center" style="background-image: url('<?= esc_html($props['article']['featured']['file_path']) ?>')">
                <div class="overlay"></div>
                <div class="container position-relative z-1 text-left text-white">
                    <h1 class="fs-1 pb-4"><?= esc_html($props['article']['title']) ?></h1>
                    <div class="pb-4">
                            Posted by <?= esc_html($props['article']['author']) ?>, Last updated on <?= esc_html($props['article']['modified']) ?>
                    </div>
                    <div class="fs-4 border-start border-4 border-warning ps-4">
                        <?= esc_html($props['article']['excerpt']) ?>
                    </div>
                </div>
                
            </section>
            <section class="content-area pt-4 container">
                <div class="post-content row justify-content-center">
                    <div class="col-md-8">
                        <?php echo($props['article']['html']) ?>
                    </div>
                </div>
            </section>
        </article>
        
    <?php endif; ?>
 </main>