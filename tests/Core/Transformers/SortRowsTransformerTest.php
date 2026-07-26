<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Transformers\SortRowsTransformer;
use PHPUnit\Framework\TestCase;

class SortRowsTransformerTest extends TestCase
{
    public function testSortsNumericValuesAscending () : void
    {
        $transformer = $this->transformer( [
            [ "field" => "id", "direction" => "asc", "type" => "numeric" ],
        ] );

        $result = $transformer->transformRows( $this->rows( [
            [ "id" => "10" ],
            [ "id" => "2" ],
            [ "id" => "7" ],
        ] ) );

        $this->assertSame( [ "2", "7", "10" ], $this->values( $result, "id" ) );
    }

    public function testSortsDescending () : void
    {
        $transformer = $this->transformer( [
            [ "field" => "id", "direction" => "desc" ],
        ] );

        $result = $transformer->transformRows( $this->rows( [
            [ "id" => 2 ],
            [ "id" => 10 ],
            [ "id" => 7 ],
        ] ) );

        $this->assertSame( [ 10, 7, 2 ], $this->values( $result, "id" ) );
    }

    public function testSortsByMultipleFields () : void
    {
        $transformer = $this->transformer( [
            [ "field" => "group", "direction" => "asc", "type" => "text" ],
            [ "field" => "score", "direction" => "desc", "type" => "numeric" ],
        ] );

        $result = $transformer->transformRows( $this->rows( [
            [ "name" => "first", "group" => "b", "score" => 1 ],
            [ "name" => "second", "group" => "a", "score" => 2 ],
            [ "name" => "third", "group" => "a", "score" => 5 ],
        ] ) );

        $this->assertSame( [ "third", "second", "first" ], $this->values( $result, "name" ) );
    }

    public function testSortIsStable () : void
    {
        $transformer = $this->transformer( [ "group" ] );

        $result = $transformer->transformRows( $this->rows( [
            [ "name" => "first", "group" => "a" ],
            [ "name" => "second", "group" => "a" ],
            [ "name" => "third", "group" => "a" ],
        ] ) );

        $this->assertSame( [ "first", "second", "third" ], $this->values( $result, "name" ) );
    }

    public function testPlacesNullsLastByDefault () : void
    {
        $transformer = $this->transformer( [ [ "field" => "id" ] ] );

        $result = $transformer->transformRows( $this->rows( [
            [ "name" => "missing" ],
            [ "name" => "two", "id" => 2 ],
            [ "name" => "one", "id" => 1 ],
        ] ) );

        $this->assertSame( [ "one", "two", "missing" ], $this->values( $result, "name" ) );
    }

    public function testPlacesNullsFirstWhenConfigured () : void
    {
        $transformer = $this->transformer( [
            [ "field" => "id", "nulls" => "first" ],
        ] );

        $result = $transformer->transformRows( $this->rows( [
            [ "name" => "two", "id" => 2 ],
            [ "name" => "missing" ],
            [ "name" => "one", "id" => 1 ],
        ] ) );

        $this->assertSame( [ "missing", "one", "two" ], $this->values( $result, "name" ) );
    }

    public function testSortsDates () : void
    {
        $transformer = $this->transformer( [
            [ "field" => "created_at", "type" => "date" ],
        ] );

        $result = $transformer->transformRows( $this->rows( [
            [ "created_at" => "2026-07-26" ],
            [ "created_at" => "2024-01-01" ],
            [ "created_at" => "2025-06-15" ],
        ] ) );

        $this->assertSame(
            [ "2024-01-01", "2025-06-15", "2026-07-26" ],
            $this->values( $result, "created_at" )
        );
    }

    public function testReturnsEmptyInput () : void
    {
        $transformer = $this->transformer( [ "id" ] );

        $this->assertSame( [], $transformer->transformRows( [] ) );
    }

    public function testThrowsWithoutFields () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "at least one field" );

        ( new SortRowsTransformer() )->transformRows( [] );
    }

    public function testThrowsForInvalidDirection () : void
    {
        $transformer = $this->transformer( [
            [ "field" => "id", "direction" => "sideways" ],
        ] );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "direction" );

        $transformer->transformRows( $this->rows( [ [ "id" => 1 ] ] ) );
    }

    public function testThrowsForNonNumericValueInNumericMode () : void
    {
        $transformer = $this->transformer( [
            [ "field" => "id", "type" => "numeric" ],
        ] );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "non-numeric" );

        $transformer->transformRows( $this->rows( [ [ "id" => "two" ], [ "id" => 1 ] ] ) );
    }

    public function testThrowsForInvalidDate () : void
    {
        $transformer = $this->transformer( [
            [ "field" => "created_at", "type" => "date" ],
        ] );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "invalid date" );

        $transformer->transformRows( $this->rows( [
            [ "created_at" => "not-a-date" ],
            [ "created_at" => "2026-07-26" ],
        ] ) );
    }

    private function transformer ( array $fields ) : SortRowsTransformer
    {
        $transformer = new SortRowsTransformer();
        $transformer->options( [ "fields" => $fields ] );

        return $transformer;
    }

    private function rows ( array $values ) : array
    {
        return array_map( fn( array $value ) => new Row( $value ), $values );
    }

    private function values ( array $rows, string $field ) : array
    {
        return array_map( fn( Row $row ) => $row->get( $field ), $rows );
    }
}
