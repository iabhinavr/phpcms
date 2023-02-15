<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $props['page_title'] ?></title>
    <meta name="description" content="<?= $props['description'] ?>">
    <link rel="stylesheet" href="/assets/style.bundle.css">
</head>
<body>
    <header class=" bg-slate-800">
        <div class="container mx-auto lg:max-w-3xl flex items-stretch">
            <div class="logo-area">
                <h1 class="text-white p-2 font-bold text-xl">PHPCMS</h1>
            </div>
            <nav class="ml-auto flex items-stretch">
                <ul class="flex items-stretch [&>li>a]:h-full [&>li>a]:text-white [&>li>a]:px-3 [&>li>a:hover]:bg-slate-700 [&>li]:flex [&>li]:items-stretch [&>li>a]:flex [&>li>a]:items-center">
                    <li>
                        <a href="/">Articles</a>
                    </li>
                    <li>
                        <a href="/about">About</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    
