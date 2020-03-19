<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

use Coco\SourceWatcher\Core\Row;
use Doctrine\DBAL\Connection;

abstract class Connector
{
    protected string $driver = "";
    protected array $connectionParameters = [];

    protected string $user;
    protected string $password;
    protected string $host;
    protected int $port;
    protected string $dbName;

    protected string $tableName = "";

    public abstract function connect () : Connection;

    public abstract function insert ( Row $row ) : void;

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

    /**
     * @return string
     */
    public function getTableName () : string
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName ( string $tableName ) : void
    {
        $this->tableName = $tableName;
    }
}
