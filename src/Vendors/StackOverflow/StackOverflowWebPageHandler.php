<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

use Coco\SourceWatcher\Watcher\Handler\WebPageHandler;
use DOMElement;
use DOMNamedNodeMap;
use DOMNodeList;
use DOMXPath;

/**
 * Class StackOverflowWebPageHandler
 *
 * @package Coco\SourceWatcher\Vendors\StackOverflow
 */
class StackOverflowWebPageHandler extends WebPageHandler
{
    private array $results = [];

    public function getResults () : array
    {
        return $this->results;
    }

    public function read () : void
    {
        parent::read();

        $this->results = [];

        $finder = new DomXPath( $this->dom );

        $classname = "listResults";
        $listResultsDom = $finder->query( "//*[contains(@class, '$classname')]" ); // DOMNodeList

        for ( $i = 0; $i < $listResultsDom->count(); $i++ ) {
            $this->processListResults( $listResultsDom->item( $i ) );
        }
    }

    private function processListResults ( DOMElement $currentDomNode ) : void
    {
        if ( $currentDomNode->hasChildNodes() ) {
            $children = $currentDomNode->childNodes; // DOMNodeList

            for ( $i = 0; $i < $children->count(); $i++ ) {
                $currentChildrenNode = $children->item( $i );

                if ( $currentChildrenNode instanceof DOMElement ) {
                    $this->processChildNodes( $currentChildrenNode );
                }
            }
        }
    }

    private function processChildNodes ( DOMElement $currentChildrenNode ) : void
    {
        $currentJob = new StackOverflowJob();

        $currentJob = $this->setBasicAttributes( $currentChildrenNode->attributes, $currentJob );

        if ( $currentChildrenNode->hasChildNodes() ) {
            if ( trim( $currentChildrenNode->nodeValue ) == "You might be interested in these jobs:" ) {
                echo "Found job separator" . PHP_EOL;
            } else {
                $extraChildNodes = $currentChildrenNode->childNodes; // DOMNodeList

                for ( $i = 0; $i < $extraChildNodes->count(); $i++ ) {
                    // DOMElement or DOMText
                    $currentExtraChildNode = $extraChildNodes->item( $i );

                    if ( $currentExtraChildNode instanceof DOMElement ) {
                        $currentJob = $this->processExtraChildNodes( $currentExtraChildNode, $currentJob );
                    }
                }
            }
        }

        if ( $currentJob->allAttributesDefined() ) {
            array_push( $this->results, $currentJob );
        } else {
            echo "Ignoring job because of missing attributes" . PHP_EOL;
        }
    }

    private function setBasicAttributes (
        DOMNamedNodeMap $attributes,
        StackOverflowJob $stackOverflowJob
    ) : StackOverflowJob {
        if ( $attributes != null ) {
            foreach ( $attributes as $currentAttribute ) {
                if ( $currentAttribute->name == "data-jobid" ) {
                    $stackOverflowJob->setJobId( $currentAttribute->value );
                }

                if ( $currentAttribute->name == "data-result-id" ) {
                    $stackOverflowJob->setResultId( $currentAttribute->value );
                }

                if ( $currentAttribute->name == "data-preview-url" ) {
                    $stackOverflowJob->setPreviewUrl( $currentAttribute->value );
                }
            }
        }

        return $stackOverflowJob;
    }

    private function processExtraChildNodes (
        DOMElement $currentExtraChildNode,
        StackOverflowJob $stackOverflowJob
    ) : StackOverflowJob {
        if ( $currentExtraChildNode->hasChildNodes() ) {
            $currentExtraChildNodeChildren = $currentExtraChildNode->childNodes; // DOMNodeList

            $nodeCount = $currentExtraChildNodeChildren->count();

            if ( $nodeCount >= 6 ) {
                for ( $i = 0; $i < $nodeCount; $i++ ) {
                    $currentDeepNode = $currentExtraChildNodeChildren->item( $i ); // DOMElement or DOMText

                    if ( $currentDeepNode instanceof DOMElement ) {
                        $stackOverflowJob = $this->processImageDivBlocks( $currentDeepNode, $stackOverflowJob );
                    }
                }
            }
        }

        return $stackOverflowJob;
    }

    private function processImageDivBlocks (
        DOMElement $currentDeepNode,
        StackOverflowJob $stackOverflowJob
    ) : StackOverflowJob {
        if ( $currentDeepNode->childNodes->count() == 2 ) {
            $stackOverflowJob = $this->processImageBlock( $currentDeepNode->childNodes->item( 1 )->attributes,
                $stackOverflowJob );
        }

        if ( $currentDeepNode->tagName == "div" && $currentDeepNode->hasChildNodes() ) {
            $stackOverflowJob = $this->processDivBlock( $currentDeepNode->childNodes, $stackOverflowJob );
        }

        return $stackOverflowJob;
    }

    private function processImageBlock (
        DOMNamedNodeMap $currentDeepNodeAttributes,
        StackOverflowJob $stackOverflowJob
    ) : StackOverflowJob {
        if ( $currentDeepNodeAttributes != null && sizeof( $currentDeepNodeAttributes ) == 2 ) {
            $attr1 = $currentDeepNodeAttributes[0]; // DOMAttr
            $attr2 = $currentDeepNodeAttributes[1]; // DOMAttr

            if ( $attr1->name == "src" ) {
                $stackOverflowJob->setLogo( $attr1->value );
            }

            if ( $attr2->name == "src" ) {
                $stackOverflowJob->setLogo( $attr2->value );
            }
        }

        return $stackOverflowJob;
    }

    private function processDivBlock (
        DOMNodeList $currentDeepNodeChildren,
        StackOverflowJob $stackOverflowJob
    ) : StackOverflowJob {
        for ( $i = 0; $i < $currentDeepNodeChildren->count(); $i++ ) {
            $currentDeepNodeChildrenElement = $currentDeepNodeChildren->item( $i ); // DOMElement or DOMText

            if ( $currentDeepNodeChildrenElement instanceof DOMElement ) {
                $stackOverflowJob = $this->processH2AndH3Elements( $currentDeepNodeChildrenElement, $stackOverflowJob );
            }
        }

        return $stackOverflowJob;
    }

    private function refineCompanyName ( string $companyName ) : string
    {
        if ( strpos( $companyName, "\r\n" ) !== false ) {
            $companyNameParts = explode( "\r\n", $companyName );

            foreach ( $companyNameParts as $index => $currentCompanyNamePart ) {
                $companyNameParts[$index] = trim( $currentCompanyNamePart );
            }

            return implode( " ", $companyNameParts );
        } else {
            return $companyName;
        }
    }

    private function setCompanyAndLocation (
        DOMElement $element,
        StackOverflowJob $stackOverflowJob
    ) : StackOverflowJob {
        if ( $element->attributes->count() == 0 ) {
            $stackOverflowJob->setCompany( $this->refineCompanyName( trim( $element->nodeValue ) ) );
        }

        if ( $element->attributes->count() == 1 && $element->getAttribute( "class" ) == "fc-black-500" ) {
            $stackOverflowJob->setLocation( trim( $element->nodeValue ) );
        }

        return $stackOverflowJob;
    }

    private function processH2AndH3Elements (
        DOMElement $currentDeepNodeChildrenElement,
        StackOverflowJob $stackOverflowJob
    ) : StackOverflowJob {
        if ( $currentDeepNodeChildrenElement->tagName == "h2" ) {
            $stackOverflowJob->setTitle( trim( $currentDeepNodeChildrenElement->nodeValue ) );
        }

        if ( $currentDeepNodeChildrenElement->tagName == "h3" && $currentDeepNodeChildrenElement->hasChildNodes() ) {
            $companyAndLocationDomNodeList = $currentDeepNodeChildrenElement->childNodes; // DOMNodeList

            for ( $i = 0; $i < $companyAndLocationDomNodeList->count(); $i++ ) {
                $currentCompanyAndLocationElement = $companyAndLocationDomNodeList->item( $i ); // DOMElement or DOMText

                if ( $currentCompanyAndLocationElement instanceof DOMElement && $currentCompanyAndLocationElement->nodeName == "span" ) {
                    $this->setCompanyAndLocation( $currentCompanyAndLocationElement );
                }
            }
        }

        return $stackOverflowJob;
    }
}
