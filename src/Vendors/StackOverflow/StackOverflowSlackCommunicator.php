<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

use Coco\SourceWatcher\Watcher\Communicator\SlackCommunicator;

/**
 * Class StackOverflowSlackCommunicator
 *
 * @package Coco\SourceWatcher\Vendors\StackOverflow
 */
class StackOverflowSlackCommunicator extends SlackCommunicator
{
    private array $dataArray;

    public static string $BLOCKS_INDEX = "blocks";

    public function __construct ( array $listOfJobs )
    {
        parent::__construct();

        $this->buildDataArray( $listOfJobs );
    }

    private function buildDataArray ( array $listOfJobs ) : void
    {
        $this->dataArray = [];

        foreach ( $listOfJobs as $currentJob ) {
            $currentArray = [ StackOverflowSlackCommunicator::$BLOCKS_INDEX => [] ];

            array_push( $currentArray[StackOverflowSlackCommunicator::$BLOCKS_INDEX], [ "type" => "divider" ] );

            $currentSection = [ "type" => "section", "text" => null, "accessory" => null ];

            $text = "*" . $currentJob->getTitle() . "*" . PHP_EOL;
            $text .= $currentJob->getCompany() . " | " . $currentJob->getLocation() . PHP_EOL;
            $text .= "<" . $currentJob->getRefinedUrl() . ">";
            $currentSection["text"] = [ "type" => "mrkdwn", "text" => $text ];

            $currentSection["accessory"] = [
                "type" => "image",
                "image_url" => $currentJob->getLogo(),
                "alt_text" => $currentJob->getCompany()
            ];

            array_push( $currentArray[StackOverflowSlackCommunicator::$BLOCKS_INDEX], $currentSection );

            array_push( $currentArray[StackOverflowSlackCommunicator::$BLOCKS_INDEX], [ "type" => "divider" ] );

            array_push( $this->dataArray, $currentArray );
        }
    }

    public function send ()
    {
        try {
            foreach ( $this->dataArray as $currentSlackMessageBlock ) {
                $this->data = json_encode( $currentSlackMessageBlock );

                parent::send();
            }

            return true;
        } catch ( Exception $e ) {
            return false;
        }
    }
}
