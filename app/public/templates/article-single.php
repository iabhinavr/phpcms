<?php

/**
 * Template for rendering the single article page
 * Requires the article data as a prop
 */

 ?>

 <main>
    <?php if(!empty($props['article'])) : ?>

        <article class="post-content">
            <section class="h-[50vh] min-h-[20rem] relative flex flex-col items-center justify-center bg-cover bg-no-repeat bg-center" style="background-image: url('<?= $props['article']['featured']['file_path'] ?>')">
                <div class="absolute bg-slate-900 inset-0 z-0 opacity-40"></div>
                <div class="container mx-auto lg:max-w-3xl">
                    <h1 class="text-6xl text-left text-slate-100 relative z-10 pb-8"><?= $props['article']['title'] ?></h1>
                    <div class="pb-4 text-slate-100 z-10 relative text-sm">
                        Posted by <?= $props['article']['author'] ?>, Last updated on <?= $props['article']['modified'] ?>
                    </div>
                    <div class="relative z-10 text-left text-slate-200 text-2xl pl-4 border-l-4 border-lime-200">
                        <?php echo($props['article']['excerpt']) ?>
                    </div>
                </div>
            </section>
            <section class="content-area py-8 container mx-auto lg:max-w-3xl">
                <div class="post-content container lg:max-w-4xl mx-auto">
                    <?php echo($props['article']['html']) ?>
                </div>
            </section>
        </article>
        
    <?php endif; ?>
 </main>