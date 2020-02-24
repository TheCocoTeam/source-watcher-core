<?php

namespace Coco\SourceWatcher\Source\StackOverflow;

use Coco\SourceWatcher\Source\WebPageSource;

/**
 * Class StackOverflowPHPJobsWebPageSource
 * @package Coco\SourceWatcher\Source\StackOverflow
 *
 * This is just a test class and should be more generic!
 */
class StackOverflowPHPJobsWebPageSource extends WebPageSource {
    private string $jobsUrl = "https://stackoverflow.com/jobs?q=PHP&l=Florida+USA&d=100&u=Miles";

    public function __construct () {
        parent::__construct( $this->jobsUrl );
    }

    public function getResults() {
        $handler = parent::getHandler();
        $dom = $handler->getDom();
        $resultElements = $dom->find( '.listResults' );
        $jobListElements = $resultElements->getChildren();
        $results = array();

        foreach ( $jobListElements as $currentJobElement ) {
            $jobId = $currentJobElement->getAttribute( 'data-jobid' );
            $resultId = $currentJobElement->getAttribute( 'data-result-id' );
            $previewUrl = $currentJobElement->getAttribute( 'data-preview-url' );

            if ( $jobId != null && $resultId != null && $previewUrl != null ) {
                $currentJob = new StackOverflowJob();
                $currentJob->setJobId( $jobId );
                $currentJob->setResultId( $resultId );
                $currentJob->setPreviewUrl( $previewUrl );

                array_push( $results, $currentJob );
            }
        }

        return $results;
    }
}
