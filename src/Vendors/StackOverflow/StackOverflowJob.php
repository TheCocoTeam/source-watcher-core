<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

class StackOverflowJob {
    private ?string $jobId = null;
    private ?string $resultId = null;
    private ?string $previewUrl = null;

    private string $baseURL = "https://stackoverflow.com/";

    public function __construct () {

    }

    public function getJobId () : string {
        return $this->jobId;
    }

    public function setJobId ( string $jobId ) : void {
        $this->jobId = $jobId;
    }

    public function getResultId () : string {
        return $this->resultId;
    }

    public function setResultId ( string $resultId ) : void {
        $this->resultId = $resultId;
    }

    public function getPreviewUrl () : string {
        if ( substr( $this->previewUrl, 0, strlen( $this->baseURL ) ) === $this->baseURL ) {
            return $this->previewUrl;
        } else {
            return $this->baseURL . $this->previewUrl;
        }
    }

    public function setPreviewUrl ( string $previewUrl ) : void {
        $this->previewUrl = $previewUrl;
    }

    public function allAttributesDefined () : bool {
        if ( $this->jobId == null ) {
            return false;
        }

        if ( $this->resultId == null ) {
            return false;
        }

        if ( $this->previewUrl == null ) {
            return false;
        }

        return true;
    }

    public function __toString () : string {
        $result = "";
        $result .= "job id: " . $this->jobId . PHP_EOL;
        $result .= "result id: " . $this->resultId . PHP_EOL;
        $result .= "preview url: " . $this->previewUrl . PHP_EOL;
        return $result;
    }
}
