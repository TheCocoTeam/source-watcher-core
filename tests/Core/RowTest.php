<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core;

use PHPUnit\Framework\TestCase;
use Coco\SourceWatcher\Core\Row;

class RowTest extends TestCase
{
    public function testGetAttribute () : void
    {
        $row = new Row( [ "name" => "Jane Doe" ] );
        $expectedAttributeValue = "Jane Doe";
        $this->assertEquals( $expectedAttributeValue, $row->get( "name" ) );
    }

    public function testSetAttribute () : void
    {
        $row = new Row( [ "name" => "Jane Doe" ] );
        $row->set( "name", "John Doe" );

        $expectedAttributeValue = "John Doe";

        $this->assertEquals( $expectedAttributeValue, $row->get( "name" ) );
    }

    public function testRemoveAttribute () : void
    {
        $row = new Row( [ "name" => "Jane Doe" ] );
        $row->remove( "name" );

        $expectedAttributeValue = null;

        $this->assertEquals( $expectedAttributeValue, $row->get( "name" ) );
    }

    public function testAccessAttributeNotationArray () : void
    {
        $row = new Row( [ "name" => "Jane Doe" ] );
        $expectedAttributeValue = "Jane Doe";
        $this->assertEquals( $expectedAttributeValue, $row["name"] );
    }

    public function testAccessAttributeNotationObject () : void
    {
        $row = new Row( [ "name" => "Jane Doe" ] );
        $expectedAttributeValue = "Jane Doe";
        $this->assertEquals( $expectedAttributeValue, $row->name );
    }
}
