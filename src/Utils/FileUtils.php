<?php

namespace Coco\SourceWatcher\Utils;

/**
 * Class FileUtils
 *
 * @package Coco\SourceWatcher\Utils
 */
class FileUtils
{
    public static function file_build_path ( ...$segments ) : string
    {
        return join( DIRECTORY_SEPARATOR, $segments );
    }

    /**
     * // getenv("HOME") isn't set on windows and generates a Notice.
     *
     * @return string
     */
    public static function getUserHomePath () : string
    {
        $home = getenv( "HOME" );

        if ( empty( $home ) ) {
            if ( !empty( $_SERVER["HOMEDRIVE"] ) && !empty( $_SERVER["HOMEPATH"] ) ) {
                // home on windows
                $home = $_SERVER["HOMEDRIVE"] . $_SERVER["HOMEPATH"];
            }
        }

        return empty( $home ) ? "" : $home;
    }
}
