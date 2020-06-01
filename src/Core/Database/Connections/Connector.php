<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;

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

    /**
     * @return Connection
     * @throws SourceWatcherException
     */
    public function getConnection () : Connection
    {
        try {
            return DriverManager::getConnection( $this->getConnectionParameters() );
        } catch ( DBALException $dbalException ) {
            throw new SourceWatcherException( "Something went wrong trying to get a connection: ", 0, $dbalException->getMessage() );
        }
    }

    /**
     * @param Row $row
     * @return int
     * @throws SourceWatcherException
     */
    public function insert ( Row $row ) : int
    {
        if ( $this->tableName == null || $this->tableName == "" ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( Connector::class, "No_Table_Name_Found" ) );
        }

        $connection = $this->getConnection();

        try {
            if ( !$connection->isConnected() ) {
                $connection->connect();
            }
        } catch ( DBALException $dbalException ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( Connector::class, "Connection_Object_Not_Connected_Cannot_Insert" ), 0, $dbalException );
        }

        try {
            $numberOfAffectedRows = $connection->insert( $this->tableName, $row->getAttributes() );

            $connection->close();
        } catch ( DBALException $dbalException ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( Connector::class, "Unexpected_Error" ), 0, $dbalException );
        }

        return $numberOfAffectedRows;
    }
}
