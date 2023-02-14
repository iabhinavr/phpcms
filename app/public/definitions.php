<?php

include __DIR__ . '/../inc/databaseClass.php';
include __DIR__ . '/../inc/articleClass.php';
include __DIR__ . '/handlers/MainHandler.php';
include __DIR__ . '/handlers/ArticleHandler.php';
include __DIR__ . '/../inc/editorParserClass.php';

use function DI\create;

return [
    'database' => create(Database::class),
    'article' => create(Article::class),
    'mainHandler' => create(MainHandler::class),
    'articleHandler' => create(ArticleHandler::class),
    'editorParser' => create(EditorParser::class),
];