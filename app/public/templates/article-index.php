<?php

/**
 * Template for rendering the list of articles on the blog home page
 * Requires an array of article items as prop
 */

 ?>
<section class="h-[50vh] min-h-[20rem] bg-[url('/assets/images/bg-2.jpg')] relative flex flex-col items-center justify-center bg-cover bg-no-repeat bg-center">
    <div class="absolute bg-slate-900 inset-0 z-0 opacity-40"></div>
    <div class="container mx-auto lg:max-w-3xl">
        <h1 class="text-6xl text-center text-slate-100 relative z-10 pb-8">BLOG</h1>
        <p class="relative z-10 text-center text-slate-200">Read latest articles</p>
    </div>
</section>
 <main class="container mx-auto lg:max-w-3xl my-8">
    <?php if(!empty($props['articles'])) : ?>
        <?php foreach ($props['articles'] as $article) : ?>
            <article class="grid grid-cols-5 gap-4 mb-4">
                <div class="col-span-2">
                    <a href="/<?= $article['slug'] ?>">
                        <img src="<?= $article['featured']['file_path'] ?>" alt="" class="h-full object-cover rounded-md">
                    </a>
                </div>
                <div class="col-span-3">
                    <h2 class="pb-2">
                        <a href="/<?= $article['slug'] ?>" class="text-blue-400 text-2xl hover:text-blue-600">
                            <?= $article['title'] ?>
                        </a>
                    </h2>
                    <div class="py-2 text-sm">
                        Published on <?= $article['published'] ?>
                    </div>
                    <div class="text-lg">
                        <?= $article['excerpt'] ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
        <div class="p-2 my-2 bg-slate-200/50 flex justify-between items-center">
            <p class="text-sm italic">Showing <?= $props['page_no'] ?> of <?= $props['total_pages'] ?> pages</p>
            <ul class="page-nav flex">
                <li>
                    <a href="<?php 
                    echo $props['page_no'] < 2 ? '#' : (($props['page_no'] === 2) ? '/' : '/page/' . $props['page_no'] - 1) ?>" class=" text-xs px-2 py-1 mr-1 block rounded-md <?php echo $props['page_no'] > 1 ? 'bg-slate-300' : 'bg-slate-200 pointer-events-none' ?>">Prev</a>
                </li>
                <li>
                    <a href="<?php echo $props['page_no'] < $props['total_pages'] ? '/page/' . $props['page_no'] + 1 : '#' ?>" class="text-xs px-2 py-1 block rounded-md <?php echo $props['page_no'] < $props['total_pages'] ? 'bg-slate-300' : 'bg-slate-200 pointer-events-none' ?>">Next</a>
                </li>
            </ul>
        </div>
    <?php endif; ?>
 </main>