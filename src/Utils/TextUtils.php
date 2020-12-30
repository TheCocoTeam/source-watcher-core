<?php

namespace Coco\SourceWatcher\Utils;

/**
 * Class TextUtils
 *
 * @package Coco\SourceWatcher\Utils
 */
class TextUtils
{
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

    public function fromCamelToSnakeCase ( string $word ) : string
    {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all( $pattern, $word, $matches );
        $ret = $matches[0];

        foreach ( $ret as &$match ) {
            $match = $match == strtoupper( $match ) ?
                strtolower( $match ) :
                lcfirst( $match );
        }

        return implode( '_', $ret );
    }
}
