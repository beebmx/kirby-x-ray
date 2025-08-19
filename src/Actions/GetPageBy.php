<?php

namespace Beebmx\KirbyXRay\Actions;

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Site;

class GetPageBy
{
    protected App $kirby;

    public function __construct()
    {
        $this->kirby = App::instance();
    }

    public function __invoke(Site|Page|string|null $page = null): Site|Page
    {
        if (is_string($page) && $page !== '') {
            $page = $this->kirby->page($page);
        }

        if (is_null($page) || $page === '') {
            $page = $this->kirby->site();
        }

        return $page;
    }
}
