<?php

namespace Coco\SourceWatcher\Watcher\Communicator;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\Internationalization;

/**
 * Class SlackCommunicator
 *
 * @package Coco\SourceWatcher\Watcher\Communicator
 */
class SlackCommunicator implements Communicator
{
    protected string $webHookUrl = "";

    protected string $method = "";

    protected string $contentType = "";

    protected string $data = "";

    public function __construct ()
    {
        $this->method = "POST";
        $this->contentType = "Content-Type: application/json";
    }

    public function getWebHookUrl () : string
    {
        return $this->webHookUrl;
    }

    public function setWebHookUrl ( string $webHookUrl ) : void
    {
        $this->webHookUrl = $webHookUrl;
    }

    public function getMethod () : string
    {
        return $this->method;
    }

    public function setMethod ( string $method ) : void
    {
        $this->method = $method;
    }

    public function getContentType () : string
    {
        return $this->contentType;
    }

    public function setContentType ( string $contentType ) : void
    {
        $this->contentType = $contentType;
    }

    public function getData () : string
    {
        return $this->data;
    }

    public function setData ( string $data ) : void
    {
        $this->data = $data;
    }

    /**
     * @return bool|string
     * @throws SourceWatcherException
     */
    public function send ()
    {
        if ( empty( $this->webHookUrl ) ) {
            throw new SourceWatcherException( Internationalization::getInstance()->getText( SlackCommunicator::class,
                "No_Web_Hook" ) );
        }

        if ( empty( $this->method ) ) {
            throw new SourceWatcherException( Internationalization::getInstance()->getText( SlackCommunicator::class,
                "No_Method" ) );
        }

        if ( empty( $this->contentType ) ) {
            throw new SourceWatcherException( Internationalization::getInstance()->getText( SlackCommunicator::class,
                "No_Content_Type" ) );
        }

        if ( empty( $this->data ) ) {
            throw new SourceWatcherException( Internationalization::getInstance()->getText( SlackCommunicator::class,
                "No_Data" ) );
        }

        $ch = curl_init( $this->webHookUrl );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $this->method );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, [ $this->contentType, "Content-Length: " . strlen( $this->data ) ] );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->data );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        return curl_exec( $ch );
    }
}
