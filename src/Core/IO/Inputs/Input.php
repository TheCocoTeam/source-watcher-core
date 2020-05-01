<?php

namespace Coco\SourceWatcher\Core\IO\Inputs;

abstract class Input
{
    public abstract function getInput ();

    public abstract function setInput ( $input );
}
