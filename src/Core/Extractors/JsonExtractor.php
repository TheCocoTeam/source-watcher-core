<?php

namespace Coco\SourceWatcher\Core\Extractors;

use Coco\SourceWatcher\Core\Extractor;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;
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
    protected array $columns = [];

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
     * @return array
     * @throws SourceWatcherException
     */
    public function extract ()
    {
        if ( $this->input == null ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( JsonExtractor::class, "No_Input_Provided" ) );
        }

        $inputIsFileInput = $this->input instanceof FileInput;

        if ( !$inputIsFileInput ) {
            throw new SourceWatcherException( sprintf( i18n::getInstance()->getText( JsonExtractor::class, "Input_Not_Instance_Of_File_Input" ), FileInput::class ) );
        }

        $result = array();

        if ( !file_exists( $this->input->getInput() ) ) {
            throw new SourceWatcherException( sprintf( i18n::getInstance()->getText( JsonExtractor::class, "File_Input_File_Not_Found" ), $this->input->getInput() ) );
        }

        $data = json_decode( file_get_contents( $this->input->getInput() ), true );

        if ( $this->columns ) {
            $jsonPath = new JSONPath( $data );

            try {
                foreach ( $this->columns as $key => $path ) {
                    $this->columns[$key] = $jsonPath->find( $path )->data();
                }
            } catch ( JSONPathException $jsonPathException ) {
                throw new SourceWatcherException( sprintf( i18n::getInstance()->getText( JsonExtractor::class, "JSON_Path_Exception" ), $jsonPathException->getMessage() ) );
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
