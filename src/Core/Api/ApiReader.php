<?php

namespace Coco\SourceWatcher\Core\Api;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;

/**
 * Class ApiReader
 *
 * When you describe the endpoint, you list the end path only (hence the term "end point").
 * The full path that contains both the base path and the endpoint is often called a resource URL.
 *
 * @package Coco\SourceWatcher\Core\Api
 */
class ApiReader implements Reader
{
    /**
     * @var string
     */
    protected ?string $resourceURL = null;

    /**
     * @var int
     */
    protected int $timeout = 5;

    /**
     * @var array
     */
    protected array $headers = [];

    /**
     * @var int
     */
    protected int $currentAttempt;

    /**
     * @var int
     */
    protected int $attempts = 3;

    /**
     * ApiReader constructor.
     */
    public function __construct ()
    {
        $this->currentAttempt = 1;
    }

    /**
     * @return string
     */
    public function getResourceURL () : string
    {
        return $this->resourceURL;
    }

    /**
     * @param string $resourceURL
     */
    public function setResourceURL ( string $resourceURL ) : void
    {
        $this->resourceURL = $resourceURL;
    }

    /**
     * @return int
     */
    public function getTimeout () : int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout ( int $timeout ) : void
    {
        $this->timeout = $timeout;
    }

    /**
     * @return array
     */
    public function getHeaders () : array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders ( array $headers ) : void
    {
        $this->headers = $headers;
    }

    /**
     * @return bool|string
     * @throws SourceWatcherException
     */
    public function read ()
    {
        if ( $this->resourceURL == null || $this->resourceURL == "" ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( "en_US", ApiReader::class, "No_Resource_URL_Found" ) );
        }

        $curl = curl_init();

        curl_setopt( $curl, CURLOPT_URL, $this->resourceURL );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, $this->timeout );

        if ( !empty( $this->headers ) ) {
            curl_setopt( $curl, CURLOPT_HTTPHEADER, $this->headers );
        }

        /**
         * https://www.php.net/manual/en/function.curl-exec.php
         *
         * Returns TRUE on success or FALSE on failure.
         * However, if the CURLOPT_RETURNTRANSFER option is set, it will return the result on success, FALSE on failure.
         */

        $response = curl_exec( $curl );

        curl_close( $curl );

        if ( $response == false ) {
            if ( $this->currentAttempt < $this->attempts ) {
                $this->currentAttempt++;
                return $this->read();
            }
        }

        return $response;
    }
}
