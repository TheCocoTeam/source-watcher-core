<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

use Coco\SourceWatcher\Source\WebPageSource;

class StackOverflowWebPageSource extends WebPageSource
{
    public function __construct ( String $url )
    {
        parent::__construct( $url );

        $this->handler = new StackOverflowWebPageHandler( $url );
    }

    public function getResults () : array
    {
        if ( $this->url != $this->handler->getUrl() ) {
            $this->handler = new StackOverflowWebPageHandler( $this->url );
        }

        $this->handler->read();

        return $this->handler->getResults();
    }
}
