<?php

include __DIR__ . '/../inc/databaseClass.php';
include __DIR__ . '/../inc/articleClass.php';

use function DI\create;

return [
    'database' => create(Database::class),
    'article' => create(Article::class),
];