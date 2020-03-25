<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

/**
 * Class ClientServerDatabaseConnector
 * @package Coco\SourceWatcher\Core\Database\Connections
 */
abstract class ClientServerDatabaseConnector extends Connector
{
    /**
     * @var string
     */
    protected string $host;

    /**
     * @var int
     */
    protected int $port;

    /**
     * @var string
     */
    protected string $dbName;

    /**
     * @return string
     */
    public function getHost () : string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost ( string $host ) : void
    {
        $this->host = $host;
    }

    /**
     * @return int
     */
    public function getPort () : int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort ( int $port ) : void
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getDbName () : string
    {
        return $this->dbName;
    }

    /**
     * @param string $dbName
     */
    public function setDbName ( string $dbName ) : void
    {
        $this->dbName = $dbName;
    }
}
