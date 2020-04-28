<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

use Coco\SourceWatcher\Core\Row;
use Doctrine\DBAL\Connection;

/**
 * Class Connector
 * @package Coco\SourceWatcher\Core\Database\Connections
 */
abstract class Connector
{
    /**
     * @var string
     */
    protected string $driver = "";

    /**
     * @var array
     */
    protected array $connectionParameters = [];

    /**
     * @var string
     */
    protected string $user = "";

    /**
     * @var string
     */
    protected string $password = "";

    /**
     * @var string
     */
    protected string $tableName = "";

    /**
     * @return Connection
     */
    public abstract function connect () : Connection;

    /**
     * @param Row $row
     * @return int
     */
    public abstract function insert ( Row $row ) : int;

    /**
     * @return string
     */
    public function getDriver () : string
    {
        return $this->driver;
    }

    /**
     * @return array
     */
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
