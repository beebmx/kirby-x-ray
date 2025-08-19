<?php

use Beebmx\KirbyXRay\Actions\RequestXRayContent;
use Kirby\Cms\App;

beforeEach(function () {
    App();
});

it('can request a specific page x-ray content')
    ->expect(fn () => (new RequestXRayContent)(
        App::instance()->page('hidden/nested')
    ))->toHaveKeys(['page', 'pages', 'files', 'breadcrumb'])
    ->toHaveKey('page.title', 'Nested page');

test('x-ray returns requested page')
    ->expect(fn () => (new RequestXRayContent)(App::instance()->page('hidden/nested'))['page'])
    ->toHaveKey('id', 'hidden/nested')
    ->toHaveKey('slug', 'nested')
    ->toHaveKey('uid', 'nested')
    ->toHaveKey('url', '/hidden/nested');

it('returns page x-ray content')
    ->expect(fn () => (new RequestXRayContent)(
        App::instance()->page('hidden/nested')
    )['page'])->toHaveKeys([
        'files', 'id', 'nice', 'pages', 'size', 'slug', 'status', 'title', 'label', 'icon', 'types', 'uid', 'url', 'panel', 'type',
    ]);

it('can request a specific page by id ')
    ->expect(fn () => (new RequestXRayContent)('hidden/nested'))
    ->toHaveKeys(['page', 'pages', 'files', 'breadcrumb'])
    ->toHaveKey('page.title', 'Nested page');

it('returns site content if page doesnt exists')
    ->expect(fn () => (new RequestXRayContent)('invalid/page'))
    ->toHaveKey('page.title', 'Site Title');

test('page breadcrumb should not be empty')
    ->expect(fn () => (new RequestXRayContent)('hidden/nested')['breadcrumb'])
    ->toBeArray()
    ->not->toBeEmpty();

test('page breadcrumb should be generated')
    ->expect(fn () => (new RequestXRayContent)('hidden/nested')['breadcrumb'])
    ->toBeArray()
    ->toHaveCount(2)
    ->toHaveKey('0.label', 'Hidden page')
    ->toHaveKey('1.label', 'Nested page')
    ->toHaveKey('1.link', 'x-ray/hidden/nested');
