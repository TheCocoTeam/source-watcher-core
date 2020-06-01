<?php

namespace Coco\SourceWatcher\Core;

use Exception;
use Throwable;

/**
 * Class SourceWatcherException
 * @package Coco\SourceWatcher\Core
 */
class SourceWatcherException extends Exception
{
    /**
     * SourceWatcherException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct ( $message = "", $code = 0, Throwable $previous = null )
    {
        parent::__construct( $message, $code, $previous );
    }
}
