<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use Coco\SourceWatcher\Core\Extractors\JsonExtractor;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonExtractorTest
 * @package Coco\SourceWatcher\Tests\Core\Extractors
 */
class JsonExtractorTest extends TestCase
{
    /**
     *
     */
    public function testSetterGetterAttributeInput () : void
    {
        $jsonExtractor = new JsonExtractor();

        $givenInput = "/some/file/path/file.json";
        $expectedInput = "/some/file/path/file.json";

        $jsonExtractor->setInput( $givenInput );

        $this->assertEquals( $expectedInput, $jsonExtractor->getInput() );
    }

    /**
     *
     */
    public function testSetGetColumns () : void
    {
        $jsonExtractor = new JsonExtractor();

        $givenColumns = array( "color" => "colors.*.color" );
        $expectedColumns = array( "color" => "colors.*.color" );

        $jsonExtractor->setColumns( $givenColumns );

        $this->assertEquals( $expectedColumns, $jsonExtractor->getColumns() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testExtractColors () : void
    {
        $jsonExtractor = new JsonExtractor();
        $jsonExtractor->setColumns( array( "color" => "colors.*.color" ) );
        $jsonExtractor->setInput( __DIR__ . "/../../../samples/data/json/colors.json" );

        $expected = [ new Row( [ "color" => "black" ] ), new Row( [ "color" => "white" ] ), new Row( [ "color" => "red" ] ), new Row( [ "color" => "blue" ] ), new Row( [ "color" => "yellow" ] ), new Row( [ "color" => "green" ] ) ];

        $this->assertEquals( $expected, $jsonExtractor->extract() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testNoInputException () : void
    {
        $this->expectException( SourceWatcherException::class );

        $jsonExtractor = new JsonExtractor();
        $jsonExtractor->setInput( null );
        $jsonExtractor->extract();
    }

    /**
     * @throws SourceWatcherException
     */
    public function testNonexistentPath () : void
    {
        $this->expectException( SourceWatcherException::class );

        $jsonExtractor = new JsonExtractor();
        $jsonExtractor->setInput( "/file/path/this/doest/not/exist/file.json" );
        $jsonExtractor->extract();
    }

    /**
     * @throws SourceWatcherException
     */
    public function testWrongColumnSelectorException () : void
    {
        $this->expectException( SourceWatcherException::class );

        $jsonExtractor = new JsonExtractor();
        $jsonExtractor->setColumns( array( "color" => "$.bad-!-selector" ) );
        $jsonExtractor->setInput( __DIR__ . "/../../../samples/data/json/colors.json" );
        $jsonExtractor->extract();
    }
}
