<?php

namespace Coco\SourceWatcher\Handler;

use Exception;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\CurlException;
use PHPHtmlParser\Exceptions\StrictException;

class WebPageHandler implements Handler {
    private string $url;
    private Dom $dom;

    public function __construct ( string $url ) {
        $this->url = $url;
        $this->dom = new Dom();

        $this->read();
    }

    public function getUrl () : string {
        return $this->url;
    }

    public function setUrl ( $url ) : void {
        $this->url = $url;
    }

    public function read () : void {
        if ( $this->url == null ) {
            throw new Exception( "A URL must be set before reading" );
        }

        try {
            $this->dom->loadFromUrl($this->url);
        } catch ( ChildNotFoundException $e ) {

        } catch ( CircularException $e ) {

        } catch ( CurlException $e ) {

        } catch ( StrictException $e ) {

        }
    }

    public function getDom() : Dom {
        return $this->dom;
    }

    public function getHtml () : string {
        return $this->dom->outerHtml;
    }
}
