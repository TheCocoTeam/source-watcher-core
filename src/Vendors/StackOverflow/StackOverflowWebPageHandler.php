<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

use Coco\SourceWatcher\Handler\WebPageHandler;
use DOMElement;
use DOMXPath;

class StackOverflowWebPageHandler extends WebPageHandler
{
    public function __construct ( string $url )
    {
        parent::__construct( $url );
    }

    private array $results;

    public function getResults () : array
    {
        return $this->results;
    }

    public function read () : void
    {
        parent::read();

        $this->results = array();

        $finder = new DomXPath( $this->dom );
        $classname = "listResults";
        $listResultsDom = $finder->query( "//*[contains(@class, '$classname')]" ); // DOMNodeList

        for ( $i = 0; $i < $listResultsDom->count(); $i++ ) {
            $currentDomNode = $listResultsDom->item( $i ); // DOMElement

            if ( $currentDomNode->hasChildNodes() ) {
                $children = $currentDomNode->childNodes; // DOMNodeList

                for ( $j = 0; $j < $children->count(); $j++ ) {
                    $currentJob = new StackOverflowJob();

                    $currentChildrenNode = $children->item( $j ); // DOMElement or DOMText
                    $attributes = $currentChildrenNode->attributes; // DOMNamedNodeMap

                    if ( $attributes != null ) {
                        foreach ( $attributes as $currentAttribute ) {
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
                    }

                    if ( $currentChildrenNode->hasChildNodes() ) {
                        $extraChildNodes = $currentChildrenNode->childNodes; // DOMNodeList

                        for ( $k = 0; $k < $extraChildNodes->count(); $k++ ) {
                            $currentExtraChildNode = $extraChildNodes->item( $k ); // DOMElement or DOMText

                            if ( $currentExtraChildNode instanceof DOMElement ) {
                                if ( $currentExtraChildNode->hasChildNodes() ) {
                                    $currentExtraChildNodeChildren = $currentExtraChildNode->childNodes; // DOMNodeList

                                    if ( $currentExtraChildNodeChildren->count() == 6 ) {
                                        for ( $l = 0; $l < $currentExtraChildNodeChildren->count(); $l++ ) {
                                            $currentDeepNode = $currentExtraChildNodeChildren->item( $l ); // DOMElement or DOMText

                                            if ( $currentDeepNode instanceof DOMElement ) {
                                                $currentDeepNodeAttributes = $currentDeepNode->attributes; // DOMNamedNodeMap

                                                if ( $currentDeepNode->tagName == "img" ) {
                                                    if ( $currentDeepNodeAttributes != null && sizeof( $currentDeepNodeAttributes ) == 2 ) {
                                                        $attr1 = $currentDeepNodeAttributes[0]; // DOMAttr
                                                        $attr2 = $currentDeepNodeAttributes[1]; // DOMAttr

                                                        if ( $attr1->name == "class" && $attr1->value == "grid--cell fl-shrink0 w48 h48 bar-sm mr12" && $attr2->name == "src" ) {
                                                            $currentJob->setLogo( $attr2->value );
                                                        }
                                                    }
                                                }

                                                if ( $currentDeepNode->tagName == "div" ) {
                                                    if ( $currentDeepNode->hasChildNodes() ) {
                                                        $currentDeepNodeChildren = $currentDeepNode->childNodes; // DOMNodeList

                                                        for ( $m = 0; $m < $currentDeepNodeChildren->count(); $m++ ) {
                                                            $currentDeepNodeChildrenElement = $currentDeepNodeChildren->item( $m ); // DOMElement or DOMText

                                                            if ( $currentDeepNodeChildrenElement instanceof DOMElement ) {
                                                                if ( $currentDeepNodeChildrenElement->tagName == "h2" ) {
                                                                    $currentJob->setTitle( trim( $currentDeepNodeChildrenElement->nodeValue ) );
                                                                }

                                                                if ( $currentDeepNodeChildrenElement->tagName == "h3" ) {
                                                                    if ( $currentDeepNodeChildrenElement->hasChildNodes() ) {
                                                                        $companyAndLocationDomNodeList = $currentDeepNodeChildrenElement->childNodes; // DOMNodeList

                                                                        for ( $n = 0; $n < $companyAndLocationDomNodeList->count(); $n++ ) {
                                                                            $currentCompanyAndLocationElement = $companyAndLocationDomNodeList->item( $n ); // DOMElement or DOMText

                                                                            if ( $currentCompanyAndLocationElement instanceof DOMElement ) {
                                                                                if ( $currentCompanyAndLocationElement->nodeName == "span" ) {
                                                                                    if ( $currentCompanyAndLocationElement->attributes->count() == 0 ) {
                                                                                        $companyName = trim( $currentCompanyAndLocationElement->nodeValue );

                                                                                        if ( strpos( $companyName, "\r\n" ) !== false ) {
                                                                                            $companyNameParts = explode( "\r\n", $companyName );

                                                                                            foreach ( $companyNameParts as $index => $currentCompanyNamePart ) {
                                                                                                $companyNameParts[$index] = trim( $currentCompanyNamePart );
                                                                                            }

                                                                                            $currentJob->setCompany( implode( " ", $companyNameParts ) );
                                                                                        } else {
                                                                                            $currentJob->setCompany( $companyName );
                                                                                        }
                                                                                    }

                                                                                    if ( $currentCompanyAndLocationElement->attributes->count() == 1 ) {
                                                                                        if ( $currentCompanyAndLocationElement->getAttribute( "class" ) == "fc-black-500" ) {
                                                                                            $currentJob->setLocation( trim( $currentCompanyAndLocationElement->nodeValue ) );
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ( $currentJob->allAttributesDefined() ) {
                        array_push( $this->results, $currentJob );
                    } else {

                    }
                }
            }
        }
    }
}
