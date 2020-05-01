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
     * @var StepLoader
     */
    private static StepLoader $instance;

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
        } catch ( Exception $exception ) {
            $errorMessage = sprintf( "Something unexpected went wrong while trying to get the short class name: %s", $exception->getMessage() );
            throw new SourceWatcherException( $errorMessage );
        }

        $baseNameSpace = "Coco\\SourceWatcher\\Core";

        $packages = [ "Extractor" => sprintf( "%s\\%s", $baseNameSpace, "Extractors" ), "Transformer" => sprintf( "%s\\%s", $baseNameSpace, "Transformers" ), "Loader" => sprintf( "%s\\%s", $baseNameSpace, "Loaders" ) ];

        $subPackage = $packages[$parentClassShortName];

        $textUtils = new TextUtils();

        $step = null;

        try {
            $temporaryClassName = sprintf( "%s_%s", $stepName, $parentClassShortName );

            $pascalCaseClassName = $textUtils->textToPascalCase( $temporaryClassName );

            $fullyQualifiedClassName = sprintf( "%s\\%s", $subPackage, $pascalCaseClassName );

            if ( class_exists( $fullyQualifiedClassName ) ) {
                $step = new $fullyQualifiedClassName();
            }
        } catch ( Exception $exception ) {
            $errorMessage = sprintf( "Something unexpected went wrong while trying to load the step: %s", $exception->getMessage() );
            throw new SourceWatcherException( $errorMessage );
        }

        return $step;
    }
}
