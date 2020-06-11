<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

/**
 * Class StackOverflowJob
 * @package Coco\SourceWatcher\Vendors\StackOverflow
 */
class StackOverflowJob
{
    /**
     * @var string
     */
    private string $baseURL = "https://stackoverflow.com";

    /**
     * @var string|null
     */
    private ?string $jobId = null;

    /**
     * @var string|null
     */
    private ?string $resultId = null;

    /**
     * @var string|null
     */
    private ?string $previewUrl = null;

    /**
     * @var string|null
     */
    private ?string $logo = "https://pbs.twimg.com/profile_images/425274582581264384/X3QXBN8C.jpeg";

    /**
     * @var string|null
     */
    private ?string $title = null;

    /**
     * @var string|null
     */
    private ?string $company = null;

    /**
     * @var string|null
     */
    private ?string $location = null;

    /**
     * @return string|null
     */
    public function getJobId () : ?string
    {
        return $this->jobId;
    }

    /**
     * @param string|null $jobId
     */
    public function setJobId ( ?string $jobId ) : void
    {
        $this->jobId = $jobId;
    }

    /**
     * @return string|null
     */
    public function getResultId () : ?string
    {
        return $this->resultId;
    }

    /**
     * @param string|null $resultId
     */
    public function setResultId ( ?string $resultId ) : void
    {
        $this->resultId = $resultId;
    }

    /**
     * @return string|null
     */
    public function getPreviewUrl () : ?string
    {
        return $this->previewUrl;
    }

    /**
     * @param string|null $previewUrl
     */
    public function setPreviewUrl ( ?string $previewUrl ) : void
    {
        $this->previewUrl = $previewUrl;
    }

    /**
     * @return string|null
     */
    public function getLogo () : ?string
    {
        return $this->logo;
    }

    /**
     * @param string|null $logo
     */
    public function setLogo ( ?string $logo ) : void
    {
        $this->logo = $logo;
    }

    /**
     * @return string|null
     */
    public function getTitle () : ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle ( ?string $title ) : void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getCompany () : ?string
    {
        return $this->company;
    }

    /**
     * @param string|null $company
     */
    public function setCompany ( ?string $company ) : void
    {
        $this->company = $company;
    }

    /**
     * @return string|null
     */
    public function getLocation () : ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     */
    public function setLocation ( ?string $location ) : void
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getRefinedUrl () : string
    {
        $currentPreviewUrl = substr( $this->previewUrl, 0, strlen( $this->baseURL ) ) === $this->baseURL ? $this->previewUrl : $this->baseURL . $this->previewUrl;

        $pieces = explode( "?", $currentPreviewUrl );

        $refinedUrl = "";

        if ( $pieces != null ) {
            if ( sizeof( $pieces ) >= 1 ) {
                $refinedUrl = $pieces[0];
            }
        }

        return $refinedUrl;
    }

    /**
     * @return bool
     */
    public function allAttributesDefined () : bool
    {
        $variablesToValidate = [ $this->jobId, $this->resultId, $this->previewUrl, $this->logo, $this->title, $this->company, $this->location ];

        foreach ( $variablesToValidate as $currentVariable ) {
            if ( empty( $currentVariable ) ) {
                return false;
            }
        }

        return true;
    }
}
