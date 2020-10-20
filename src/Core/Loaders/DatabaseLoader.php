<?php

namespace Coco\SourceWatcher\Core\Loaders;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use Coco\SourceWatcher\Core\Loader;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;

/**
 * Class DatabaseLoader
 *
 * @package Coco\SourceWatcher\Core\Loaders
 */
class DatabaseLoader extends Loader
{
    /**
     * @param Row $row
     * @throws SourceWatcherException
     */
    public function load ( Row $row )
    {
        $this->insert( $row );
    }

    /**
     * @param Row $row
     * @throws SourceWatcherException
     */
    protected function insert ( Row $row ) : void
    {
        if ( $this->output == null ) {
            throw new SourceWatcherException( "An output must be provided." );
        }

        $outputIsDatabaseOutput = $this->output instanceof DatabaseOutput;

        if ( !$outputIsDatabaseOutput ) {
            throw new SourceWatcherException( sprintf( "The output must be an instance of %s", DatabaseOutput::class ) );
        }

        if ( $this->output->getOutput() == null ) {
            throw new SourceWatcherException( "No connector found. Set a connector before trying to insert a row." );
        }

        $this->output->getOutput()->insert( $row );
    }
}
