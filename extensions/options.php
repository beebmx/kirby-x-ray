<?php

use Beebmx\KirbyXRay\Enums\FilterType;

return [
    'autoclean' => [
        'files' => true,
        'pages' => true,
    ],
    'cache' => true,
    'icon' => 'x-ray-icon',
    'limit' => [
        'overview' => 5,
        'resource' => 10,
    ],
    'title' => 'X Ray',
    'overview' => FilterType::Page,
];
