<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Transformers\DeduplicateRowsTransformer;
use PHPUnit\Framework\TestCase;

class DeduplicateRowsTransformerTest extends TestCase
{
    public function testKeepsFirstDuplicateByDefault () : void
    {
        $result = $this->transformer()->transformRows( $this->rows( [
            [ "id" => 1, "name" => "first" ],
            [ "id" => 1, "name" => "second" ],
            [ "id" => 2, "name" => "other" ],
        ] ) );

        $this->assertSame( [ "first", "other" ], $this->values( $result, "name" ) );
    }

    public function testKeepsLastDuplicate () : void
    {
        $result = $this->transformer( [ "keep" => "last" ] )->transformRows( $this->rows( [
            [ "id" => 1, "name" => "first" ],
            [ "id" => 2, "name" => "other" ],
            [ "id" => 1, "name" => "second" ],
        ] ) );

        $this->assertSame( [ "other", "second" ], $this->values( $result, "name" ) );
    }

    public function testUsesMultipleKeyFields () : void
    {
        $result = $this->transformer( [ "keyFields" => [ "id", "source" ] ] )
            ->transformRows( $this->rows( [
                [ "id" => 1, "source" => "a", "name" => "first" ],
                [ "id" => 1, "source" => "b", "name" => "second" ],
                [ "id" => 1, "source" => "a", "name" => "duplicate" ],
            ] ) );

        $this->assertSame( [ "first", "second" ], $this->values( $result, "name" ) );
    }

    public function testKeepsLowestOrderedValue () : void
    {
        $result = $this->transformer( [
            "orderField" => "version",
            "keep" => "first",
        ] )->transformRows( $this->rows( [
            [ "id" => 1, "version" => 3 ],
            [ "id" => 1, "version" => 1 ],
            [ "id" => 1, "version" => 2 ],
        ] ) );

        $this->assertSame( 1, $result[0]->get( "version" ) );
    }

    public function testKeepsHighestOrderedValue () : void
    {
        $result = $this->transformer( [
            "orderField" => "version",
            "keep" => "last",
        ] )->transformRows( $this->rows( [
            [ "id" => 1, "version" => 3 ],
            [ "id" => 1, "version" => 1 ],
            [ "id" => 1, "version" => 2 ],
        ] ) );

        $this->assertSame( 3, $result[0]->get( "version" ) );
    }

    public function testDescendingOrderReversesOrderedSelection () : void
    {
        $result = $this->transformer( [
            "orderField" => "version",
            "orderDirection" => "desc",
            "keep" => "first",
        ] )->transformRows( $this->rows( [
            [ "id" => 1, "version" => 1 ],
            [ "id" => 1, "version" => 3 ],
        ] ) );

        $this->assertSame( 3, $result[0]->get( "version" ) );
    }

    public function testDistinguishesMissingKeyFromNullKey () : void
    {
        $result = $this->transformer()->transformRows( $this->rows( [
            [ "name" => "missing" ],
            [ "id" => null, "name" => "null" ],
        ] ) );

        $this->assertCount( 2, $result );
    }

    public function testReturnsEmptyInput () : void
    {
        $this->assertSame( [], $this->transformer()->transformRows( [] ) );
    }

    public function testThrowsWithoutKeyFields () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "at least one key field" );

        $transformer = new DeduplicateRowsTransformer();
        $transformer->transformRows( [] );
    }

    public function testThrowsForInvalidKeepValue () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "keep" );

        $this->transformer( [ "keep" => "random" ] )->transformRows( [] );
    }

    private function transformer ( array $options = [] ) : DeduplicateRowsTransformer
    {
        $transformer = new DeduplicateRowsTransformer();
        $transformer->options( array_merge( [ "keyFields" => [ "id" ] ], $options ) );

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
