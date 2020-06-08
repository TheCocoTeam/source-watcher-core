<?php

namespace Coco\SourceWatcher\Watcher\Communicator;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;

/**
 * Class SlackCommunicator
 * @package Coco\SourceWatcher\Watcher\Communicator
 */
class SlackCommunicator implements Communicator
{
    /**
     * @var string
     */
    protected string $webHookUrl = "";

    /**
     * @var string
     */
    protected string $method = "";

    /**
     * @var string
     */
    protected string $contentType = "";

    /**
     * @var string
     */
    protected string $data = "";

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
     * @throws SourceWatcherException
     */
    public function send ()
    {
        if ( empty( $this->webHookUrl ) ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( SlackCommunicator::class, "No_Web_Hook" ) );
        }

        if ( empty( $this->method ) ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( SlackCommunicator::class, "No_Method" ) );
        }

        if ( empty( $this->contentType ) ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( SlackCommunicator::class, "No_Content_Type" ) );
        }

        if ( empty( $this->data ) ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( SlackCommunicator::class, "No_Data" ) );
        }

        $ch = curl_init( $this->webHookUrl );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $this->method );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( $this->contentType, "Content-Length: " . strlen( $this->data ) ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->data );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        return curl_exec( $ch );
    }
}
