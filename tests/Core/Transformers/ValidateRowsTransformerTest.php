<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Transformers\ValidateRowsTransformer;
use PHPUnit\Framework\TestCase;

class ValidateRowsTransformerTest extends TestCase
{
    public function testValidRowPassesWithoutMutationInFailMode () : void
    {
        $row = $this->validRow();

        $this->transformer( $this->rules() )->transform( $row );

        $this->assertFalse( $row->offsetExists( "_validation_errors" ) );
    }

    public function testAnnotateModeAddsEmptyErrorsToValidRow () : void
    {
        $row = $this->validRow();

        $this->transformer( $this->rules(), [ "mode" => "annotate" ] )->transform( $row );

        $this->assertSame( [], $row->get( "_validation_errors" ) );
    }

    public function testAnnotateModeCollectsValidationErrors () : void
    {
        $row = new Row( [
            "id" => 0,
            "email" => "invalid",
            "status" => "unknown",
            "name" => "A",
        ] );

        $this->transformer( $this->rules(), [
            "mode" => "annotate",
            "errorField" => "errors",
        ] )->transform( $row );

        $errors = $row->get( "errors" );
        $this->assertCount( 4, $errors );
        $this->assertStringContainsString( "id must be at least", implode( " ", $errors ) );
        $this->assertStringContainsString( "valid email", implode( " ", $errors ) );
        $this->assertStringContainsString( "not an allowed value", implode( " ", $errors ) );
        $this->assertStringContainsString( "at least 2", implode( " ", $errors ) );
    }

    public function testFailModeThrowsWithAllErrors () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "Row validation failed" );

        $this->transformer( $this->rules() )->transform( new Row( [
            "id" => 0,
            "email" => "invalid",
            "status" => "active",
            "name" => "Valid",
        ] ) );
    }

    public function testRequiredDetectsMissingNullAndEmptyValues () : void
    {
        foreach ( [ new Row( [] ), new Row( [ "name" => null ] ), new Row( [ "name" => "" ] ) ] as $row ) {
            $transformer = $this->transformer( [ "name" => [ "required" => true ] ], [ "mode" => "annotate" ] );
            $transformer->transform( $row );
            $this->assertSame( [ "name is required." ], $row->get( "_validation_errors" ) );
        }
    }

    public function testOptionalEmptyValueSkipsOtherRules () : void
    {
        $row = new Row( [ "email" => null ] );

        $this->transformer( [
            "email" => [ "format" => "email" ],
        ], [ "mode" => "annotate" ] )->transform( $row );

        $this->assertSame( [], $row->get( "_validation_errors" ) );
    }

    public function testValidatesUrlUuidRegexAndMaximums () : void
    {
        $row = new Row( [
            "url" => "not-url",
            "uuid" => "not-uuid",
            "code" => "abc",
            "score" => 11,
            "tags" => [ 1, 2, 3 ],
        ] );

        $this->transformer( [
            "url" => [ "format" => "url" ],
            "uuid" => [ "format" => "uuid" ],
            "code" => [ "regex" => "/^[A-Z]+$/" ],
            "score" => [ "max" => 10 ],
            "tags" => [ "maxLength" => 2 ],
        ], [ "mode" => "annotate" ] )->transform( $row );

        $this->assertCount( 5, $row->get( "_validation_errors" ) );
    }

    public function testValidatesSupportedTypes () : void
    {
        $row = new Row( [
            "integer" => 1,
            "float" => 1.5,
            "numeric" => 2,
            "string" => "value",
            "boolean" => true,
            "array" => [],
            "date" => "2026-07-26",
            "datetime" => "2026-07-26 10:30:00",
        ] );
        $rules = [];

        foreach ( array_keys( $row->getAttributes() ) as $field ) {
            $rules[$field] = [ "type" => $field ];
        }

        $this->transformer( $rules, [ "mode" => "annotate" ] )->transform( $row );

        $this->assertSame( [], $row->get( "_validation_errors" ) );
    }

    public function testValidatesDateBounds () : void
    {
        $row = new Row( [ "published_on" => "2026-01-01" ] );

        $this->transformer( [
            "published_on" => [
                "type" => "date",
                "min" => "2026-02-01",
                "max" => "2026-12-31",
            ],
        ], [ "mode" => "annotate" ] )->transform( $row );

        $this->assertSame(
            [ "published_on must be at least 2026-02-01." ],
            $row->get( "_validation_errors" )
        );
    }

    public function testThrowsForInvalidRegexConfiguration () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "regex" );

        $this->transformer( [ "code" => [ "regex" => "/[/" ] ] )
            ->transform( new Row( [ "code" => "ABC" ] ) );
    }

    public function testThrowsWithoutRules () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "at least one field rule" );

        $this->transformer( [] )->transform( $this->validRow() );
    }

    private function transformer ( array $rules, array $options = [] ) : ValidateRowsTransformer
    {
        $transformer = new ValidateRowsTransformer();
        $transformer->options( array_merge( [ "rules" => $rules ], $options ) );

        return $transformer;
    }

    private function rules () : array
    {
        return [
            "id" => [ "required" => true, "type" => "integer", "min" => 1 ],
            "email" => [ "required" => true, "format" => "email" ],
            "status" => [ "allowed" => [ "active", "inactive" ] ],
            "name" => [ "required" => true, "type" => "string", "minLength" => 2 ],
        ];
    }

    private function validRow () : Row
    {
        return new Row( [
            "id" => 7,
            "email" => "avery@example.com",
            "status" => "active",
            "name" => "Avery",
        ] );
    }
}
