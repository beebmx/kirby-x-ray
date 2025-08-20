<?php

namespace Beebmx\KirbyXRay\Actions;

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Panel\Ui\Buttons\ViewButton;

class RenderXRayButton extends ViewButton
{
    public function __construct(?Page $page, bool $disabled = false)
    {
        $kirby = App::instance();
        $uri = $page?->id() ? '/'.$page->id() : null;

        parent::__construct(
            disabled: $disabled,
            icon: MakeXRayArea::getIcon($kirby),
            link: MakeXRayArea::getRoutePrefix().$uri,
            text: $kirby->option('beebmx.x-ray.title', 'X Ray'),
        );
    }
}
