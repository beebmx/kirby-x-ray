<?php

arch('globals')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

arch()
    ->expect('Beebmx\KirbyEnum\Exceptions')
    ->toHaveSuffix('Exception');

arch('app')
    ->expect('App\Actions')
    ->toBeInvokable();
