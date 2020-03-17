<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Exception;

/**
 * Class PostgreSqlConnector
 * @package Coco\SourceWatcher\Core\Database\Connections
 *
 * Following the definition from https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#pdo-pgsql
 */
class PostgreSqlConnector extends Connector
{
    protected string $charset;
    protected string $defaultDatabaseName;
    protected string $sslMode;
    protected string $sslRootCert;
    protected string $sslCert;
    protected string $sslKey;
    protected string $sslCrl;
    protected string $applicationName;

    public function __construct ()
    {
        $this->driver = "pdo_pgsql";
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
