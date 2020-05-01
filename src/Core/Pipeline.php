<?php

namespace Coco\SourceWatcher\Core;

use Iterator;

/**
 * Class Pipeline
 * @package Coco\SourceWatcher\Core
 */
class Pipeline implements Iterator
{
    /**
     * @var Extractor
     */
    private ?Extractor $extractor = null;

    /**
     * @var array
     */
    private array $steps = [];

    /**
     * @var array
     */
    private array $results = [];

    public function __construct ()
    {

    }

    /**
     * @return Extractor
     */
    public function getExtractor () : Extractor
    {
        return $this->extractor;
    }

    /**
     * @param Extractor $extractor
     */
    public function setExtractor ( Extractor $extractor ) : void
    {
        $this->extractor = $extractor;
    }

    /**
     * @return array
     */
    public function getSteps () : array
    {
        return $this->steps;
    }

    /**
     * @param array $steps
     */
    public function setSteps ( array $steps ) : void
    {
        $this->steps = $steps;
    }

    /**
     * @return array
     */
    public function getResults () : array
    {
        return $this->results;
    }

    /**
     * @param array $results
     */
    public function setResults ( array $results ) : void
    {
        $this->results = $results;
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

    /**
     * @param Step $step
     */
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
}
