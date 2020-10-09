<?php

namespace Coco\SourceWatcher\Core;

use Coco\SourceWatcher\Core\IO\Inputs\Input;
use Coco\SourceWatcher\Core\IO\Outputs\Output;
use Coco\SourceWatcher\Utils\Internationalization;

/**
 * Class SourceWatcher
 *
 * @package Coco\SourceWatcher\Core
 */
class SourceWatcher
{
    private StepLoader $stepLoader;

    private Pipeline $pipeline;

    public function __construct ()
    {
        $this->stepLoader = new StepLoader();
        $this->pipeline = new Pipeline();
    }

    public function getStepLoader () : StepLoader
    {
        return $this->stepLoader;
    }

    public function getPipeline () : Pipeline
    {
        return $this->pipeline;
    }

    /**
     * @param string $extractorName
     * @param Input $input
     * @param array $options
     * @return $this
     * @throws SourceWatcherException
     */
    public function extract ( string $extractorName, Input $input, array $options = [] ) : SourceWatcher
    {
        $extractor = $this->stepLoader->getStep( Extractor::class, $extractorName );

        if ( $extractor == null ) {
            throw new SourceWatcherException( sprintf( Internationalization::getInstance()->getText( SourceWatcher::class,
                "Extractor_Not_Found" ), $extractorName ) );
        }

        $extractor->setInput( $input );
        $extractor->options( $options );

        $this->pipeline->setExtractor( $extractor );

        return $this;
    }

    /**
     * @param string $transformerName
     * @param array $options
     * @return $this
     * @throws SourceWatcherException
     */
    public function transform ( string $transformerName, array $options = [] ) : SourceWatcher
    {
        $transformer = $this->stepLoader->getStep( Transformer::class, $transformerName );

        if ( $transformer == null ) {
            throw new SourceWatcherException( sprintf( Internationalization::getInstance()->getText( SourceWatcher::class,
                "Transformer_Not_Found" ), $transformerName ) );
        }

        $transformer->options( $options );

        $this->pipeline->pipe( $transformer );

        return $this;
    }

    /**
     * @param string $loaderName
     * @param Output $output
     * @param array $options
     * @return $this
     * @throws SourceWatcherException
     */
    public function load ( string $loaderName, Output $output, array $options = [] ) : SourceWatcher
    {
        $loader = $this->stepLoader->getStep( Loader::class, $loaderName );

        if ( $loader == null ) {
            throw new SourceWatcherException( sprintf( Internationalization::getInstance()->getText( SourceWatcher::class,
                "Loader_Not_Found" ), $loaderName ) );
        }

        $loader->setOutput( $output );
        $loader->options( $options );

        $this->pipeline->pipe( $loader );

        return $this;
    }

    public function run () : void
    {
        $this->pipeline->execute();
    }
}
