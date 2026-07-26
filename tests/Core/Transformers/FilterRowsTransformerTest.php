<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Transformers\FilterRowsTransformer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FilterRowsTransformerTest extends TestCase
{
    public static function conditionProvider () : array
    {
        return [
            "equals" => [ [ "field" => "status", "operator" => "equals", "value" => "active" ], true ],
            "not equals" => [ [ "field" => "status", "operator" => "notEquals", "value" => "inactive" ], true ],
            "contains string" => [ [ "field" => "name", "operator" => "contains", "value" => "ver" ], true ],
            "contains array" => [ [ "field" => "tags", "operator" => "contains", "value" => "etl" ], true ],
            "regex" => [ [ "field" => "email", "operator" => "regex", "value" => "/@example\\.com$/" ], true ],
            "in" => [ [ "field" => "status", "operator" => "in", "value" => [ "active", "pending" ] ], true ],
            "greater than" => [ [ "field" => "id", "operator" => "greaterThan", "value" => 5 ], true ],
            "less than" => [ [ "field" => "id", "operator" => "lessThan", "value" => 10 ], true ],
            "is null" => [ [ "field" => "deleted_at", "operator" => "isNull" ], true ],
            "is empty" => [ [ "field" => "notes", "operator" => "isEmpty" ], true ],
            "non-match" => [ [ "field" => "status", "operator" => "equals", "value" => "inactive" ], false ],
        ];
    }

    #[DataProvider( "conditionProvider" )]
    public function testSupportedConditions ( array $condition, bool $expected ) : void
    {
        $transformer = new FilterRowsTransformer();
        $transformer->options( [ "conditions" => [ $condition ] ] );

        $row = new Row( [
            "id" => "7",
            "name" => "Avery",
            "email" => "avery@example.com",
            "status" => "active",
            "tags" => [ "etl", "php" ],
            "deleted_at" => null,
            "notes" => "",
        ] );

        $this->assertSame( $expected, $transformer->transform( $row ) );
    }

    public function testMatchAllRequiresEveryCondition () : void
    {
        $transformer = new FilterRowsTransformer();
        $transformer->options( [
            "match" => "all",
            "conditions" => [
                [ "field" => "id", "operator" => "greaterThan", "value" => 5 ],
                [ "field" => "status", "operator" => "equals", "value" => "inactive" ],
            ],
        ] );

        $this->assertFalse( $transformer->transform( new Row( [ "id" => 7, "status" => "active" ] ) ) );
    }

    public function testMatchAnyKeepsRowWhenOneConditionMatches () : void
    {
        $transformer = new FilterRowsTransformer();
        $transformer->options( [
            "match" => "any",
            "conditions" => [
                [ "field" => "id", "operator" => "lessThan", "value" => 5 ],
                [ "field" => "status", "operator" => "equals", "value" => "active" ],
            ],
        ] );

        $this->assertTrue( $transformer->transform( new Row( [ "id" => 7, "status" => "active" ] ) ) );
    }

    public function testMissingFieldIsNull () : void
    {
        $transformer = new FilterRowsTransformer();
        $transformer->options( [
            "conditions" => [
                [ "field" => "missing", "operator" => "isNull" ],
            ],
        ] );

        $this->assertTrue( $transformer->transform( new Row( [ "id" => 7 ] ) ) );
    }

    public function testThrowsWhenConditionsAreEmpty () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "at least one condition" );

        ( new FilterRowsTransformer() )->transform( new Row( [ "id" => 7 ] ) );
    }

    public function testThrowsForUnsupportedOperator () : void
    {
        $transformer = new FilterRowsTransformer();
        $transformer->options( [
            "conditions" => [
                [ "field" => "id", "operator" => "approximately", "value" => 7 ],
            ],
        ] );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "Unsupported Filter Rows operator" );

        $transformer->transform( new Row( [ "id" => 7 ] ) );
    }

    public function testThrowsForInvalidRegex () : void
    {
        $transformer = new FilterRowsTransformer();
        $transformer->options( [
            "conditions" => [
                [ "field" => "name", "operator" => "regex", "value" => "/[/" ],
            ],
        ] );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "Invalid Filter Rows regex" );

        $transformer->transform( new Row( [ "name" => "Avery" ] ) );
    }
}
