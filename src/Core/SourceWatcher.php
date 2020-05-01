<?php

namespace Coco\SourceWatcher\Core;

use Coco\SourceWatcher\Core\IO\Inputs\Input;

/**
 * Class SourceWatcher
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

    public function extract ( string $extractorName, Input $input, array $options = [] ) : SourceWatcher
    {
        try {
            $extractor = $this->stepLoader->getStep( Extractor::class, $extractorName );
            $extractor->setInput( $input );
            $extractor->options( $options );

            $this->pipeline->setExtractor( $extractor );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }

        return $this;
    }

    public function transform ( string $transformerName, array $options = [] ) : SourceWatcher
    {
        try {
            $transformer = $this->stepLoader->getStep( Transformer::class, $transformerName );
            $transformer->options( $options );

            $this->pipeline->pipe( $transformer );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }

        return $this;
    }

    public function load ( string $loaderName, $output, $options = [] ) : SourceWatcher
    {
        try {
            $loader = $this->stepLoader->getStep( Loader::class, $loaderName );
            $loader->setOutput( $output );
            $loader->options( $options );

            $this->pipeline->pipe( $loader );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }

        return $this;
    }

    public function run () : void
    {
        $this->pipeline->execute();
    }
}
