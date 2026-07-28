<?php

namespace Coco\SourceWatcher\Core\IO\Inputs;

/**
 * Input containing the pipeline's current result set.
 */
class ResultSetInput extends Input
{
    private array $rows;

    public function __construct ( array $rows = [] )
    {
        $this->rows = $rows;
    }

    public function getInput () : array
    {
        return $this->rows;
    }

    public function setInput ( $input ) : void
    {
        $this->rows = is_array( $input ) ? $input : [];
    }
}
