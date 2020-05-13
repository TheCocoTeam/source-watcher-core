<?php

namespace Coco\SourceWatcher\Tests\Core\Transformers;

use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\Transformers\ConvertCaseTransformer;
use PHPUnit\Framework\TestCase;

class ConvertCaseTransformerTest extends TestCase
{
    public function testCaseModeUpperConstant () : void
    {
        $expectedValue = MB_CASE_UPPER;
        $actualValue = ConvertCaseTransformer::CONVERT_CASE_MODE_UPPER;

        $this->assertEquals( $expectedValue, $actualValue );
    }

    public function testCaseModeLowerConstant () : void
    {
        $expectedValue = MB_CASE_LOWER;
        $actualValue = ConvertCaseTransformer::CONVERT_CASE_MODE_LOWER;

        $this->assertEquals( $expectedValue, $actualValue );
    }

    public function testCaseModeTitleConstant () : void
    {
        $expectedValue = MB_CASE_TITLE;
        $actualValue = ConvertCaseTransformer::CONVERT_CASE_MODE_TITLE;

        $this->assertEquals( $expectedValue, $actualValue );
    }

    public function testTransformRowModeUpperAllColumns () : void
    {
        $transformer = new ConvertCaseTransformer();
        $transformer->options( [ "columns" => [ "id", "name" ], "encoding" => "UTF-8", "mode" => ConvertCaseTransformer::CONVERT_CASE_MODE_UPPER ] );

        $givenRow = new Row( [ "id" => 1, "name" => "John Doe" ] );

        $transformer->transform( $givenRow );

        $expectedRow = new Row( [ "ID" => 1, "NAME" => "John Doe" ] );

        $this->assertEquals( $expectedRow, $givenRow );
    }

    public function testTransformRowModeUpperSomeColumns () : void
    {
        $transformer = new ConvertCaseTransformer();
        $transformer->options( [ "columns" => [ "id" ], "encoding" => "UTF-8", "mode" => ConvertCaseTransformer::CONVERT_CASE_MODE_UPPER ] );

        $givenRow = new Row( [ "id" => 1, "name" => "John Doe" ] );

        $transformer->transform( $givenRow );

        $expectedRow = new Row( [ "ID" => 1, "name" => "John Doe" ] );

        $this->assertEquals( $expectedRow, $givenRow );
    }

    public function testTransformRowModeLowerAllColumns () : void
    {
        $transformer = new ConvertCaseTransformer();
        $transformer->options( [ "columns" => [ "ID", "NAME" ], "encoding" => "UTF-8", "mode" => ConvertCaseTransformer::CONVERT_CASE_MODE_LOWER ] );

        $givenRow = new Row( [ "ID" => 1, "NAME" => "John Doe" ] );

        $transformer->transform( $givenRow );

        $expectedRow = new Row( [ "id" => 1, "name" => "John Doe" ] );

        $this->assertEquals( $expectedRow, $givenRow );
    }

    public function testTransformRowModeLowerSomeColumns () : void
    {
        $transformer = new ConvertCaseTransformer();
        $transformer->options( [ "columns" => [ "ID" ], "encoding" => "UTF-8", "mode" => ConvertCaseTransformer::CONVERT_CASE_MODE_LOWER ] );

        $givenRow = new Row( [ "ID" => 1, "NAME" => "John Doe" ] );

        $transformer->transform( $givenRow );

        $expectedRow = new Row( [ "id" => 1, "NAME" => "John Doe" ] );

        $this->assertEquals( $expectedRow, $givenRow );
    }

    public function testTransformRowModeTitleAllColumns () : void
    {
        $transformer = new ConvertCaseTransformer();
        $transformer->options( [ "columns" => [ "id", "name" ], "encoding" => "UTF-8", "mode" => ConvertCaseTransformer::CONVERT_CASE_MODE_TITLE ] );

        $givenRow = new Row( [ "id" => 1, "name" => "John Doe" ] );

        $transformer->transform( $givenRow );

        $expectedRow = new Row( [ "Id" => 1, "Name" => "John Doe" ] );

        $this->assertEquals( $expectedRow, $givenRow );
    }

    public function testTransformRowModeTitleSomeColumns () : void
    {
        $transformer = new ConvertCaseTransformer();
        $transformer->options( [ "columns" => [ "id" ], "encoding" => "UTF-8", "mode" => ConvertCaseTransformer::CONVERT_CASE_MODE_TITLE ] );

        $givenRow = new Row( [ "id" => 1, "name" => "John Doe" ] );

        $transformer->transform( $givenRow );

        $expectedRow = new Row( [ "Id" => 1, "name" => "John Doe" ] );

        $this->assertEquals( $expectedRow, $givenRow );
    }
}
