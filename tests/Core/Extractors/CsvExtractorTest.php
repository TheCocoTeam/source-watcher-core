<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use Coco\SourceWatcher\Core\Extractors\CsvExtractor;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;

use PHPUnit\Framework\TestCase;

class CsvExtractorTest extends TestCase
{
    public function testSetterGetterAttributeColumns () : void
    {
        $csvExtractor = new CsvExtractor();

        $givenColumns = array( "id", "name", "email" );
        $expectedColumns = array( "id", "name", "email" );

        $csvExtractor->setColumns( $givenColumns );

        $this->assertEquals( $expectedColumns, $csvExtractor->getColumns() );
    }

    public function testSetterGetterAttributeDelimiter () : void
    {
        $csvExtractor = new CsvExtractor();

        $givenDelimiter = ",";
        $expectedDelimiter = ",";

        $csvExtractor->setDelimiter( $givenDelimiter );

        $this->assertEquals( $expectedDelimiter, $csvExtractor->getDelimiter() );
    }

    public function testSetterGetterAttributeEnclosure () : void
    {
        $csvExtractor = new CsvExtractor();

        $givenEnclosure = "\"";
        $expectedEnclosure = "\"";

        $csvExtractor->setEnclosure( $givenEnclosure );

        $this->assertEquals( $expectedEnclosure, $csvExtractor->getEnclosure() );
    }

    public function testSetterGetterAttributeInput () : void
    {
        $csvExtractor = new CsvExtractor();

        $givenInput = "/some/file/path/file.csv";
        $expectedInput = "/some/file/path/file.csv";

        $csvExtractor->setInput( $givenInput );

        $this->assertEquals( $expectedInput, $csvExtractor->getInput() );
    }

    public function testLoadCsvWithDefaultOptions () : void
    {
        $csvExtractor = new CsvExtractor();

        $expected = [ new Row( [ "id" => 1, "name" => "John Doe", "email" => "johndoe@email.com" ] ), new Row( [ "id" => 2, "name" => "Jane Doe", "email" => "janedoe@email.com" ] ) ];

        $csvExtractor->setInput( __DIR__ . "/../../../samples/data/csv/csv1.csv" );

        $this->assertEquals( $expected, $csvExtractor->extract() );
    }

    public function testNoInputException () : void
    {
        $this->expectException( SourceWatcherException::class );

        $csvExtractor = new CsvExtractor();
        $csvExtractor->setInput( null );
        $csvExtractor->extract();
    }

    /**
     * This test is similar to the code from: samples/Core/Extractors/CsvExtractorSample2.php
     *
     * @throws SourceWatcherException
     */
    public function testColumnsWithNoIndex1 () : void
    {
        $csvExtractor = new CsvExtractor();
        $csvExtractor->setColumns( array( "id", "email" ) );
        $csvExtractor->setInput( __DIR__ . "/../../../samples/data/csv/csv1.csv" );

        $expected = [ new Row( [ "id" => 1, "email" => "johndoe@email.com" ] ), new Row( [ "id" => 2, "email" => "janedoe@email.com" ] ) ];

        $this->assertEquals( $expected, $csvExtractor->extract() );
    }

    /**
     * This test is similar to the code from: samples/Core/Extractors/CsvExtractorSample3.php
     *
     * @throws SourceWatcherException
     */
    public function testColumnsWithNoIndex2 () : void
    {
        $csvExtractor = new CsvExtractor();
        $csvExtractor->setColumns( array( "id", "name", "email" ) );
        $csvExtractor->setInput( __DIR__ . "/../../../samples/data/csv/csv1.csv" );

        $expected = [ new Row( [ "id" => 1, "name" => "John Doe", "email" => "johndoe@email.com" ] ), new Row( [ "id" => 2, "name" => "Jane Doe", "email" => "janedoe@email.com" ] ) ];

        $this->assertEquals( $expected, $csvExtractor->extract() );
    }

    /**
     * This test is similar to the code from: samples/Core/Extractors/CsvExtractorSample4.php
     *
     * @throws SourceWatcherException
     */
    public function testGetColumnsWithDifferentNames () : void
    {
        $csvExtractor = new CsvExtractor();
        $csvExtractor->setColumns( array( "id" => "id", "email" => "email_address" ) );
        $csvExtractor->setInput( __DIR__ . "/../../../samples/data/csv/csv1.csv" );

        $expected = [ new Row( [ "id" => 1, "email_address" => "johndoe@email.com" ] ), new Row( [ "id" => 2, "email_address" => "janedoe@email.com" ] ) ];

        $this->assertEquals( $expected, $csvExtractor->extract() );
    }
}
