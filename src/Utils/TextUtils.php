<?php

namespace Coco\SourceWatcher\Utils;

/**
 * Class TextUtils
 * @package Coco\SourceWatcher\Utils
 */
class TextUtils
{
    /**
     * @param string $word
     * @return string
     */
    public function textToCamelCase ( string $word ) : string
    {
        // Make an array of word parts exploding the word by "_"
        $wordParts = explode( "_", $word );

        // Make every word part lower case
        $wordParts = array_map( "strtolower", $wordParts );

        // Make every word part first character uppercase
        $wordParts = array_map( "ucfirst", $wordParts );

        // Make the new word the combination of the given word parts
        $newWord = implode( "", $wordParts );

        // Make the new word first character lowercase
        return lcfirst( $newWord );
    }

    /**
     * @param string $word
     * @return string
     */
    public function textToPascalCase ( string $word ) : string
    {
        // Make an array of word parts exploding the word by "_"
        $wordParts = explode( "_", $word );

        // Make every word part lower case
        $wordParts = array_map( "strtolower", $wordParts );

        // Make every word part first character uppercase
        $wordParts = array_map( "ucfirst", $wordParts );

        // Make the new word the combination of the given word parts
        return implode( "", $wordParts );
    }
}
