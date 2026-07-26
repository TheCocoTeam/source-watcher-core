<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Step\ExecutionTransformer;

/**
 * Remove duplicate rows using one or more key fields.
 */
class DeduplicateRowsTransformer extends ExecutionTransformer
{
    protected array $keyFields = [];

    protected string $keep = "first";

    protected ?string $orderField = null;

    protected string $orderDirection = "asc";

    protected array $availableOptions = [ "keyFields", "keep", "orderField", "orderDirection" ];

    /**
     * @throws SourceWatcherException
     */
    public function transformRows ( array $rows ) : array
    {
        $keyFields = $this->normalizeKeyFields();
        $keep = strtolower( trim( $this->keep ) );
        $orderDirection = strtolower( trim( $this->orderDirection ) );
        $orderField = is_string( $this->orderField ) ? trim( $this->orderField ) : "";

        if ( !in_array( $keep, [ "first", "last" ], true ) ) {
            throw new SourceWatcherException( 'Deduplicate Rows keep must be either "first" or "last".' );
        }

        if ( !in_array( $orderDirection, [ "asc", "desc" ], true ) ) {
            throw new SourceWatcherException( 'Deduplicate Rows orderDirection must be either "asc" or "desc".' );
        }

        $selected = [];

        foreach ( $rows as $index => $row ) {
            if ( !( $row instanceof Row ) ) {
                throw new SourceWatcherException( "Deduplicate Rows requires Row instances." );
            }

            $key = $this->buildKey( $row, $keyFields );

            if ( !isset( $selected[$key] ) ) {
                $selected[$key] = [ "index" => $index, "row" => $row ];
                continue;
            }

            if ( $this->shouldReplace( $selected[$key]["row"], $row, $keep, $orderField, $orderDirection ) ) {
                $selected[$key] = [ "index" => $index, "row" => $row ];
            }
        }

        usort( $selected, fn( array $left, array $right ) => $left["index"] <=> $right["index"] );

        return array_map( fn( array $item ) => $item["row"], $selected );
    }

    /**
     * @throws SourceWatcherException
     */
    private function normalizeKeyFields () : array
    {
        $fields = [];

        foreach ( $this->keyFields as $field ) {
            if ( !is_string( $field ) || trim( $field ) === "" ) {
                throw new SourceWatcherException( "Deduplicate Rows keyFields must contain non-empty field names." );
            }

            $fields[] = trim( $field );
        }

        if ( empty( $fields ) ) {
            throw new SourceWatcherException( "Deduplicate Rows requires at least one key field." );
        }

        return $fields;
    }

    private function buildKey ( Row $row, array $keyFields ) : string
    {
        $values = [];

        foreach ( $keyFields as $field ) {
            $values[] = [
                "exists" => $row->offsetExists( $field ),
                "value" => $row->get( $field ),
            ];
        }

        return serialize( $values );
    }

    private function shouldReplace (
        Row $current,
        Row $candidate,
        string $keep,
        string $orderField,
        string $orderDirection
    ) : bool {
        if ( $orderField === "" ) {
            return $keep === "last";
        }

        $comparison = $this->compareValues( $candidate->get( $orderField ), $current->get( $orderField ) );

        if ( $orderDirection === "desc" ) {
            $comparison = -$comparison;
        }

        if ( $comparison === 0 ) {
            return $keep === "last";
        }

        return $keep === "first" ? $comparison < 0 : $comparison > 0;
    }

    private function compareValues ( mixed $left, mixed $right ) : int
    {
        if ( $left === null || $right === null ) {
            if ( $left === null && $right === null ) {
                return 0;
            }

            return $left === null ? 1 : -1;
        }

        if ( is_numeric( $left ) && is_numeric( $right ) ) {
            return (float) $left <=> (float) $right;
        }

        return strcmp( (string) $left, (string) $right );
    }
}
