<?php

namespace Coco\SourceWatcher\Vendors\StackOverflow;

class StackOverflowJob
{
    private string $baseURL = "https://stackoverflow.com";

    private ?string $jobId = null;
    private ?string $resultId = null;
    private ?string $previewUrl = null;
    private ?string $logo = "https://pbs.twimg.com/profile_images/425274582581264384/X3QXBN8C.jpeg"; // https://cdn.sstatic.net/careers/Img/ico-no-company-logo.svg
    private ?string $title = null;
    private ?string $company = null;
    private ?string $location = null;

    public function __construct ()
    {

    }

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

    public function allAttributesDefined () : bool
    {
        if ( $this->jobId == null ) {
            return false;
        }

        if ( $this->resultId == null ) {
            return false;
        }

        if ( $this->previewUrl == null ) {
            return false;
        }

        if ( $this->logo == null ) {
            return false;
        }

        if ( $this->title == null ) {
            return false;
        }

        if ( $this->company == null ) {
            return false;
        }

        if ( $this->location == null ) {
            return false;
        }

        return true;
    }

    public function __toString () : string
    {
        $result = "";
        $result .= "job id: " . $this->jobId . PHP_EOL;
        $result .= "result id: " . $this->resultId . PHP_EOL;
        $result .= "preview url: " . $this->previewUrl . PHP_EOL;
        $result .= "logo: " . $this->logo . PHP_EOL;
        $result .= "title: " . $this->title . PHP_EOL;
        $result .= "company: " . $this->company . PHP_EOL;
        $result .= "location: " . $this->location . PHP_EOL;
        return $result;
    }
}
