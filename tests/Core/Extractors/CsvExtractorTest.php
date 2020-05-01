<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use Coco\SourceWatcher\Core\Extractors\CsvExtractor;
use Coco\SourceWatcher\Core\Inputs\FileInput;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use PHPUnit\Framework\TestCase;

/**
 * Class CsvExtractorTest
 * @package Coco\SourceWatcher\Tests\Core\Extractors
 */
class CsvExtractorTest extends TestCase
{
    public function testSetGetColumns () : void
    {
        $csvExtractor = new CsvExtractor();

        $givenColumns = array( "id", "name", "email" );
        $expectedColumns = array( "id", "name", "email" );

        $csvExtractor->setColumns( $givenColumns );

        $this->assertEquals( $expectedColumns, $csvExtractor->getColumns() );
    }

    public function testSetGetDelimiter () : void
    {
        $csvExtractor = new CsvExtractor();

        $givenDelimiter = ",";
        $expectedDelimiter = ",";

        $csvExtractor->setDelimiter( $givenDelimiter );

        $this->assertEquals( $expectedDelimiter, $csvExtractor->getDelimiter() );
    }

    public function testSetGetEnclosure () : void
    {
        $csvExtractor = new CsvExtractor();

        $givenEnclosure = "\"";
        $expectedEnclosure = "\"";

        $csvExtractor->setEnclosure( $givenEnclosure );

        $this->assertEquals( $expectedEnclosure, $csvExtractor->getEnclosure() );
    }

    public function testSetGetInput () : void
    {
        $csvExtractor = new CsvExtractor();

        $givenInput = new FileInput( "/some/file/path/file.csv" );
        $expectedInput = new FileInput( "/some/file/path/file.csv" );

        $csvExtractor->setInput( $givenInput );

        $this->assertEquals( $expectedInput, $csvExtractor->getInput() );
    }

    public function testExceptionNoInput () : void
    {
        $this->expectException( SourceWatcherException::class );

        $csvExtractor = new CsvExtractor();

        $csvExtractor->extract();
    }

    public function testLoadCsvWithDefaultOptions () : void
    {
        try {
            $csvExtractor = new CsvExtractor();

            $expected = [ new Row( [ "id" => 1, "name" => "John Doe", "email" => "johndoe@email.com" ] ), new Row( [ "id" => 2, "name" => "Jane Doe", "email" => "janedoe@email.com" ] ) ];

            $csvExtractor->setInput( new FileInput( __DIR__ . "/../../../samples/data/csv/csv1.csv" ) );

            $this->assertEquals( $expected, $csvExtractor->extract() );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }
    }

    public function testColumnsWithNoIndex1 () : void
    {
        try {
            $csvExtractor = new CsvExtractor();
            $csvExtractor->setColumns( array( "id", "email" ) );
            $csvExtractor->setInput( new FileInput( __DIR__ . "/../../../samples/data/csv/csv1.csv" ) );

            $expected = [ new Row( [ "id" => 1, "email" => "johndoe@email.com" ] ), new Row( [ "id" => 2, "email" => "janedoe@email.com" ] ) ];

            $this->assertEquals( $expected, $csvExtractor->extract() );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }
    }

    public function testColumnsWithNoIndex2 () : void
    {
        try {
            $csvExtractor = new CsvExtractor();
            $csvExtractor->setColumns( array( "id", "name", "email" ) );
            $csvExtractor->setInput( new FileInput( __DIR__ . "/../../../samples/data/csv/csv1.csv" ) );

            $expected = [ new Row( [ "id" => 1, "name" => "John Doe", "email" => "johndoe@email.com" ] ), new Row( [ "id" => 2, "name" => "Jane Doe", "email" => "janedoe@email.com" ] ) ];

            $this->assertEquals( $expected, $csvExtractor->extract() );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }
    }

    public function testGetColumnsWithDifferentNames () : void
    {
        try {
            $csvExtractor = new CsvExtractor();
            $csvExtractor->setColumns( array( "id" => "id", "email" => "email_address" ) );
            $csvExtractor->setInput( new FileInput( __DIR__ . "/../../../samples/data/csv/csv1.csv" ) );

            $expected = [ new Row( [ "id" => 1, "email_address" => "johndoe@email.com" ] ), new Row( [ "id" => 2, "email_address" => "janedoe@email.com" ] ) ];

            $this->assertEquals( $expected, $csvExtractor->extract() );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }
    }
}
