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
    protected string $url;

    protected WebPageHandler $handler;

    public function __construct ( string $url )
    {
        $this->url = $url;
        $this->handler = new WebPageHandler( $url );
    }

    public function getUrl () : string
    {
        return $this->url;
    }

    public function setUrl ( string $url ) : void
    {
        $this->url = $url;
    }

    public function getHandler () : Handler
    {
        return $this->handler;
    }

    public function setHandler ( Handler $handler ) : void
    {
        $this->handler = $handler;
    }
}
