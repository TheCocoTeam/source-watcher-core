<?php

namespace Coco\SourceWatcher\Core\IO\Outputs;

use Coco\SourceWatcher\Core\Database\Connections\Connector;

/**
 * Class DatabaseOutput
 * @package Coco\SourceWatcher\Core\IO\Outputs
 */
class DatabaseOutput extends Output
{
    private ?Connector $databaseConnector = null;

    public function __construct ( Connector $databaseConnector )
    {
        $this->databaseConnector = $databaseConnector;
    }

    public function getOutput ()
    {
        return $this->databaseConnector;
    }

    public function setOutput ( $output )
    {
        $this->databaseConnector = $output;
    }
}
