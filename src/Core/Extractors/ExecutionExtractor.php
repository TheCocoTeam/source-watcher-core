<?php

namespace Coco\SourceWatcher\Core\Extractors;

use Coco\SourceWatcher\Core\Step\Extractor;
use Coco\SourceWatcher\Core\IO\Inputs\ExtractorResultInput;
use Coco\SourceWatcher\Core\IO\Inputs\ResultSetInput;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;

/**
 * Class ExecutionExtractor
 *
 * @package Coco\SourceWatcher\Core\Extractors
 */
class ExecutionExtractor extends Extractor
{
    /**
     * @return array
     * @throws SourceWatcherException
     */
    public function extract ()
    {
        if ( $this->input == null ) {
            throw new SourceWatcherException( "An input must be provided." );
        }

        if ( $this->input instanceof ResultSetInput ) {
            $this->result = $this->input->getInput();

            return $this->result;
        }

        if ( $this->input instanceof ExtractorResultInput ) {
            $previousExtractor = $this->input->getInput();

            if ( $previousExtractor === null ) {
                throw new SourceWatcherException( "The previous extractor must be provided." );
            }

            $this->result = $previousExtractor->getResult();

            return $this->result;
        }

        throw new SourceWatcherException( sprintf(
            "The input must be an instance of %s or %s",
            ResultSetInput::class,
            ExtractorResultInput::class
        ) );
    }
}
