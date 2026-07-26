<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Step\ExecutionTransformer;
use DateTimeImmutable;
use Throwable;

/**
 * Stably sort an extracted result by one or more fields.
 */
class SortRowsTransformer extends ExecutionTransformer
{
    protected array $fields = [];

    protected array $availableOptions = [ "fields" ];

    /**
     * @throws SourceWatcherException
     */
    public function transformRows ( array $rows ) : array
    {
        $fields = $this->normalizeFields();

        $decorated = [];

        foreach ( $rows as $index => $row ) {
            $decorated[] = [ "index" => $index, "row" => $row ];
        }

        usort( $decorated, function ( array $left, array $right ) use ( $fields ) : int {
            foreach ( $fields as $field ) {
                $comparison = $this->compareValues(
                    $left["row"]->get( $field["field"] ),
                    $right["row"]->get( $field["field"] ),
                    $field
                );

                if ( $comparison !== 0 ) {
                    return $comparison;
                }
            }

            return $left["index"] <=> $right["index"];
        } );

        return array_map( fn( array $item ) => $item["row"], $decorated );
    }

    /**
     * @throws SourceWatcherException
     */
    private function normalizeFields () : array
    {
        if ( empty( $this->fields ) ) {
            throw new SourceWatcherException( "Sort Rows requires at least one field." );
        }

        $normalized = [];

        foreach ( $this->fields as $field ) {
            if ( is_string( $field ) ) {
                $field = [ "field" => $field ];
            }

            if ( !is_array( $field ) ) {
                throw new SourceWatcherException( "Each Sort Rows field must be a string or object." );
            }

            $name = isset( $field["field"] ) && is_string( $field["field"] )
                ? trim( $field["field"] )
                : "";
            $direction = isset( $field["direction"] ) && is_string( $field["direction"] )
                ? strtolower( trim( $field["direction"] ) )
                : "asc";
            $type = isset( $field["type"] ) && is_string( $field["type"] )
                ? strtolower( trim( $field["type"] ) )
                : "auto";
            $nulls = isset( $field["nulls"] ) && is_string( $field["nulls"] )
                ? strtolower( trim( $field["nulls"] ) )
                : "last";

            if ( $name === "" ) {
                throw new SourceWatcherException( "Each Sort Rows field requires a field name." );
            }

            if ( !in_array( $direction, [ "asc", "desc" ], true ) ) {
                throw new SourceWatcherException( 'Sort Rows direction must be either "asc" or "desc".' );
            }

            if ( !in_array( $type, [ "auto", "numeric", "text", "date" ], true ) ) {
                throw new SourceWatcherException( 'Sort Rows type must be "auto", "numeric", "text", or "date".' );
            }

            if ( !in_array( $nulls, [ "first", "last" ], true ) ) {
                throw new SourceWatcherException( 'Sort Rows nulls must be either "first" or "last".' );
            }

            $normalized[] = [
                "field" => $name,
                "direction" => $direction,
                "type" => $type,
                "nulls" => $nulls,
            ];
        }

        return $normalized;
    }

    /**
     * @throws SourceWatcherException
     */
    private function compareValues ( mixed $left, mixed $right, array $field ) : int
    {
        if ( $left === null || $right === null ) {
            if ( $left === null && $right === null ) {
                return 0;
            }

            if ( $left === null ) {
                return $field["nulls"] === "first" ? -1 : 1;
            }

            return $field["nulls"] === "first" ? 1 : -1;
        }

        $comparison = match ( $field["type"] ) {
            "numeric" => $this->compareNumeric( $left, $right, $field["field"] ),
            "text" => strcmp( (string) $left, (string) $right ),
            "date" => $this->compareDates( $left, $right, $field["field"] ),
            default => $this->compareAutomatically( $left, $right ),
        };

        return $field["direction"] === "desc" ? -$comparison : $comparison;
    }

    /**
     * @throws SourceWatcherException
     */
    private function compareNumeric ( mixed $left, mixed $right, string $field ) : int
    {
        if ( !is_numeric( $left ) || !is_numeric( $right ) ) {
            throw new SourceWatcherException(
                sprintf( 'Sort Rows field "%s" contains a non-numeric value.', $field )
            );
        }

        return (float) $left <=> (float) $right;
    }

    /**
     * @throws SourceWatcherException
     */
    private function compareDates ( mixed $left, mixed $right, string $field ) : int
    {
        try {
            $leftDate = new DateTimeImmutable( (string) $left );
            $rightDate = new DateTimeImmutable( (string) $right );
        } catch ( Throwable ) {
            throw new SourceWatcherException(
                sprintf( 'Sort Rows field "%s" contains an invalid date value.', $field )
            );
        }

        return $leftDate->getTimestamp() <=> $rightDate->getTimestamp();
    }

    private function compareAutomatically ( mixed $left, mixed $right ) : int
    {
        if ( is_numeric( $left ) && is_numeric( $right ) ) {
            return (float) $left <=> (float) $right;
        }

        return strcmp( (string) $left, (string) $right );
    }
}
