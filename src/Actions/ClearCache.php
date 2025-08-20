<?php

namespace Beebmx\KirbyXRay\Actions;

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Site;
use Kirby\Exception\InvalidArgumentException;

class ClearCache
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(App $kirby, Site|Page|string|null $page = null): array
    {
        $page = (new GetPageBy)(
            $page ?? $kirby->request()->get('id')
        );

        $hash = (new GetHashBy)($page);

        return [
            'success' => $kirby->cache('beebmx.x-ray')->remove($hash),
        ];
    }
}
