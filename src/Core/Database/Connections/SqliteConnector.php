<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

/**
 * Class SqliteConnector
 * @package Coco\SourceWatcher\Core\Database\Connections
 *
 * Following the definition from: https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#pdo-sqlite
 */
class SqliteConnector extends EmbeddedDatabaseConnector
{
    /**
     * @var string
     */
    protected string $path;

    /**
     * @var bool
     */
    protected bool $memory;

    /**
     * SqliteConnector constructor.
     */
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

    /**
     * @return array
     */
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
}
