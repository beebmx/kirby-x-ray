<?php

use Beebmx\KirbyXRay\Actions\MakeXRayArea;
use Kirby\Cms\App;

return [
    'x-ray' => fn (App $kirby) => (new MakeXRayArea)($kirby),
];
