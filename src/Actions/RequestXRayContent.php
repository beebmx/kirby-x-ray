<?php

namespace Beebmx\KirbyXRay\Actions;

use Kirby\Cms\App;
use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Cms\Site;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Filesystem\F;
use Kirby\Toolkit\I18n;

class RequestXRayContent
{
    protected App $kirby;

    public function __construct()
    {
        $this->kirby = App::instance();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(Site|Page|string|null $page = null): array
    {
        $page = (new GetPageBy)($page);
        $hash = (new GetHashBy)($page);

        if ($cache = $this->kirby->cache('beebmx.x-ray')->get($hash)) {
            return $cache;
        }

        $xray = $this->getXRay($page);
        $this->kirby->cache('beebmx.x-ray')->set(key: $hash, value: $xray);

        return $xray;
    }

    protected function getXRay(Site|Page $page): array
    {
        $children = $page->childrenAndDrafts()->map(fn (Page $children) => $this->getXRay($children))->values();
        $files = $page->files()->map(fn (File $file) => $this->getFile($file))->values();

        return [
            'breadcrumb' => $this->getBreadcrumb($page),
            'files' => $files,
            'page' => $this->getPage($page, $children, $files),
            'pages' => $children,
        ];
    }

    protected function getPage(Site|Page $page, array $children = [], array $files = []): array
    {
        return [
            'id' => $page->id(),
            'slug' => $page->slug(),
            'status' => $page instanceof Page ? $page->status() : null,
            'title' => $page->title()->value(),
            'uid' => $page->uid(),
            'label' => I18n::translate('page'),
            'icon' => 'page',
            'panel' => $page->panel()->url(true),
            'type' => 'page',
            'url' => $page->url(),
            'pages' => [
                'title' => I18n::translate('pages'),
                'label' => I18n::translate('pages'),
                'icon' => 'x-ray-pages',
                'count' => count($children),
                'size' => $children_size = $this->getTotalSizeOf($this->getPagesByChildren($children)),
                'nice' => F::niceSize($children_size),
            ],
            'files' => [
                'title' => I18n::translate('files'),
                'label' => I18n::translate('files'),
                'icon' => 'x-ray-files',
                'count' => count($files),
                'size' => $files_size = $this->getTotalSizeOf($files),
                'nice' => F::niceSize($files_size),
            ],
            'size' => $full_size = $children_size + $files_size,
            'nice' => F::niceSize($full_size),
            'types' => [
                'archive' => $this->getFileSizeBy('archive', $files),
                'audio' => $this->getFileSizeBy('audio', $files),
                'code' => $this->getFileSizeBy('code', $files),
                'document' => $this->getFileSizeBy('document', $files),
                'image' => $this->getFileSizeBy('image', $files),
                'video' => $this->getFileSizeBy('video', $files),
                'other' => $this->getFileSizeBy('other', $files),
            ],
        ];
    }

    protected function getFile(File $file): array
    {
        return [
            'extension' => $file->extension(),
            'filename' => $file->filename(),
            'id' => $file->id(),
            'mime' => $file->mime(),
            'nice' => $file->niceSize(),
            'size' => $file->size(),
            'title' => $file->filename(),
            'panel' => $file->panel()->url(true),
            'type' => $file->type() ?? 'other',
            'url' => $file->url(),
        ];
    }

    protected function getPagesByChildren(array $children): array
    {
        return ! empty($children)
        ? array_map(fn ($child) => $child['page'], $children)
        : [];
    }

    protected function getTotalSizeOf(array $resource = []): int
    {
        return array_reduce(
            $resource,
            fn ($sum, $item) => $sum + ($item['size'] ?? 0),
            initial: 0
        ) ?? 0;
    }

    protected function getFileSizeBy(string $type, array $files = []): int
    {
        return array_reduce(
            array_filter($files, fn ($file) => $file['type'] === $type),
            fn ($sum, $file) => $sum + $file['size'],
            initial: 0
        ) ?? 0;
    }

    protected function getBreadcrumb(Site|Page $page): array
    {
        if ($page instanceof Site) {
            return [];
        }

        $parents = $page->parents()->flip()->merge($page);

        return $parents->values(
            fn ($parent) => [
                'label' => $parent->title()->toString(),
                'link' => MakeXRayArea::getRoutePrefix().'/'.$parent->id(),
            ]
        );
    }
}
