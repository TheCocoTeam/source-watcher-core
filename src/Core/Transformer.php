<?php

namespace Coco\SourceWatcher\Core;

/**
 * Class Transformer
 * @package Coco\SourceWatcher\Core
 */
abstract class Transformer extends Step
{
    /**
     * @param Row $row
     * @return mixed
     */
    abstract public function transform ( Row $row );
}
