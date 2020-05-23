<?php

namespace Coco\SourceWatcher\Utils;

use Symfony\Component\Yaml\Yaml;

class i18n
{
    /**
     * @var i18n|null
     */
    private static ?i18n $instance = null;

    /**
     * @return i18n
     */
    public static function getInstance () : i18n
    {
        if ( is_null( static::$instance ) ) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function getText ( string $language = "en_US", string $className, string $entry ) : string
    {
        $result = "";

        $data = Yaml::parseFile( __DIR__ . sprintf( "/../../resources/Locales/translations_%s.yml", $language ) );

        $classPathParts = explode( "\\", $className );

        $dataCopy = $data;

        foreach ( $classPathParts as $currentPart ) {
            $dataCopy = $dataCopy[$currentPart];
        }

        $result = $dataCopy[$entry];

        return $result;
    }
}
