<?php

namespace Coco\SourceWatcher\Source;

use Coco\SourceWatcher\Handler\Handler;
use Coco\SourceWatcher\Handler\WebPageHandler;

class WebPageSource implements Source {
    private String $url;
    private WebPageHandler $handler;

    public function __construct ( String $url ) {
        $this->url = $url;
        $this->handler = new WebPageHandler( $url );
    }

    public function getUrl () : String {
        return $this->url;
    }

    public function setUrl ( String $url ) : void {
        $this->url = $url;
    }

    public function getHandler () : Handler {
        return $this->handler;
    }

    public function setHandler ( Handler $handler ) : void {
        $this->handler = $handler;
    }
}
