<?php

namespace Coco\SourceWatcher\Core\IO\Outputs;

/**
 * Class Output
 *
 * @package Coco\SourceWatcher\Core\IO\Outputs
 */
abstract class Output
{
    public abstract function getOutput ();

    public abstract function setOutput ( $output );
}
