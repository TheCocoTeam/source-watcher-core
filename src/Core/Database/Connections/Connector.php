<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

use Doctrine\DBAL\Connection;

abstract class Connector
{
    protected string $driver;
    protected array $connectionParameters = [];

    protected string $user;
    protected string $password;
    protected string $host;
    protected int $port;
    protected string $dbName;

    public abstract function connect () : Connection;

    /**
     * @return string
     */
    public function getDriver () : string
    {
        return $this->driver;
    }

    protected abstract function getConnectionParameters () : array;

    /**
     * @return string
     */
    public function getUser () : string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser ( string $user ) : void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword () : string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword ( string $password ) : void
    {
        $this->password = $password;
    }

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
