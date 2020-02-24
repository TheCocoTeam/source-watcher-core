<?php

namespace Coco\SourceWatcher\Source\StackOverflow;

class StackOverflowJob {
    private string $jobId;
    private string $resultId;
    private string $previewUrl;

    public function getJobId() {
        return $this->jobId;
    }

    public function setJobId($jobId): void {
        $this->jobId = $jobId;
    }

    public function getResultId() {
        return $this->resultId;
    }

    public function setResultId($resultId): void {
        $this->resultId = $resultId;
    }

    public function getPreviewUrl() {
        return $this->previewUrl;
    }

    public function setPreviewUrl($previewUrl): void {
        $this->previewUrl = $previewUrl;
    }

    public function __toString() {
        $result = "";
        $result .= "job id: " . $this->jobId . PHP_EOL;
        $result .= "result id: " . $this->resultId . PHP_EOL;
        $result .= "preview url: " . $this->previewUrl . PHP_EOL;
        return $result;
    }
}
