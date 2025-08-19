<?php

use Beebmx\KirbyXRay\Actions\ClearCache;
use Kirby\Cms\App;

return fn (App $kirby) => [
    [
        'pattern' => 'x-ray/cache/clear',
        'method' => 'POST',
        'action' => fn () => (new ClearCache)($kirby),
    ],
];
