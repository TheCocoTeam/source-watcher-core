<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

use Coco\SourceWatcher\Handler\WebPageHandler;
use DOMXPath;

class StackOverflowWebPageHandler extends WebPageHandler {
    public function __construct ( string $url ) {
        parent::__construct( $url );
    }

    private array $results;

    public function getResults () : array {
        return $this->results;
    }

    public function read () : void {
        parent::read();

        $this->results = array();

        $dom = $this->dom;

        $finder = new DomXPath($dom);
        $classname = "listResults";
        $listResultsDom = $finder->query( "//*[contains(@class, '$classname')]" ); // DOMNodeList

        for ( $i = 0; $i < $listResultsDom->count(); $i++ ) {
            $currentDomNode = $listResultsDom->item( $i ); // DOMNode

            if ( $currentDomNode->hasChildNodes() ) {
                $children = $currentDomNode->childNodes; // DOMNodeList
                //print_r( $children );

                for ( $j = 0; $j < $children->count(); $j++ ) {
                    $currentChildrenNode = $children->item( $j ); // DOMNode
                    //print_r( $currentChildrenNode );

                    $attributes = $currentChildrenNode->attributes; // DOMNamedNodeMap

                    if ( $attributes != null ) {
                        $currentJob = new StackOverflowJob();

                        foreach ( $attributes as $currentAttribute ) {
                            //print_r( $currentAttribute );

                            if ( $currentAttribute->name != null ) {
                                if ( $currentAttribute->name == "data-jobid" ) {
                                    $currentJob->setJobId( $currentAttribute->value );
                                }

                                if ( $currentAttribute->name == "data-result-id" ) {
                                    $currentJob->setResultId( $currentAttribute->value );
                                }

                                if ( $currentAttribute->name == "data-preview-url" ) {
                                    $currentJob->setPreviewUrl( $currentAttribute->value );
                                }
                            }
                        }

                        if ( $currentJob->allAttributesDefined() ) {
                            array_push( $this->results, $currentJob );
                        }
                    }
                }
            }
        }
    }
}
