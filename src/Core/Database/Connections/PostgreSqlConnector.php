<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

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
     * @return string
     */
    public function getDefaultDatabaseName () : string
    {
        return $this->defaultDatabaseName;
    }

    /**
     * @param string $defaultDatabaseName
     */
    public function setDefaultDatabaseName ( string $defaultDatabaseName ) : void
    {
        $this->defaultDatabaseName = $defaultDatabaseName;
    }

    /**
     * @return string
     */
    public function getSslMode () : string
    {
        return $this->sslMode;
    }

    /**
     * @param string $sslMode
     */
    public function setSslMode ( string $sslMode ) : void
    {
        $this->sslMode = $sslMode;
    }

    /**
     * @return string
     */
    public function getSslRootCert () : string
    {
        return $this->sslRootCert;
    }

    /**
     * @param string $sslRootCert
     */
    public function setSslRootCert ( string $sslRootCert ) : void
    {
        $this->sslRootCert = $sslRootCert;
    }

    /**
     * @return string
     */
    public function getSslCert () : string
    {
        return $this->sslCert;
    }

    /**
     * @param string $sslCert
     */
    public function setSslCert ( string $sslCert ) : void
    {
        $this->sslCert = $sslCert;
    }

    /**
     * @return string
     */
    public function getSslKey () : string
    {
        return $this->sslKey;
    }

    /**
     * @param string $sslKey
     */
    public function setSslKey ( string $sslKey ) : void
    {
        $this->sslKey = $sslKey;
    }

    /**
     * @return string
     */
    public function getSslCrl () : string
    {
        return $this->sslCrl;
    }

    /**
     * @param string $sslCrl
     */
    public function setSslCrl ( string $sslCrl ) : void
    {
        $this->sslCrl = $sslCrl;
    }

    /**
     * @return string
     */
    public function getApplicationName () : string
    {
        return $this->applicationName;
    }

    /**
     * @param string $applicationName
     */
    public function setApplicationName ( string $applicationName ) : void
    {
        $this->applicationName = $applicationName;
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

        if ( isset( $this->charset ) && $this->charset !== "" ) {
            $this->connectionParameters["charset"] = $this->charset;
        }

        if ( isset( $this->defaultDatabaseName ) && $this->defaultDatabaseName !== "" ) {
            $this->connectionParameters["default_dbname"] = $this->defaultDatabaseName;
        }

        if ( isset( $this->sslMode ) && $this->sslMode !== "" ) {
            $this->connectionParameters["sslmode"] = $this->sslMode;
        }

        if ( isset( $this->sslRootCert ) && $this->sslRootCert !== "" ) {
            $this->connectionParameters["sslrootcert"] = $this->sslRootCert;
        }

        if ( isset( $this->sslCert ) && $this->sslCert !== "" ) {
            $this->connectionParameters["sslcert"] = $this->sslCert;
        }

        if ( isset( $this->sslKey ) && $this->sslKey !== "" ) {
            $this->connectionParameters["sslkey"] = $this->sslKey;
        }

        if ( isset( $this->sslCrl ) && $this->sslCrl !== "" ) {
            $this->connectionParameters["sslcrl"] = $this->sslCrl;
        }

        if ( isset( $this->applicationName ) && $this->applicationName !== "" ) {
            $this->connectionParameters["application_name"] = $this->applicationName;
        }

        return $this->connectionParameters;
    }
}
