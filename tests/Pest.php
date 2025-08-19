<?php

use Kirby\Cms\App;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function App(array $roots = [], array $options = []): App
{
    App::$enableWhoops = false;

    return new App([
        'roots' => array_merge([
            'index' => '/dev/null',
            'base' => $base = dirname(__DIR__).'/tests/Fixtures',
            'site' => $base.'/site',
            'content' => $base.'/content',
        ], $roots),
        'options' => array_merge([
            'beebmx.kirby-summary' => require dirname(__DIR__).'/extensions/options.php',
        ], $options),
        'areas' => require dirname(__DIR__).'/extensions/areas.php',
    ]);
}
