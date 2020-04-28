<?php

namespace Coco\SourceWatcher\Core\Extractors;

use Coco\SourceWatcher\Core\Extractor;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Flow\JSONPath\JSONPath;
use Flow\JSONPath\JSONPathException;

/**
 * Class JsonExtractor
 * @package Coco\SourceWatcher\Core\Extractors
 */
class JsonExtractor extends Extractor
{
    /**
     * @var array
     */
    private array $columns = [];

    /**
     * @var array|string[]
     */
    protected array $availableOptions = [ "columns" ];

    /**
     * @return array
     */
    public function getColumns () : array
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     */
    public function setColumns ( array $columns ) : void
    {
        $this->columns = $columns;
    }

    /**
     * @return array|mixed
     * @throws SourceWatcherException
     */
    public function extract ()
    {
        if ( $this->input == null ) {
            throw new SourceWatcherException( "An input must be provided." );
        }

        $result = array();

        if ( !file_exists( $this->input ) ) {
            throw new SourceWatcherException( "The file " . $this->input . " could not be found." );
        }

        $data = json_decode( file_get_contents( $this->input ), true );

        if ( $this->columns ) {
            $jsonPath = new JSONPath( $data );

            try {
                foreach ( $this->columns as $key => $path ) {
                    $this->columns[$key] = $jsonPath->find( $path )->data();
                }
            } catch ( JSONPathException $jsonPathException ) {
                throw new SourceWatcherException( "Something went wrong trying to extract the JSON file: " . $jsonPathException->getMessage() );
            } catch ( Exception $exception ) {
                throw new SourceWatcherException( "Something unexpected went wrong trying to extract the JSON file: " . $exception->getMessage() );
            }

            $data = $this->transpose( $this->columns );
        }

        foreach ( $data as $row ) {
            array_push( $result, new Row( $row ) );
        }

        return $result;
    }

    /**
     * @param $columns
     * @return array
     */
    private function transpose ( $columns )
    {
        $data = [];

        foreach ( $columns as $column => $items ) {
            foreach ( $items as $row => $item ) {
                $data[$row][$column] = $item;
            }
        }

        return $data;
    }
}
