<?php

namespace Coco\SourceWatcher\Core;

use Iterator;

/**
 * Class Pipeline
 * @package Coco\SourceWatcher\Core
 */
class Pipeline implements Iterator
{
    private ?Extractor $extractor = null;

    private array $steps = [];

    private array $results = [];

    public function getExtractor () : Extractor
    {
        return $this->extractor;
    }

    public function setExtractor ( Extractor $extractor ) : void
    {
        $this->extractor = $extractor;
    }

    public function getSteps () : array
    {
        return $this->steps;
    }

    public function setSteps ( array $steps ) : void
    {
        $this->steps = $steps;
    }

    public function pipe ( Step $step ) : void
    {
        $this->steps[] = $step;
    }

    public function execute () : void
    {
        $this->results = $this->extractor->extract();

        foreach ( $this->steps as $currentStep ) {
            if ( $currentStep instanceof Transformer ) {
                foreach ( $this->results as $currentIndex => $currentItem ) {
                    $currentStep->transform( $this->results[$currentIndex] );
                }
            }

            if ( $currentStep instanceof Loader ) {
                foreach ( $this->results as $currentIndex => $currentItem ) {
                    $currentStep->load( $currentItem );
                }
            }
        }
    }

    public function getResults () : array
    {
        return $this->results;
    }

    private int $index = 0;

    public function current ()
    {
        return $this->results[$this->index];
    }

    public function next ()
    {
        $this->index++;
    }

    public function key ()
    {
        return $this->index;
    }

    public function valid ()
    {
        return isset( $this->results[$this->key()] );
    }

    public function rewind ()
    {
        $this->index = 0;
    }
}
