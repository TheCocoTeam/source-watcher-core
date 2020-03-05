<?php

namespace Coco\SourceWatcher\Core;

/**
 * Class Step
 * @package Coco\SourceWatcher\Core
 */
class Step
{
    /**
     * @var array
     */
    protected array $availableOptions = array();

    /**
     * @param array $options
     */
    public function options ( array $options )
    {
        foreach ( $options as $optionName => $optionValue ) {
            $camelCaseOptionName = $this->textToCamelCase( $optionName );

            if ( in_array( $camelCaseOptionName, $this->availableOptions ) ) {
                $this->$camelCaseOptionName = $optionValue;
            }
        }
    }

    /**
     * @param string $word
     * @return string
     */
    protected function textToCamelCase ( string $word ) : string
    {
        // Make an array of word parts exploding the word by "_"
        $wordParts = explode( "_", $word );

        // Make every word part lower case
        $wordParts = array_map( "strtolower", $wordParts );

        // Make ever word part first character uppercase
        $wordParts = array_map( "ucfirst", $wordParts );

        // Make the new word as the combination of the given word parts
        $newWord = implode( "", $wordParts );

        // Make the new word first character lowercase
        $newWord = lcfirst( $newWord );

        return $newWord;
    }
}
