<div class="absolute w-full h-12 bg-slate-700 z-10 text-slate-100">
    <ul class="flex items-center h-full">
        <li>
            <h1 class="pl-3 text-lg font-bold">Dashboard - PHPCMS</h1>
        </li>
        <li class="ml-auto">
            <ul class="flex text-sm">
                <li class="mr-2">Hello, <?= $_SESSION['first_name'] ?></li>
                <li class="mr-2">
                    <a href="/" class="px-2 py-1 rounded-sm border border-emerald-600 hover:bg-emerald-600/75">Visit Site</a>
                </li>
                <li class="mr-2">
                    <a href="logout.php" class="px-2 py-1 rounded-sm border border-red-600 hover:bg-red-600/75">Logout →</a>
                </li>
            </ul>    
        </li>
    </ul>
</div>