<?php

namespace Coco\SourceWatcher\Core;

use Coco\SourceWatcher\Utils\TextUtils;
use ReflectionClass;
use ReflectionException;

/**
 * Class StepLoader
 * @package Coco\SourceWatcher\Core
 */
class StepLoader
{
    /**
     * @var StepLoader|null
     */
    private static ?StepLoader $instance = null;

    /**
     * @return StepLoader
     */
    public static function getInstance () : StepLoader
    {
        if ( is_null( static::$instance ) ) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @param string $parentClassName
     * @param string $stepName
     * @return Step
     * @throws SourceWatcherException
     */
    public function getStep ( string $parentClassName, string $stepName ) : ?Step
    {
        if ( empty( $parentClassName ) ) {
            throw new SourceWatcherException( "The parent class name must be provided." );
        }

        if ( empty( $stepName ) ) {
            throw new SourceWatcherException( "The step name must be provided." );
        }

        try {
            $reflection = new ReflectionClass( $parentClassName );

            $parentClassShortName = $reflection->getShortName();
        } catch ( ReflectionException $reflectionException ) {
            $errorMessage = sprintf( "Something went wrong while trying to get the short class name: %s", $reflectionException->getMessage() );
            throw new SourceWatcherException( $errorMessage );
        }

        $baseNameSpace = "Coco\\SourceWatcher\\Core";

        $packages = [ "Extractor" => sprintf( "%s\\%s", $baseNameSpace, "Extractors" ), "Transformer" => sprintf( "%s\\%s", $baseNameSpace, "Transformers" ), "Loader" => sprintf( "%s\\%s", $baseNameSpace, "Loaders" ) ];

        $step = null;

        $textUtils = new TextUtils();

        $fullyQualifiedClassName = sprintf( "%s\\%s", $packages[$parentClassShortName], $textUtils->textToPascalCase( sprintf( "%s_%s", $stepName, $parentClassShortName ) ) );

        if ( class_exists( $fullyQualifiedClassName ) ) {
            $step = new $fullyQualifiedClassName();
        }

        return $step;
    }
}
