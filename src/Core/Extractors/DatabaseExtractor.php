<?php

namespace Coco\SourceWatcher\Core\Extractors;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\Extractor;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;

/**
 * Class DatabaseExtractor
 *
 * @package Coco\SourceWatcher\Core\Extractors
 */
class DatabaseExtractor extends Extractor
{
    private ?Connector $databaseConnector = null;

    private ?string $query = null;

    public function __construct ( Connector $databaseConnector, string $query )
    {
        $this->databaseConnector = $databaseConnector;
        $this->query = $query;
    }

    /**
     * @return array
     * @throws SourceWatcherException
     */
    public function extract () : array
    {
        if ( $this->databaseConnector == null ) {
            throw new SourceWatcherException( "Database connector missing" );
        }

        if ( $this->query == null ) {
            throw new SourceWatcherException( "Query missing" );
        }

        $result = [];

        $arrayResults = $this->databaseConnector->executePlainQuery( $this->query );

        foreach ( $arrayResults as $currentRecord ) {
            array_push( $result, new Row( $currentRecord ) );
        }

        return $result;
    }
}
