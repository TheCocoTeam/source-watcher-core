<?php

namespace Coco\SourceWatcher\Core;

use Coco\SourceWatcher\Core\Extractors\ExecutionExtractor;
use Coco\SourceWatcher\Core\IO\Inputs\ExtractorResultInput;
use Iterator;

/**
 * Class Pipeline
 *
 * @package Coco\SourceWatcher\Core
 */
class Pipeline implements Iterator
{
    private array $steps = [];

    private array $results = [];

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
        if ( $step instanceof ExecutionExtractor ) {
            $step->setInput( new ExtractorResultInput( end( $this->steps ) ) );
        }

        $this->steps[] = $step;
    }

    public function execute () : void
    {
        foreach ( $this->steps as $index => $currentStep ) {
            if ( $currentStep instanceof Extractor ) {
                $this->results = $currentStep->extract();
            }

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
