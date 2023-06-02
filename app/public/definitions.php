<?php

use DI\Definition\FactoryDefinition;
use function DI\create;
use function DI\factory;

include __DIR__ . '/inc/databaseClass.php';
include __DIR__ . '/inc/articleClass.php';
include __DIR__ . '/inc/imageClass.php';
include __DIR__ . '/inc/settingsClass.php';
include __DIR__ . '/handlers/MainHandler.php';
include __DIR__ . '/handlers/ArticleHandler.php';
include __DIR__ . '/inc/editorParserClass.php';

return [
    'database' => create(Database::class),
    'article' => create(Article::class),
    'image' => create(Image::class),
    'settings' => create(Settings::class),
    'mainHandler' => create(MainHandler::class),
    'articleHandler' => create(ArticleHandler::class),
    'editorParser' => create(EditorParser::class),

    'HTMLPurifier' => factory(function () {
            $config = HTMLPurifier_Config::createDefault();
            return new HTMLPurifier($config);
        }),
];