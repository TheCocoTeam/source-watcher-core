<?php

namespace Coco\SourceWatcher\Watcher\Source;

use Coco\SourceWatcher\Watcher\Handler\Handler;

interface Source
{
    public function getHandler () : Handler;

    public function setHandler ( Handler $handler ) : void;
}
