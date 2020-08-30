<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Watcher\Source\WebPageSource;

/**
 * Class StackOverflowWebPageSource
 * @package Coco\SourceWatcher\Vendors\StackOverflow
 */
class StackOverflowWebPageSource extends WebPageSource
{
    /**
     * StackOverflowWebPageSource constructor.
     * @param string $url
     */
    public function __construct ( string $url )
    {
        parent::__construct( $url );

        $this->handler = new StackOverflowWebPageHandler( $url );
    }

    public function getResults () : array
    {
        if ( $this->url != $this->handler->getUrl() ) {
            $this->handler = new StackOverflowWebPageHandler( $this->url );
        }

        try {
            $this->handler->read();
        } catch ( SourceWatcherException $e ) {

        }

        return $this->handler->getResults();
    }
}
