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
    private Extractor $extractor;

    /**
     * @var array
     */
    private array $steps;

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
     * @return mixed|void
     */
    public function current ()
    {
        // TODO: Implement current() method.
    }

    /**
     *
     */
    public function next ()
    {
        // TODO: Implement next() method.
    }

    /**
     * @return bool|float|int|string|void|null
     */
    public function key ()
    {
        // TODO: Implement key() method.
    }

    /**
     * @return bool|void
     */
    public function valid ()
    {
        // TODO: Implement valid() method.
    }

    /**
     *
     */
    public function rewind ()
    {
        // TODO: Implement rewind() method.
    }

    /**
     * @param Step $step
     */
    public function pipe ( Step $step ) : void
    {
        $this->steps[] = $step;
    }
}
