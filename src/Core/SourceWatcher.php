<?php

namespace Coco\SourceWatcher\Core;

class SourceWatcher
{
    private StepLoader $stepLoader;

    public function __construct ()
    {
        $this->stepLoader = new StepLoader();
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

        $extractor = $this->stepLoader->step( Extractor::class, $extractorName );
        $extractor->setInput( $input );
        $extractor->options( $options );

        return $this;
    }

    public function transform () : SourceWatcher
    {

    }

    public function load () : SourceWatcher
    {

    }
}
