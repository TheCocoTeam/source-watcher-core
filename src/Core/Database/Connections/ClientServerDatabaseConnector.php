<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;
use Doctrine\DBAL\DBALException;

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

    /**
     * @param Row $row
     * @return int
     * @throws SourceWatcherException
     */
    public function insert ( Row $row ) : int
    {
        if ( $this->tableName == null || $this->tableName == "" ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( ClientServerDatabaseConnector::class, "No_Table_Name_Found" ) );
        }

        $connection = $this->connect();

        if ( !$connection->isConnected() ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( ClientServerDatabaseConnector::class, "Connection_Object_Not_Connected_Cannot_Insert" ) );
        }

        try {
            $numberOfAffectedRows = $connection->insert( $this->tableName, $row->getAttributes() );

            $connection->close();
        } catch ( DBALException $dbalException ) {
            $errorMessage = sprintf( "Something went wrong while trying to insert the row: %s", $dbalException->getMessage() );
            throw new SourceWatcherException( $errorMessage );
        }

        return $numberOfAffectedRows;
    }
}
