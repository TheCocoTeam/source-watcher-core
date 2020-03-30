<?php

namespace Coco\SourceWatcher\Core\Extractors;

use Coco\SourceWatcher\Core\Extractor;
use Coco\SourceWatcher\Core\Row;

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
     * @var array
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
     * @throws JSONPathException
     */
    public function extract ()
    {
        if ( $this->input == null ) {
            throw new Exception( "An input must be provided." );
        }

        $result = array();

        // @todo: check if file exists
        $data = json_decode( file_get_contents( $this->input ), true );

        if ( $this->columns ) {
            // @todo: control possible JSONPathException exception
            $jsonPath = new JSONPath( $data );

            foreach ( $this->columns as $key => $path ) {
                $this->columns[$key] = $jsonPath->find( $path )->data();
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
