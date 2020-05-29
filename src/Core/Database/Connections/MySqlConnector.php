<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;

/**
 * Class MySqlConnector
 * @package Coco\SourceWatcher\Core\Database\Connections
 *
 * Following the definition from: https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#pdo-mysql
 */
class MySqlConnector extends ClientServerDatabaseConnector
{
    /**
     * @var string
     */
    protected string $unixSocket = "";

    /**
     * @var string
     */
    protected string $charset = "";

    /**
     * MySqlConnector constructor.
     */
    public function __construct ()
    {
        $this->driver = "pdo_mysql";

        $this->port = 3306;
    }

    /**
     * @return string
     */
    public function getUnixSocket () : string
    {
        return $this->unixSocket;
    }

    /**
     * @param string $unixSocket
     */
    public function setUnixSocket ( string $unixSocket ) : void
    {
        $this->unixSocket = $unixSocket;
    }

    /**
     * @return string
     */
    public function getCharset () : string
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     */
    public function setCharset ( string $charset ) : void
    {
        $this->charset = $charset;
    }

    /**
     * @return Connection
     * @throws SourceWatcherException
     */
    public function connect () : Connection
    {
        try {
            return DriverManager::getConnection( $this->getConnectionParameters() );
        } catch ( DBALException $dbalException ) {
            throw new SourceWatcherException( "Something went wrong trying to get a connection: " . $dbalException->getMessage() );
        }
    }

    /**
     * @return array
     */
    protected function getConnectionParameters () : array
    {
        $this->connectionParameters = array();

        $this->connectionParameters["driver"] = $this->driver;
        $this->connectionParameters["user"] = $this->user;
        $this->connectionParameters["password"] = $this->password;
        $this->connectionParameters["host"] = $this->host;
        $this->connectionParameters["port"] = $this->port;
        $this->connectionParameters["dbname"] = $this->dbName;

        if ( isset( $this->unixSocket ) && $this->unixSocket !== "" ) {
            $this->connectionParameters["unix_socket"] = $this->unixSocket;
        }

        if ( isset( $this->charset ) && $this->charset !== "" ) {
            $this->connectionParameters["charset"] = $this->charset;
        }

        return $this->connectionParameters;
    }
}
