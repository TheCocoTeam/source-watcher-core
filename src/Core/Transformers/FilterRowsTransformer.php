<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Step\Transformer;

/**
 * Keep rows that match configured conditions.
 *
 * Returning false tells the pipeline to remove the current row. Existing
 * mutating transformers return null, which continues to keep the row.
 */
class FilterRowsTransformer extends Transformer
{
    public const MATCH_ALL = "all";

    public const MATCH_ANY = "any";

    protected array $conditions = [];

    protected string $match = self::MATCH_ALL;

    protected array $availableOptions = [ "conditions", "match" ];

    /**
     * @throws SourceWatcherException
     */
    public function transform ( Row $row ) : bool
    {
        if ( empty( $this->conditions ) ) {
            throw new SourceWatcherException( "Filter Rows requires at least one condition." );
        }

        $match = strtolower( trim( $this->match ) );

        if ( !in_array( $match, [ self::MATCH_ALL, self::MATCH_ANY ], true ) ) {
            throw new SourceWatcherException( 'Filter Rows match must be either "all" or "any".' );
        }

        foreach ( $this->conditions as $condition ) {
            $conditionMatches = $this->matchesCondition( $row, $condition );

            if ( $match === self::MATCH_ALL && !$conditionMatches ) {
                return false;
            }

            if ( $match === self::MATCH_ANY && $conditionMatches ) {
                return true;
            }
        }

        return $match === self::MATCH_ALL;
    }

    /**
     * @throws SourceWatcherException
     */
    private function matchesCondition ( Row $row, mixed $condition ) : bool
    {
        if ( !is_array( $condition ) ) {
            throw new SourceWatcherException( "Each Filter Rows condition must be an object." );
        }

        $field = isset( $condition["field"] ) && is_string( $condition["field"] )
            ? trim( $condition["field"] )
            : "";
        $operator = isset( $condition["operator"] ) && is_string( $condition["operator"] )
            ? strtolower( trim( $condition["operator"] ) )
            : "";

        if ( $field === "" ) {
            throw new SourceWatcherException( "Each Filter Rows condition requires a field." );
        }

        if ( $operator === "" ) {
            throw new SourceWatcherException( "Each Filter Rows condition requires an operator." );
        }

        $actual = $row->get( $field );
        $expected = $condition["value"] ?? null;

        return match ( $operator ) {
            "equals" => $actual == $expected,
            "notequals" => $actual != $expected,
            "contains" => $this->contains( $actual, $expected ),
            "regex" => $this->matchesRegex( $actual, $expected ),
            "in" => $this->isIn( $actual, $expected ),
            "greaterthan" => $this->compareNumeric( $actual, $expected, "greaterThan" ) > 0,
            "lessthan" => $this->compareNumeric( $actual, $expected, "lessThan" ) < 0,
            "isnull" => $actual === null,
            "isempty" => $actual === null || $actual === "" || $actual === [],
            default => throw new SourceWatcherException(
                sprintf( 'Unsupported Filter Rows operator "%s".', $condition["operator"] )
            )
        };
    }

    /**
     * @throws SourceWatcherException
     */
    private function contains ( mixed $actual, mixed $expected ) : bool
    {
        if ( is_array( $actual ) ) {
            return in_array( $expected, $actual, true );
        }

        if ( !is_scalar( $actual ) || !is_scalar( $expected ) ) {
            throw new SourceWatcherException( "Filter Rows contains requires string, numeric, or array values." );
        }

        return str_contains( (string) $actual, (string) $expected );
    }

    /**
     * @throws SourceWatcherException
     */
    private function matchesRegex ( mixed $actual, mixed $expected ) : bool
    {
        if ( !is_scalar( $actual ) || !is_string( $expected ) || $expected === "" ) {
            throw new SourceWatcherException( "Filter Rows regex requires a scalar field and a non-empty pattern." );
        }

        $matched = @preg_match( $expected, (string) $actual );

        if ( $matched === false ) {
            throw new SourceWatcherException( sprintf( 'Invalid Filter Rows regex pattern "%s".', $expected ) );
        }

        return $matched === 1;
    }

    /**
     * @throws SourceWatcherException
     */
    private function isIn ( mixed $actual, mixed $expected ) : bool
    {
        if ( !is_array( $expected ) ) {
            throw new SourceWatcherException( "Filter Rows in requires an array value." );
        }

        return in_array( $actual, $expected, true );
    }

    /**
     * @throws SourceWatcherException
     */
    private function compareNumeric ( mixed $actual, mixed $expected, string $operator ) : int
    {
        if ( !is_numeric( $actual ) || !is_numeric( $expected ) ) {
            throw new SourceWatcherException(
                sprintf( "Filter Rows %s requires numeric values.", $operator )
            );
        }

        return (float) $actual <=> (float) $expected;
    }
}
