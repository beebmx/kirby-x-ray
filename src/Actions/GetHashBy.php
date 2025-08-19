<?php

namespace Beebmx\KirbyXRay\Actions;

use Kirby\Cms\Page;
use Kirby\Cms\Site;

class GetHashBy
{
    public function __invoke(Site|Page $page): string
    {
        return hash('sha256', $page->id() ? "page:{$page->id()}" : 'site');
    }
}
