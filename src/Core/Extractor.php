<?php

namespace Coco\SourceWatcher\Core;

use Coco\SourceWatcher\Core\IO\Inputs\Input;

abstract class Extractor extends Step
{
    protected ?Input $input = null;

    public function getInput () : Input
    {
        return $this->input;
    }

    public function setInput ( ?Input $input ) : void
    {
        $this->input = $input;
    }

    public abstract function extract ();
}
