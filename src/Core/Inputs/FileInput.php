<?php

namespace Coco\SourceWatcher\Core\Inputs;

/**
 * Class FileInput
 * @package Coco\SourceWatcher\Core\Inputs
 */
class FileInput extends Input
{
    /**
     * @var string
     */
    private string $fileLocation;

    /**
     * FileInput constructor.
     * @param string $fileLocation
     */
    public function __construct ( string $fileLocation )
    {
        $this->fileLocation = $fileLocation;
    }

    /**
     * @return string
     */
    public function getInput ()
    {
        return $this->fileLocation;
    }

    /**
     * @param string $input
     */
    public function setInput ( $input )
    {
        $this->fileLocation = $input;
    }
}
