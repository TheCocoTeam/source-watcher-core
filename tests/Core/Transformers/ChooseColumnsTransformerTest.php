<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Transformers\ChooseColumnsTransformer;
use PHPUnit\Framework\TestCase;

class ChooseColumnsTransformerTest extends TestCase
{
    public function testIncludeKeepsOnlyConfiguredColumnsInConfiguredOrder () : void
    {
        $row = new Row( [
            "id" => 7,
            "name" => "Avery",
            "email" => "avery@example.com",
            "raw_html" => "<html>",
        ] );

        $this->transformer( "include", [ "email", "id" ] )->transform( $row );

        $this->assertSame(
            [ "email" => "avery@example.com", "id" => 7 ],
            $row->getAttributes()
        );
    }

    public function testIncludeIgnoresMissingColumns () : void
    {
        $row = new Row( [ "id" => 7, "name" => "Avery" ] );

        $this->transformer( "include", [ "id", "missing", "name" ] )->transform( $row );

        $this->assertSame( [ "id" => 7, "name" => "Avery" ], $row->getAttributes() );
    }

    public function testExcludeRemovesConfiguredColumns () : void
    {
        $row = new Row( [
            "id" => 7,
            "name" => "Avery",
            "raw_html" => "<html>",
            "internal_notes" => "private",
        ] );

        $this->transformer( "exclude", [ "raw_html", "internal_notes" ] )->transform( $row );

        $this->assertSame( [ "id" => 7, "name" => "Avery" ], $row->getAttributes() );
    }

    public function testExcludeIgnoresMissingColumns () : void
    {
        $row = new Row( [ "id" => 7, "name" => "Avery" ] );

        $this->transformer( "exclude", [ "missing" ] )->transform( $row );

        $this->assertSame( [ "id" => 7, "name" => "Avery" ], $row->getAttributes() );
    }

    public function testDuplicateColumnNamesAreAcceptedOnce () : void
    {
        $row = new Row( [ "id" => 7, "name" => "Avery" ] );

        $this->transformer( "include", [ "id", "id" ] )->transform( $row );

        $this->assertSame( [ "id" => 7 ], $row->getAttributes() );
    }

    public function testThrowsForInvalidMode () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "mode" );

        $this->transformer( "sometimes", [ "id" ] )->transform( new Row( [ "id" => 7 ] ) );
    }

    public function testThrowsWithoutColumns () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "at least one column" );

        $this->transformer( "include", [] )->transform( new Row( [ "id" => 7 ] ) );
    }

    public function testThrowsForInvalidColumn () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "non-empty field names" );

        $this->transformer( "include", [ "id", "" ] )->transform( new Row( [ "id" => 7 ] ) );
    }

    private function transformer ( string $mode, array $columns ) : ChooseColumnsTransformer
    {
        $transformer = new ChooseColumnsTransformer();
        $transformer->options( [ "mode" => $mode, "columns" => $columns ] );

        return $transformer;
    }
}
