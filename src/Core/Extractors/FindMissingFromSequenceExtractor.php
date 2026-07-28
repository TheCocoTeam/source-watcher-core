<?php

namespace Coco\SourceWatcher\Core\Extractors;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;

/**
 * Class FindMissingFromSequenceExtractor
 *
 * @package Coco\SourceWatcher\Core\Extractors
 */
class FindMissingFromSequenceExtractor extends ExecutionExtractor
{
    protected string $filterField;

    protected array $availableOptions = [ "filterField" ];

    public function __construct ()
    {
        $this->filterField = "id";
    }

    /**
     * @return string
     */
    public function getFilterField () : string
    {
        return $this->filterField;
    }

    /**
     * @param string $filterField
     */
    public function setFilterField ( string $filterField ) : void
    {
        $this->filterField = $filterField;
    }

    /**
     * @return array
     * @throws SourceWatcherException
     */
    public function extract ()
    {
        $previousExtractorResult = parent::extract();

        if ( $previousExtractorResult === [] ) {
            return $this->result;
        }

        $copy = [];

        foreach ( $previousExtractorResult as $index => $currentRow ) {
            if ( !$currentRow instanceof Row ) {
                throw new SourceWatcherException( sprintf(
                    "Find Missing From Sequence requires every input item to be a Row; item %s is %s.",
                    $index,
                    get_debug_type( $currentRow )
                ) );
            }

            if ( !$currentRow->offsetExists( $this->filterField ) ) {
                throw new SourceWatcherException( sprintf(
                    'Find Missing From Sequence requires field "%s" on every input row; row %s does not contain it.',
                    $this->filterField,
                    $index
                ) );
            }

            $value = $currentRow[$this->filterField];

            if (
                !is_int( $value )
                && !( is_float( $value ) && is_finite( $value ) && floor( $value ) === $value )
                && !( is_string( $value ) && preg_match( '/^[+-]?\d+$/', trim( $value ) ) === 1 )
            ) {
                throw new SourceWatcherException( sprintf(
                    'Find Missing From Sequence requires field "%s" to contain an integer value; row %s contains %s.',
                    $this->filterField,
                    $index,
                    get_debug_type( $value )
                ) );
            }

            $copy[] = (int) $value;
        }

        asort( $copy );

        $min = reset( $copy );
        $max = end( $copy );

        $this->result = [];

        for ( $i = $min; $i <= $max; $i++ ) {
            if ( !in_array( $i, $copy, true ) ) {
                $this->result[] = new Row( [ $this->filterField => $i ] );
            }
        }

        return $this->result;
    }
}
