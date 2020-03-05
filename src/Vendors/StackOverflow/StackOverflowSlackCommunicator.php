<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

use Coco\SourceWatcher\Watcher\Communicator\SlackCommunicator;

class StackOverflowSlackCommunicator extends SlackCommunicator
{
    private array $dataArray;

    public function __construct ( array $listOfJobs )
    {
        parent::__construct();

        $this->buildDataArray( $listOfJobs );
    }

    public function setListOfJobs ( array $listOfJobs ) : void
    {
        $this->buildDataArray( $listOfJobs );
    }

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

    public function getDatArray () : array
    {
        return $this->dataArray;
    }

    public function send ()
    {
        foreach ( $this->dataArray as $currentSlackMessageBlock ) {
            $this->data = json_encode( $currentSlackMessageBlock );
            parent::send();
        }
    }
}
