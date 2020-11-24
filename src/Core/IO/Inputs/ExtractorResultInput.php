<?php

namespace Coco\SourceWatcher\Core\IO\Inputs;

use Coco\SourceWatcher\Core\Extractor;

/**
 * Class ExtractorResultInput
 *
 * @package Coco\SourceWatcher\Core\IO\Inputs
 */
class ExtractorResultInput extends Input
{
    private ?Extractor $previousExtractor;

    public function __construct ( Extractor $previousExtractor = null )
    {
        $this->previousExtractor = $previousExtractor;
    }

    public function getInput () : Extractor
    {
        return $this->previousExtractor;
    }

    public function setInput ( $input ) : void
    {
        $this->previousExtractor = $input;
    }
}
