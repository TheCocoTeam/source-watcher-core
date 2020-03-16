<?php

namespace Coco\SourceWatcher\Watcher\Handler;

use DOMDocument;
use Exception;

class WebPageHandler implements Handler
{
    protected string $url;
    protected string $html;
    protected DOMDocument $dom;

    public function __construct ( string $url )
    {
        $this->url = $url;
        $this->html = "";
        $this->dom = new DOMDocument();
    }

    public function getUrl () : string
    {
        return $this->url;
    }

    public function setUrl ( string $url ) : void
    {
        $this->url = $url;
    }

    public function read () : void
    {
        if ( $this->url == null ) {
            throw new Exception( "A URL must be set before reading" );
        }

        try {
            $ch = curl_init();

            $timeout = 5;

            curl_setopt( $ch, CURLOPT_URL, $this->url );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );

            $this->html = curl_exec( $ch );

            curl_close( $ch );

            # Create a DOM parser object
            $this->dom = new DOMDocument();

            if ( isset( $this->html ) && $this->html !== "" ) {
                # The @ before the method call suppresses any warnings that loadHTML might throw because of invalid HTML in the page.
                @$this->dom->loadHTML( $this->html );
            } else {
                // TODO: Handle re-attempts when no response found.
            }
        } catch ( Exception $e ) {

        }
    }

    public function getDom () : DOMDocument
    {
        return $this->dom;
    }

    public function getHtml () : string
    {
        return $this->html;
    }
}
