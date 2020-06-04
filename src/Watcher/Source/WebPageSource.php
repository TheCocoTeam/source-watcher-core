<?php

namespace Coco\SourceWatcher\Watcher\Source;

use Coco\SourceWatcher\Watcher\Handler\Handler;
use Coco\SourceWatcher\Watcher\Handler\WebPageHandler;

/**
 * Class WebPageSource
 * @package Coco\SourceWatcher\Watcher\Source
 */
class WebPageSource implements Source
{
    /**
     * @var String
     */
    protected string $url;

    /**
     * @var WebPageHandler
     */
    protected WebPageHandler $handler;

    /**
     * WebPageSource constructor.
     * @param String $url
     */
    public function __construct ( string $url )
    {
        $this->url = $url;
        $this->handler = new WebPageHandler( $url );
    }

    /**
     * @return String
     */
    public function getUrl () : string
    {
        return $this->url;
    }

    /**
     * @param String $url
     */
    public function setUrl ( string $url ) : void
    {
        $this->url = $url;
    }

    /**
     * @return Handler
     */
    public function getHandler () : Handler
    {
        return $this->handler;
    }

    /**
     * @param Handler $handler
     */
    public function setHandler ( Handler $handler ) : void
    {
        $this->handler = $handler;
    }
}
