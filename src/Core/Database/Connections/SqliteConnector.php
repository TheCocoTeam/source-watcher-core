<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Exception;

/**
 * Class SqliteConnector
 * @package Coco\SourceWatcher\Core\Database\Connections
 *
 * Following the definition from: https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#pdo-sqlite
 */
class SqliteConnector extends EmbeddedDatabaseConnector
{
    protected string $path;
    protected bool $memory;

    public function __construct ()
    {
        $this->driver = "pdo_sqlite";

        $this->memory = false;
    }

    /**
     * @return string
     */
    public function getPath () : string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath ( string $path ) : void
    {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function isMemory () : bool
    {
        return $this->memory;
    }

    /**
     * @param bool $memory
     */
    public function setMemory ( bool $memory ) : void
    {
        $this->memory = $memory;
    }

    public function connect () : Connection
    {
        try {
            return DriverManager::getConnection( $this->getConnectionParameters() );
        } catch ( DBALException $dbalException ) {
            throw new SourceWatcherException( "Something went wrong trying to get a connection: " . $dbalException->getMessage() );
        } catch ( Exception $exception ) {
            throw new SourceWatcherException( "Something unexpected went wrong trying to get a connection: " . $exception->getMessage() );
        }
    }

    protected function getConnectionParameters () : array
    {
        $this->connectionParameters = array();

        $this->connectionParameters["driver"] = $this->driver;
        $this->connectionParameters["user"] = $this->user;
        $this->connectionParameters["password"] = $this->password;

        if ( isset( $this->path ) && $this->path !== "" ) {
            $this->connectionParameters["path"] = $this->path;
        }

        $this->connectionParameters["memory"] = $this->memory;

        return $this->connectionParameters;
    }

    public function insert ( Row $row ) : void
    {
        if ( $this->tableName == null || $this->tableName == "" ) {
            throw new SourceWatcherException( "No table name found." );
        }

        $connection = $this->connect();

        $numberOfAffectedRows = $connection->insert( $this->tableName, $row->getAttributes() );
    }
}
