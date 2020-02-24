<?php

namespace Coco\SourceWatcher\Source;

use Coco\SourceWatcher\Handler\Handler;

interface Source {
    public function getHandler () : Handler;

    public function setHandler ( Handler $handler ) : void;
}
