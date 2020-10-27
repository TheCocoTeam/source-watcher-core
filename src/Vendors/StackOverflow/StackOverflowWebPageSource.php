<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Watcher\Source\WebPageSource;

/**
 * Class StackOverflowWebPageSource
 *
 * @package Coco\SourceWatcher\Vendors\StackOverflow
 */
class StackOverflowWebPageSource extends WebPageSource
{
    public function __construct ( string $url )
    {
        parent::__construct( $url );

        $this->handler = new StackOverflowWebPageHandler( $url );
    }

    /**
     * Function that returns the results from the given StackOverflow URL.
     *
     * @return array
     * @throws SourceWatcherException
     */
    public function getResults () : array
    {
        if ( $this->url != $this->handler->getUrl() ) {
            $this->handler = new StackOverflowWebPageHandler( $this->url );
        }

        try {
            $this->handler->read();
        } catch ( SourceWatcherException $e ) {
            throw new SourceWatcherException( "Something went wrong trying to read the results", $e->getCode(), $e );
        }

        return $this->handler->getResults();
    }
}
