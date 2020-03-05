<?php

namespace Coco\SourceWatcher\Core\Extractors;

use Coco\SourceWatcher\Core\Extractor;
use Coco\SourceWatcher\Core\Row;

use Exception;

class CsvExtractor extends Extractor
{
    private array $columns;
    private string $delimiter;
    private string $enclosure;

    public function __construct ()
    {
        $this->columns = array();
        $this->delimiter = ",";
        $this->enclosure = "\"";
    }

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
     * @return string
     */
    public function getDelimiter () : string
    {
        return $this->delimiter;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter ( string $delimiter ) : void
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @return string
     */
    public function getEnclosure () : string
    {
        return $this->enclosure;
    }

    /**
     * @param string $enclosure
     */
    public function setEnclosure ( string $enclosure ) : void
    {
        $this->enclosure = $enclosure;
    }

    public function extract () : array
    {
        if ( $this->input == null ) {
            throw new Exception( "An input must be provided." );
        }

        $result = array();

        $fileHandler = fopen( $this->input, "r" );

        $columns = $this->generateColumns( $fileHandler );

        while ( $currentFileLine = fgets( $fileHandler ) ) {
            $currentRowArray = $this->generateRow( $currentFileLine, $columns );

            array_push( $result, new Row( $currentRowArray ) );
        }

        fclose( $fileHandler );

        return $result;
    }

    private function generateColumns ( $fileHandler ) : array
    {
        // The goal will be to represent the keys in format [key1 -> 1, key2 -> 2, ... keyN -> N]
        $columnsArrayFlipped = array_flip( str_getcsv( fgets( $fileHandler ), $this->delimiter, $this->enclosure ) );

        foreach ( $columnsArrayFlipped as $key => $index ) {
            $columnsArrayFlipped[$key] = $index + 1;
        }

        // If no columns have been defined, make the columns attribute equal to the ones with format [key1 -> 1, key2 -> 2, ... keyN -> n]
        if ( empty( $this->columns ) ) {
            return $columnsArrayFlipped;
        }

        // If the keys of the columns attribute equal to an array in format [0, 1, ... N] then they need to be reformatted as an intersection of the ones found and the ones requested.
        if ( array_keys( $this->columns ) === range( 0, count( $this->columns ) - 1 ) ) {
            return array_intersect_key( $columnsArrayFlipped, array_flip( $this->columns ) );
        }

        $result = array();

        foreach ( $this->columns as $key => $value ) {
            $result[$value] = $columnsArrayFlipped[$key];
        }

        return $result;
    }

    private function generateRow ( string $rowString, array $columns ) : array
    {
        $resultRow = array();

        $rowArray = str_getcsv( $rowString, $this->delimiter, $this->enclosure );

        foreach ( $columns as $column => $index ) {
            $resultRow[$column] = $rowArray[$index - 1];
        }

        return $resultRow;
    }
}
