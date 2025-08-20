<?php

namespace Beebmx\KirbyXRay\Actions;

use Beebmx\KirbyXRay\Enums\FilterType;
use Kirby\Cms\App;

class MakeXRayView
{
    public function __invoke(App $kirby, string $routePrefix = 'x-ray'): array
    {
        return [
            [
                'pattern' => [$routePrefix, $routePrefix.'/(:all)'],
                'action' => function ($id = null) {
                    $kirby = App::instance();
                    $content = (new RequestXRayContent)($id);

                    return [
                        'component' => 'k-x-ray-view',
                        'title' => $kirby->option('beebmx.x-ray.title', 'X Ray'),
                        'props' => [
                            'cache' => $kirby->option('beebmx.x-ray.cache', true),
                            'overview' => $kirby->option('beebmx.x-ray.limit.overview', 5),
                            'resource' => $kirby->option('beebmx.x-ray.limit.resource', 10),
                            'view' => $kirby->option('beebmx.x-ray.overview', FilterType::Page)?->value ?: 'page',
                            ...$content,
                        ],

                        'breadcrumb' => fn () => [
                            ...$content['breadcrumb'],
                        ],
                    ];
                },
            ],
        ];
    }
}
