<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Transformers\TypeConversionTransformer;
use PHPUnit\Framework\TestCase;

class TypeConversionTransformerTest extends TestCase
{
    public function testConvertsSupportedTypes () : void
    {
        $row = new Row( [
            "id" => "42",
            "price" => "19.95",
            "label" => 123,
            "active" => "yes",
            "published_on" => "July 26, 2026",
            "created_at" => "2026-07-26T14:30:45-04:00",
        ] );

        $this->transformer( [
            "id" => "integer",
            "price" => "float",
            "label" => "string",
            "active" => "boolean",
            "published_on" => "date",
            "created_at" => "datetime",
        ] )->transform( $row );

        $this->assertSame( 42, $row->get( "id" ) );
        $this->assertSame( 19.95, $row->get( "price" ) );
        $this->assertSame( "123", $row->get( "label" ) );
        $this->assertTrue( $row->get( "active" ) );
        $this->assertSame( "2026-07-26", $row->get( "published_on" ) );
        $this->assertSame( "2026-07-26 14:30:45", $row->get( "created_at" ) );
    }

    public function testAcceptsObjectFieldConfiguration () : void
    {
        $row = new Row( [ "id" => "42" ] );

        $this->transformer( [ "id" => [ "type" => "integer" ] ] )->transform( $row );

        $this->assertSame( 42, $row->get( "id" ) );
    }

    public function testRecognizesFalseBooleanValues () : void
    {
        foreach ( [ false, 0, "0", "false", "no", "off" ] as $value ) {
            $row = new Row( [ "active" => $value ] );
            $this->transformer( [ "active" => "boolean" ] )->transform( $row );
            $this->assertFalse( $row->get( "active" ) );
        }
    }

    public function testEmptyStringBecomesNullByDefault () : void
    {
        $row = new Row( [ "age" => "" ] );

        $this->transformer( [ "age" => "integer" ] )->transform( $row );

        $this->assertNull( $row->get( "age" ) );
    }

    public function testCanRejectNullValues () : void
    {
        $row = new Row( [ "age" => null ] );
        $transformer = $this->transformer( [ "age" => "integer" ], [ "nullHandling" => "error" ] );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( 'field "age" cannot be null' );

        $transformer->transform( $row );
    }

    public function testForgivingModePreservesInvalidOriginalValue () : void
    {
        $row = new Row( [ "age" => "unknown" ] );

        $this->transformer( [ "age" => "integer" ], [ "mode" => "forgiving" ] )->transform( $row );

        $this->assertSame( "unknown", $row->get( "age" ) );
    }

    public function testMissingFieldsAreIgnored () : void
    {
        $row = new Row( [ "id" => "42" ] );

        $this->transformer( [ "missing" => "integer" ] )->transform( $row );

        $this->assertSame( [ "id" => "42" ], $row->getAttributes() );
    }

    public function testStrictModeThrowsForInvalidConversion () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( 'could not convert field "age"' );

        $this->transformer( [ "age" => "integer" ] )->transform( new Row( [ "age" => "unknown" ] ) );
    }

    public function testThrowsForUnsupportedType () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "Unsupported Type Conversion type" );

        $this->transformer( [ "id" => "object" ] )->transform( new Row( [ "id" => "42" ] ) );
    }

    public function testThrowsWithoutFields () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "at least one field" );

        $this->transformer( [] )->transform( new Row( [ "id" => "42" ] ) );
    }

    private function transformer ( array $fields, array $options = [] ) : TypeConversionTransformer
    {
        $transformer = new TypeConversionTransformer();
        $transformer->options( array_merge( [ "fields" => $fields ], $options ) );

        return $transformer;
    }
}
