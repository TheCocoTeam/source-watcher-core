<?php

namespace Coco\SourceWatcher\Core;

use Coco\SourceWatcher\Core\Inputs\Input;

/**
 * Class Extractor
 * @package Coco\SourceWatcher\Core
 */
abstract class Extractor extends Step
{
    /**
     * @var Input|null
     */
    protected ?Input $input = null;

    /**
     * @return Input
     */
    public function getInput () : Input
    {
        return $this->input;
    }

    /**
     * @param Input $input
     */
    public function setInput ( Input $input ) : void
    {
        $this->input = $input;
    }

    /**
     * @return mixed
     */
    public abstract function extract ();
}
