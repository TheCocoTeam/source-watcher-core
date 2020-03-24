<?php

namespace Coco\SourceWatcher\Core;

/**
 * Class Extractor
 * @package Coco\SourceWatcher\Core
 */
abstract class Extractor extends Step
{
    /**
     * @var mixed
     */
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

    /**
     * @return mixed
     */
    public abstract function extract ();
}
