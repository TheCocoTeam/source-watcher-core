<?php

namespace Coco\SourceWatcher\Core;

use Coco\SourceWatcher\Core\IO\Inputs\Input;
use Coco\SourceWatcher\Core\IO\Outputs\Output;

/**
 * Class SourceWatcher
 * @package Coco\SourceWatcher\Core
 */
class SourceWatcher
{
    /**
     * @var StepLoader
     */
    private StepLoader $stepLoader;

    /**
     * @var Pipeline
     */
    private Pipeline $pipeline;

    /**
     * SourceWatcher constructor.
     */
    public function __construct ()
    {
        $this->stepLoader = new StepLoader();
        $this->pipeline = new Pipeline();
    }

    /**
     * @return StepLoader
     */
    public function getStepLoader () : StepLoader
    {
        return $this->stepLoader;
    }

    /**
     * @return Pipeline
     */
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
            throw new SourceWatcherException( sprintf( "The extractor %s can't be found.", $extractorName ) );
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
            throw new SourceWatcherException( sprintf( "The transformer %s can't be found.", $transformerName ) );
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
            throw new SourceWatcherException( sprintf( "The loader %s can't be found.", $loaderName ) );
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
