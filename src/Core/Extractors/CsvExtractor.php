<?php

namespace Coco\SourceWatcher\Core\Extractors;

use Coco\SourceWatcher\Core\Extractor;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;

/**
 * Class CsvExtractor
 *
 * @package Coco\SourceWatcher\Core\Extractors
 */
class CsvExtractor extends Extractor
{
    protected array $columns;
    protected string $delimiter;
    protected string $enclosure;

    protected array $availableOptions = [ "columns", "delimiter", "enclosure" ];

    public function __construct ()
    {
        $this->columns = [];
        $this->delimiter = ",";
        $this->enclosure = "\"";
    }

    public function getColumns () : array
    {
        return $this->columns;
    }

    public function setColumns ( array $columns ) : void
    {
        $this->columns = $columns;
    }

    public function getDelimiter () : string
    {
        return $this->delimiter;
    }

    public function setDelimiter ( string $delimiter ) : void
    {
        $this->delimiter = $delimiter;
    }

    public function getEnclosure () : string
    {
        return $this->enclosure;
    }

    public function setEnclosure ( string $enclosure ) : void
    {
        $this->enclosure = $enclosure;
    }

    /**
     * @return array
     * @throws SourceWatcherException
     */
    public function extract () : array
    {
        if ( $this->input == null ) {
            throw new SourceWatcherException( "An input must be provided." );
        }

        $inputIsFileInput = $this->input instanceof FileInput;

        if ( !$inputIsFileInput ) {
            throw new SourceWatcherException( sprintf( "The input must be an instance of %s", FileInput::class ) );
        }

        $this->result = [];

        $fileHandler = fopen( $this->input->getInput(), "r" );

        $this->columns = $this->generateColumns( $fileHandler );

        while ( $currentFileLine = fgets( $fileHandler ) ) {
            $currentRowArray = $this->generateRow( $currentFileLine, $this->columns );

            array_push( $this->result, new Row( $currentRowArray ) );
        }

        fclose( $fileHandler );

        return $this->result;
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

        $resultColumns = [];

        foreach ( $this->columns as $key => $value ) {
            $resultColumns[$value] = $columnsArrayFlipped[$key];
        }

        return $resultColumns;
    }

    private function generateRow ( string $rowString, array $columns ) : array
    {
        $resultRow = [];

        $rowArray = str_getcsv( $rowString, $this->delimiter, $this->enclosure );

        foreach ( $columns as $column => $index ) {
            $resultRow[$column] = $rowArray[$index - 1];
        }

        return $resultRow;
    }
}
