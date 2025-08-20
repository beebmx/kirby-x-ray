<?php

use Kirby\Cms\App as Kirby;

@include_once __DIR__.'/vendor/autoload.php';

Kirby::plugin('beebmx/x-ray', [
    'api' => [
        'routes' => require_once __DIR__.'/extensions/apiRoutes.php',
    ],
    'areas' => require_once __DIR__.'/extensions/areas.php',
    'hooks' => require_once __DIR__.'/extensions/hooks.php',
    'options' => require_once __DIR__.'/extensions/options.php',
    'translations' => require_once __DIR__.'/extensions/translations.php',
]);
