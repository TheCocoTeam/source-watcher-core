<?php

namespace Coco\SourceWatcher\Core\Loaders;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\Loader;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;

class DatabaseLoader extends Loader
{
    /**
     * @var Connector
     */
    private Connector $connector;

    /**
     * @param Row $row
     * @throws SourceWatcherException
     */
    public function load ( Row $row )
    {
        $this->insert( $row );
    }

    /**
     * @return Connector
     */
    public function getConnector () : Connector
    {
        return $this->connector;
    }

    /**
     * @param Connector $connector
     */
    public function setConnector ( Connector $connector ) : void
    {
        $this->connector = $connector;
    }

    /**
     * @param Row $row
     * @throws SourceWatcherException
     */
    protected function insert ( Row $row ) : void
    {
        if ( $this->connector == null ) {
            throw new SourceWatcherException( "No connector found. Set a connector before trying to insert a row." );
        }

        $this->connector->insert( $row );
    }
}
