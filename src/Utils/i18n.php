<?php

namespace Coco\SourceWatcher\Utils;

use Dotenv\Dotenv;
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

    /**
     * @return string|null
     */
    private function getLanguageFromEnvFile () : ?string
    {
        $dotEnv = Dotenv::createImmutable( __DIR__ . "./../../" );
        $dotEnv->load();

        return array_key_exists( "I18N_LANGUAGE", $_ENV ) ? $_ENV["I18N_LANGUAGE"] : null;
    }

    /**
     * @param string $className
     * @param string $entry
     * @param string|null $language
     * @return string
     */
    public function getText ( string $className, string $entry, string $language = null ) : string
    {
        if ( empty( $language ) ) {
            $language = $this->getLanguageFromEnvFile();
        }

        if ( empty( $language ) ) {
            $language = "en_US";
        }

        $data = Yaml::parseFile( __DIR__ . sprintf( "/../../resources/Locales/translations_%s.yml", $language ) );

        $classPathParts = explode( "\\", $className );

        $dataCopy = $data;

        foreach ( $classPathParts as $currentPart ) {
            $dataCopy = $dataCopy[$currentPart];
        }

        return $dataCopy[$entry];
    }
}
