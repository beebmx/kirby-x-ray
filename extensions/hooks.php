<?php

use Beebmx\KirbyXRay\Actions\ClearCache;
use Kirby\Cms\Event;
use Kirby\Cms\File;
use Kirby\Cms\Page;

return [
    'file.*:before' => function (Event $event, File $file) {
        if ($this->option('beebmx.kirby-x-ray.cache', true)
         && $this->option('beebmx.kirby-x-ray.autoclean.files', true)
         && in_array($event->action(), ['changeTemplate', 'changeSort']) !== true) {
            (new ClearCache)($this, $file->parent());
            (new ClearCache)($this, $this->site());
        }
    },
    'page.*:before' => function (Event $event, Page $page) {
        if ($this->option('beebmx.kirby-x-ray.cache', true)
         && $this->option('beebmx.kirby-x-ray.autoclean.pages', true)
         && in_array($event->action(), ['changeTemplate', 'changeNum', 'render']) !== true) {
            (new ClearCache)($this, $page);
            (new ClearCache)($this, $this->site());
        }
    },
];
