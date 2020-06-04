<?php

namespace Coco\SourceWatcher\Watcher\Communicator;

use Exception;

/**
 * Class SlackCommunicator
 * @package Coco\SourceWatcher\Watcher\Communicator
 */
class SlackCommunicator implements Communicator
{
    /**
     * @var string
     */
    protected string $webHookUrl;

    /**
     * @var string
     */
    protected string $method;

    /**
     * @var string
     */
    protected string $contentType;

    /**
     * @var string
     */
    protected string $data;

    /**
     * SlackCommunicator constructor.
     */
    public function __construct ()
    {
        $this->method = "POST";
        $this->contentType = "Content-Type: application/json";
    }

    /**
     * @return string
     */
    public function getWebHookUrl () : string
    {
        return $this->webHookUrl;
    }

    /**
     * @param string $webHookUrl
     */
    public function setWebHookUrl ( string $webHookUrl ) : void
    {
        $this->webHookUrl = $webHookUrl;
    }

    /**
     * @return string
     */
    public function getMethod () : string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod ( string $method ) : void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getContentType () : string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType ( string $contentType ) : void
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getData () : string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData ( string $data ) : void
    {
        $this->data = $data;
    }

    /**
     * @return bool|string
     * @throws Exception
     */
    public function send ()
    {
        if ( $this->webHookUrl == null || $this->webHookUrl == "" ) {
            throw new Exception( "A web hook url must be defined first." );
        }

        if ( $this->method == null || $this->method == "" ) {
            throw new Exception( "A method must be defined first." );
        }

        if ( $this->contentType == null || $this->contentType == "" ) {
            throw new Exception( "A content type be defined first." );
        }

        if ( $this->data == null || $this->data == "" ) {
            throw new Exception( "Data must be defined first." );
        }

        $ch = curl_init( $this->webHookUrl );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $this->method );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( $this->contentType, 'Content-Length: ' . strlen( $this->data ) ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->data );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        return curl_exec( $ch );
    }
}
