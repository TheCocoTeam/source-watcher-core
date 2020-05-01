<?php

namespace Coco\SourceWatcher\Core;

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

    public function extract ( string $extractorName, $input, array $options = [] ) : SourceWatcher
    {
        /*
         * Since PHP 5.5, the class keyword is also used for class name resolution.
         * You can get a string containing the fully qualified name of the ClassName class by using ClassName::class.
         * This is particularly useful with namespaced classes.
         *
         * Coco\SourceWatcher\Core\Extractor, $extractorName
         */

        try {
            $extractor = $this->stepLoader->getStep( Extractor::class, $extractorName );
            $extractor->setInput( $input );
            $extractor->options( $options );

            $this->pipeline->setExtractor( $extractor );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }

        return $this;
    }

    public function transform () : SourceWatcher
    {

    }

    public function load () : SourceWatcher
    {

    }
}
