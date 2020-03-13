<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use PHPUnit\Framework\TestCase;
use Coco\SourceWatcher\Core\Extractors\CsvExtractor;
use Coco\SourceWatcher\Core\Row;

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
}
