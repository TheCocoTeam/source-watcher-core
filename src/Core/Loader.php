<?php

namespace Coco\SourceWatcher\Core;

abstract class Loader extends Step
{
    abstract public function load ( Row $row );
}
