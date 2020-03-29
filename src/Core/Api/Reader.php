<?php

namespace Coco\SourceWatcher\Core\Api;

/**
 * Interface Reader
 * @package Coco\SourceWatcher\Core\Api
 */
interface Reader
{
    /**
     * @return mixed
     */
    public function read ();
}
