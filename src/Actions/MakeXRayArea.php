<?php

namespace Beebmx\KirbyXRay\Actions;

use Kirby\Cms\App;
use Kirby\Cms\Page;

class MakeXRayArea
{
    public function __invoke(App $kirby): array
    {
        return [
            'buttons' => [
                'x-ray' => fn (?Page $page) => new RenderXRayButton(
                    page: $page,
                    disabled: ! $kirby->user()->role()->permissions()->for('access', 'x-ray', true)
                ),
            ],
            'icon' => self::getIcon($kirby),
            'label' => self::getLabel($kirby),
            'link' => $this->getRoutePrefix(),
            'menu' => $this->getMenuVisibility(),
            'views' => (new MakeXRayView)($kirby, self::getRoutePrefix()),
        ];
    }

    public static function getRoutePrefix(): string
    {
        return 'x-ray';
    }

    public static function getLabel(App $kirby): string
    {
        return $kirby->option('beebmx.x-ray.title', 'X Ray');
    }

    public static function getIcon(App $kirby): string
    {
        return $kirby->option('beebmx.x-ray.icon', 'x-ray-icon');
    }

    protected function getMenuVisibility(): bool
    {
        return true;
    }
}
