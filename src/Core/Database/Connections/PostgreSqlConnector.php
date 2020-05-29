<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;

/**
 * Class PostgreSqlConnector
 * @package Coco\SourceWatcher\Core\Database\Connections
 *
 * Following the definition from: https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#pdo-pgsql
 */
class PostgreSqlConnector extends ClientServerDatabaseConnector
{
    /**
     * @var string
     */
    protected string $charset = "";

    /**
     * @var string
     */
    protected string $defaultDatabaseName = "";

    /**
     * @var string
     */
    protected string $sslMode = "";

    /**
     * @var string
     */
    protected string $sslRootCert = "";

    /**
     * @var string
     */
    protected string $sslCert = "";

    /**
     * @var string
     */
    protected string $sslKey = "";

    /**
     * @var string
     */
    protected string $sslCrl = "";

    /**
     * @var string
     */
    protected string $applicationName = "";

    /**
     * PostgreSqlConnector constructor.
     */
    public function __construct ()
    {
        $this->driver = "pdo_pgsql";

        $this->port = 5432;
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

        $this->connectionParameters["charset"] = $this->charset;
        $this->connectionParameters["default_dbname"] = $this->defaultDatabaseName;
        $this->connectionParameters["sslmode"] = $this->sslMode;
        $this->connectionParameters["sslrootcert"] = $this->sslRootCert;
        $this->connectionParameters["sslcert"] = $this->sslCert;
        $this->connectionParameters["sslkey"] = $this->sslKey;
        $this->connectionParameters["sslcrl"] = $this->sslCrl;
        $this->connectionParameters["application_name"] = $this->applicationName;

        return $this->connectionParameters;
    }
}
