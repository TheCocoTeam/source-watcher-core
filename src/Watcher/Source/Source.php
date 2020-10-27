<?php

namespace Coco\SourceWatcher\Watcher\Source;

use Coco\SourceWatcher\Watcher\Handler\Handler;

/**
 * Interface Source
 *
 * @package Coco\SourceWatcher\Watcher\Source
 */
interface Source
{
    public function getHandler () : Handler;

    public function setHandler ( Handler $handler ) : void;
}
