<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core;

use Coco\SourceWatcher\Core\Row;
use PHPUnit\Framework\TestCase;

class RowTest extends TestCase
{
    public function testGetAndSetAttributes () : void
    {
        $givenAttributes = [ "id", "name" ];

        $row = new Row( [] );
        $row->setAttributes( $givenAttributes );

        $expectedAttributes = [ "id", "name" ];
        $actualAttributes = $row->getAttributes();

        $this->assertEquals( $expectedAttributes, $actualAttributes );
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

    public function testGetAndSetAttribute () : void
    {
        $givenName = "Jane Doe";

        $row = new Row( [ "id" => 1 ] );
        $row->set( "name", $givenName );    // test the set method

        $expectedName = "Jane Doe";
        $actualName = $row->get( "name" );  // test the get method

        $this->assertEquals( $expectedName, $actualName );
    }

    public function testRemoveAttribute () : void
    {
        $row = new Row( [ "id" => 1, "name" => "Jane Doe" ] );

        // test the remove method
        $row->remove( "id" );

        $expectedRow = new Row( [ "name" => "Jane Doe" ] );

        $this->assertEquals( $expectedRow, $row );
    }

    public function testGetAndSetWithObjectNotation () : void
    {
        $givenName = "Jane Doe";

        $row = new Row( [ "id" => 1 ] );
        $row->name = $givenName;    // test the __set method

        $expectedName = "Jane Doe";
        $actualName = $row->name;   // test the __get method

        $this->assertEquals( $expectedName, $actualName );
    }
}
