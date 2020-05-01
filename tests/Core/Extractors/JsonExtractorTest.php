<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use Coco\SourceWatcher\Core\Extractors\JsonExtractor;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonExtractorTest
 * @package Coco\SourceWatcher\Tests\Core\Extractors
 */
class JsonExtractorTest extends TestCase
{
    public function testSetGetColumns () : void
    {
        $jsonExtractor = new JsonExtractor();

        $givenColumns = array( "color" => "colors.*.color" );
        $expectedColumns = array( "color" => "colors.*.color" );

        $jsonExtractor->setColumns( $givenColumns );

        $this->assertEquals( $expectedColumns, $jsonExtractor->getColumns() );
    }

    public function testSetGetInput () : void
    {
        $jsonExtractor = new JsonExtractor();

        $givenInput = new FileInput( "/some/file/path/file.json" );
        $expectedInput = new FileInput( "/some/file/path/file.json" );

        $jsonExtractor->setInput( $givenInput );

        $this->assertEquals( $expectedInput, $jsonExtractor->getInput() );
    }

    public function testExceptionNoInput () : void
    {
        $this->expectException( SourceWatcherException::class );

        $jsonExtractor = new JsonExtractor();

        $jsonExtractor->extract();
    }

    public function testExtractColors () : void
    {
        try {
            $jsonExtractor = new JsonExtractor();
            $jsonExtractor->setColumns( array( "color" => "colors.*.color" ) );
            $jsonExtractor->setInput( new FileInput( __DIR__ . "/../../../samples/data/json/colors.json" ) );

            $expected = [ new Row( [ "color" => "black" ] ), new Row( [ "color" => "white" ] ), new Row( [ "color" => "red" ] ), new Row( [ "color" => "blue" ] ), new Row( [ "color" => "yellow" ] ), new Row( [ "color" => "green" ] ) ];

            $this->assertEquals( $expected, $jsonExtractor->extract() );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }
    }

    public function testNonexistentPath () : void
    {
        $this->expectException( SourceWatcherException::class );

        $jsonExtractor = new JsonExtractor();
        $jsonExtractor->setInput( new FileInput( "/file/path/this/doest/not/exist/file.json" ) );
        $jsonExtractor->extract();
    }

    public function testWrongColumnSelectorException () : void
    {
        $this->expectException( SourceWatcherException::class );

        $jsonExtractor = new JsonExtractor();
        $jsonExtractor->setColumns( array( "color" => "$.bad-!-selector" ) );
        $jsonExtractor->setInput( new FileInput( __DIR__ . "/../../../samples/data/json/colors.json" ) );
        $jsonExtractor->extract();
    }
}
