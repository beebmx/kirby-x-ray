<?php

use Beebmx\KirbyXRay\Actions\RequestXRayContent;

beforeEach(function () {
    App();
    $this->xray = (new RequestXRayContent)();
});

it('can request all files')
    ->expect(fn () => $this->xray)
    ->toBeArray()
    ->not->toBeEmpty();

test('main path should contain summary files structure')
    ->expect(fn () => $this->xray)
    ->toHaveKeys(['page', 'pages', 'files', 'breadcrumb']);

test('nested paths should contain x-ray files structure')
    ->expect(fn () => $this->xray['pages'])
    ->each
    ->toHaveKeys(['page', 'pages', 'files', 'breadcrumb']);

test('a root page should contain basic page structure')
    ->expect(fn () => $this->xray['page'])
    ->toHaveKeys([
        'files', 'id', 'nice', 'pages', 'size', 'slug', 'status', 'title', 'label', 'icon', 'types', 'uid', 'url', 'panel', 'type',
    ]);

it('should load pages with drafts')
    ->expect(fn () => $this->xray['pages'])
    ->toHaveCount(6);

test('main children size can not be 0')
    ->expect(fn () => $this->xray['page']['pages']['size'])
    ->toBeGreaterThan(0);

test('main files size can not be 0')
    ->expect(fn () => $this->xray['page']['files']['size'])
    ->toBeGreaterThan(0);

test('main page contains file types')
    ->expect(fn () => $this->xray['page']['types'])
    ->toHaveKeys([
        'archive', 'audio', 'code', 'document', 'image', 'other', 'video',
    ]);

test('main page calculate file types sizes', function () {
    expect($this->xray['page']['types']['image'])
        ->toBeGreaterThan(222)
        ->and($this->xray['page']['types']['video'])
        ->toBeGreaterThan(0)
        ->and($this->xray['page']['types']['other'])
        ->toBeGreaterThan(0);
});

test('site breadcrumb should be empty')
    ->expect(fn () => $this->xray['breadcrumb'])
    ->toBeArray()
    ->toEqual([]);
