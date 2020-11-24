<?php

namespace Coco\SourceWatcher\Core\IO\Inputs;

/**
 * Class Input
 *
 * @package Coco\SourceWatcher\Core\IO\Inputs
 */
abstract class Input
{
    /**
     * This function returns the input object which can be of any type.
     * @return mixed
     */
    public abstract function getInput ();

    /**
     * This function sets the input object which can be of any type.
     * @param $input
     * @return mixed
     */
    public abstract function setInput ( $input );
}
