<?php

namespace Coco\SourceWatcher\Core\Api;

use Coco\SourceWatcher\Core\SourceWatcherException;

/**
 * Class ApiReader
 * @package Coco\SourceWatcher\Core\Api
 */
class ApiReader implements Reader
{
    /**
     * @var string
     */
    protected string $endpoint;

    /**
     * @var int
     */
    protected int $timeout = 5;

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
    public function getEndpoint () : string
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint ( string $endpoint ) : void
    {
        $this->endpoint = $endpoint;
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
     * @return bool|string
     * @throws SourceWatcherException
     */
    public function read ()
    {
        if ( $this->endpoint == null || $this->endpoint == "" ) {
            throw new SourceWatcherException( "No endpoint found." );
        }

        $curl = curl_init();

        curl_setopt( $curl, CURLOPT_URL, $this->endpoint );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, $this->timeout );

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
                $this->read();
            }
        }

        return $response;
    }
}
