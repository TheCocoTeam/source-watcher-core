<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

/**
 * Class MySqlConnector
 *
 * @package Coco\SourceWatcher\Core\Database\Connections
 *
 * Following the definition from:
 *     https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#pdo-mysql
 */
class MySqlConnector extends ClientServerDatabaseConnector
{
    protected string $unixSocket = "";

    protected string $charset = "";

    public function __construct ()
    {
        $this->driver = "pdo_mysql";

        $this->port = 3306;
    }

    public function getUnixSocket () : string
    {
        return $this->unixSocket;
    }

    public function setUnixSocket ( string $unixSocket ) : void
    {
        $this->unixSocket = $unixSocket;
    }

    public function getCharset () : string
    {
        return $this->charset;
    }

    public function setCharset ( string $charset ) : void
    {
        $this->charset = $charset;
    }

    public function getConnectionParameters () : array
    {
        $this->extraParameters = [ "unix_socket" => $this->unixSocket, "charset" => $this->charset ];

        return parent::getConnectionParameters();
    }
}
