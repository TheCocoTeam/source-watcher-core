<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Transformers\DeriveFieldTransformer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DeriveFieldTransformerTest extends TestCase
{
    public static function expressionProvider () : array
    {
        return [
            "field reference" => [ "name", "Avery" ],
            "string literal" => [ "'fixed'", "fixed" ],
            "integer literal" => [ "42", 42 ],
            "decimal literal" => [ "4.5", 4.5 ],
            "null literal" => [ "null", null ],
            "boolean literal" => [ "true", true ],
            "concat" => [ "concat(first_name, ' ', last_name)", "Avery Ruiz" ],
            "coalesce value" => [ "coalesce(nickname, name, 'Unknown')", "Avery" ],
            "coalesce fallback" => [ "coalesce(missing, 'Unknown')", "Unknown" ],
            "upper" => [ "upper(name)", "AVERY" ],
            "lower" => [ "lower(last_name)", "ruiz" ],
            "trim" => [ "trim(padded)", "value" ],
            "length" => [ "length(name)", 5 ],
            "date format" => [ "date_format(created_at, 'Y/m/d')", "2026/07/26" ],
            "addition precedence" => [ "price + quantity * 2", 33 ],
            "parentheses" => [ "(price + quantity) * 2", 58 ],
            "division" => [ "price / quantity", 6.25 ],
            "unary minus" => [ "-quantity", -4 ],
            "nested functions" => [ "upper(concat(first_name, ' ', last_name))", "AVERY RUIZ" ],
            "escaped string" => [ "'line\\nvalue'", "line\nvalue" ],
        ];
    }

    #[DataProvider( "expressionProvider" )]
    public function testDerivesSupportedExpressions ( string $expression, mixed $expected ) : void
    {
        $row = $this->row();

        $this->transformer( "derived", $expression )->transform( $row );

        $this->assertSame( $expected, $row->get( "derived" ) );
    }

    public function testCanReplaceExistingField () : void
    {
        $row = $this->row();

        $this->transformer( "name", "upper(name)" )->transform( $row );

        $this->assertSame( "AVERY", $row->get( "name" ) );
    }

    public function testThrowsWithoutTargetField () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "target field" );

        $this->transformer( "", "name" )->transform( $this->row() );
    }

    public function testThrowsForEmptyExpression () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "cannot be empty" );

        $this->transformer( "derived", "" )->transform( $this->row() );
    }

    public function testThrowsForUnsupportedFunction () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "Unsupported Derive Field function" );

        $this->transformer( "derived", "system('whoami')" )->transform( $this->row() );
    }

    public function testThrowsForInvalidCharacter () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "Invalid character" );

        $this->transformer( "derived", "name; phpinfo()" )->transform( $this->row() );
    }

    public function testThrowsForNonNumericArithmetic () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "arithmetic requires numeric" );

        $this->transformer( "derived", "name + 1" )->transform( $this->row() );
    }

    public function testThrowsForDivisionByZero () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "divide by zero" );

        $this->transformer( "derived", "price / 0" )->transform( $this->row() );
    }

    public function testThrowsForWrongFunctionArgumentCount () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "expects 1 argument" );

        $this->transformer( "derived", "upper(name, last_name)" )->transform( $this->row() );
    }

    public function testThrowsForInvalidDate () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "invalid date" );

        $this->transformer( "derived", "date_format(name, 'Y')" )->transform( $this->row() );
    }

    private function transformer ( string $targetField, string $expression ) : DeriveFieldTransformer
    {
        $transformer = new DeriveFieldTransformer();
        $transformer->options( [
            "targetField" => $targetField,
            "expression" => $expression,
        ] );

        return $transformer;
    }

    private function row () : Row
    {
        return new Row( [
            "name" => "Avery",
            "first_name" => "Avery",
            "last_name" => "Ruiz",
            "nickname" => null,
            "padded" => "  value  ",
            "price" => 25,
            "quantity" => 4,
            "created_at" => "2026-07-26 14:30:00",
        ] );
    }
}
