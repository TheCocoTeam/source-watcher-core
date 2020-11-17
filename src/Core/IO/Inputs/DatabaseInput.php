<?php

namespace Coco\SourceWatcher\Core\IO\Inputs;

use Coco\SourceWatcher\Core\Database\Connections\Connector;

/**
 * Class DatabaseInput
 *
 * @package Coco\SourceWatcher\Core\IO\Inputs
 */
class DatabaseInput extends Input
{
    private ?Connector $databaseConnector = null;

    public function __construct ( Connector $databaseConnector = null )
    {
        $this->databaseConnector = $databaseConnector;
    }

    public function getInput ()
    {
        return $this->databaseConnector;
    }

    public function setInput ( $input )
    {
        $this->databaseConnector = $input;
    }
}
