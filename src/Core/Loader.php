<?php

namespace Coco\SourceWatcher\Core;

/**
 * Class Loader
 * @package Coco\SourceWatcher\Core
 */
abstract class Loader extends Step
{
    /**
     * @param Row $row
     * @return mixed
     */
    abstract public function load ( Row $row );
}
