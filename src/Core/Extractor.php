<?php

namespace Coco\SourceWatcher\Core;

abstract class Extractor extends Step
{
    protected $input;

    /**
     * @return mixed
     */
    public function getInput ()
    {
        return $this->input;
    }

    /**
     * @param mixed $input
     */
    public function setInput ( $input ) : void
    {
        $this->input = $input;
    }

    public abstract function extract ();
}
