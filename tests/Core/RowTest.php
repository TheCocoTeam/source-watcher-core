<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core;

use Coco\SourceWatcher\Core\Row;
use PHPUnit\Framework\TestCase;

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

    public function testOffsetExistsViaEmpty () : void
    {
        $row = new Row( [ "name" => "Jane Doe" ] );

        // test the offsetExists method
        $this->assertTrue( empty( $row["id"] ) );
    }

    public function testOffsetExistsViaIsSet () : void
    {
        $row = new Row( [ "name" => "Jane Doe" ] );

        // test the offsetExists method
        $this->assertNotTrue( isset( $row["id"] ) );
    }

    public function testOffsetGetAndSetWithArrayNotation () : void
    {
        $givenName = "Jane Doe";

        $row = new Row( [ "id" => 1 ] );
        $row["name"] = $givenName;  // test the offsetSet method

        $expectedName = "Jane Doe";
        $actualName = $row["name"]; // test the offsetGet method

        $this->assertEquals( $expectedName, $actualName );
    }

    public function testOffsetUnset () : void
    {
        $row = new Row( [ "id" => 1, "name" => "Jane Doe" ] );

        // test the offsetUnset method
        unset( $row["id"] );

        $expectedRow = new Row( [ "name" => "Jane Doe" ] );

        $this->assertEquals( $expectedRow, $row );
    }
}
