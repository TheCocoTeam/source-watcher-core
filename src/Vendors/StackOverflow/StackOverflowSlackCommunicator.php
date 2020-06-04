<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

use Coco\SourceWatcher\Watcher\Communicator\SlackCommunicator;
use Exception;

/**
 * Class StackOverflowSlackCommunicator
 * @package Coco\SourceWatcher\Vendors\StackOverflow
 */
class StackOverflowSlackCommunicator extends SlackCommunicator
{
    /**
     * @var array
     */
    private array $dataArray;

    /**
     * StackOverflowSlackCommunicator constructor.
     * @param array $listOfJobs
     */
    public function __construct ( array $listOfJobs )
    {
        parent::__construct();

        $this->buildDataArray( $listOfJobs );
    }

    /**
     * @param array $listOfJobs
     */
    private function buildDataArray ( array $listOfJobs ) : void
    {
        $this->dataArray = array();

        foreach ( $listOfJobs as $currentJob ) {
            $currentArray = array( "blocks" => array() );

            array_push( $currentArray["blocks"], array( "type" => "divider" ) );

            $currentSection = array( "type" => "section", "text" => null, "accessory" => null );

            $text = "*" . $currentJob->getTitle() . "*" . PHP_EOL;
            $text .= $currentJob->getCompany() . " | " . $currentJob->getLocation() . PHP_EOL;
            $text .= "<" . $currentJob->getRefinedUrl() . ">";
            $currentSection["text"] = array( "type" => "mrkdwn", "text" => $text );

            $currentSection["accessory"] = array( "type" => "image", "image_url" => $currentJob->getLogo(), "alt_text" => $currentJob->getCompany() );

            array_push( $currentArray["blocks"], $currentSection );

            array_push( $currentArray["blocks"], array( "type" => "divider" ) );

            array_push( $this->dataArray, $currentArray );
        }
    }

    /**
     * @return bool|string|void
     * @throws Exception
     */
    public function send ()
    {
        foreach ( $this->dataArray as $currentSlackMessageBlock ) {
            $this->data = json_encode( $currentSlackMessageBlock );
            parent::send();
        }
    }
}
