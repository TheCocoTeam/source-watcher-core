<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

/**
 * Class StackOverflowJob
 *
 * @package Coco\SourceWatcher\Vendors\StackOverflow
 */
class StackOverflowJob
{
    private string $baseURL = "https://stackoverflow.com";

    private ?string $jobId = null;

    private ?string $resultId = null;

    private ?string $previewUrl = null;

    private ?string $logo = "https://pbs.twimg.com/profile_images/425274582581264384/X3QXBN8C.jpeg";

    private ?string $title = null;

    private ?string $company = null;

    private ?string $location = null;

    public function getJobId () : ?string
    {
        return $this->jobId;
    }

    public function setJobId ( ?string $jobId ) : void
    {
        $this->jobId = $jobId;
    }

    public function getResultId () : ?string
    {
        return $this->resultId;
    }

    public function setResultId ( ?string $resultId ) : void
    {
        $this->resultId = $resultId;
    }

    public function getPreviewUrl () : ?string
    {
        return $this->previewUrl;
    }

    public function setPreviewUrl ( ?string $previewUrl ) : void
    {
        $this->previewUrl = $previewUrl;
    }

    public function getLogo () : ?string
    {
        return $this->logo;
    }

    public function setLogo ( ?string $logo ) : void
    {
        $this->logo = $logo;
    }

    public function getTitle () : ?string
    {
        return $this->title;
    }

    public function setTitle ( ?string $title ) : void
    {
        $this->title = $title;
    }

    public function getCompany () : ?string
    {
        return $this->company;
    }

    public function setCompany ( ?string $company ) : void
    {
        $this->company = $company;
    }

    public function getLocation () : ?string
    {
        return $this->location;
    }

    public function setLocation ( ?string $location ) : void
    {
        $this->location = $location;
    }

    public function getRefinedUrl () : string
    {
        $currentPreviewUrl = substr( $this->previewUrl, 0,
            strlen( $this->baseURL ) ) === $this->baseURL ? $this->previewUrl : $this->baseURL . $this->previewUrl;

        $pieces = explode( "?", $currentPreviewUrl );

        $refinedUrl = "";

        if ( !empty( $pieces ) ) {
            $refinedUrl = $pieces[0];
        }

        return $refinedUrl;
    }

    public function allAttributesDefined () : bool
    {
        $variablesToValidate = [
            $this->jobId,
            $this->resultId,
            $this->previewUrl,
            $this->logo,
            $this->title,
            $this->company,
            $this->location
        ];

        foreach ( $variablesToValidate as $currentVariable ) {
            if ( empty( $currentVariable ) ) {
                return false;
            }
        }

        return true;
    }
}
