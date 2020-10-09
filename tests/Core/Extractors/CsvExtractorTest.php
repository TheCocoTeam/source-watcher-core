<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use Coco\SourceWatcher\Core\Extractors\CsvExtractor;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\IO\Inputs\Input;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use PHPUnit\Framework\TestCase;

/**
 * Class CsvExtractorTest
 *
 * @package Coco\SourceWatcher\Tests\Core\Extractors
 */
class CsvExtractorTest extends TestCase
{
    private string $csvLocation;

    private string $idIndex;
    private string $nameIndex;
    private string $emailIndex;
    private string $emailAddressIndex;

    private string $johnDoeName;
    private string $johnDoeEmailAddress;

    private string $janeDoeName;
    private string $janeDoeEmailAddress;

    public function setUp () : void
    {
        $this->csvLocation = __DIR__ . "/../../../samples/data/csv/csv1.csv";

        $this->idIndex = "id";
        $this->nameIndex = "name";
        $this->emailIndex = "email";
        $this->emailAddressIndex = "email_address";

        $this->johnDoeName = "John Doe";
        $this->johnDoeEmailAddress = "johndoe@email.com";

        $this->janeDoeName = "Jane Doe";
        $this->janeDoeEmailAddress = "janedoe@email.com";
    }

    public function testSetGetColumns () : void
    {
        $csvExtractor = new CsvExtractor();

        $givenColumns = [ $this->idIndex, $this->nameIndex, $this->emailIndex ];
        $expectedColumns = [ $this->idIndex, $this->nameIndex, $this->emailIndex ];

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

    /**
     * @throws SourceWatcherException
     */
    public function testExceptionNoInput () : void
    {
        $this->expectException( SourceWatcherException::class );

        $csvExtractor = new CsvExtractor();
        $csvExtractor->extract();
    }

    /**
     * @throws SourceWatcherException
     */
    public function testExceptionNoFileInput () : void
    {
        $this->expectException( SourceWatcherException::class );

        $csvExtractor = new CsvExtractor();
        $csvExtractor->setInput( $this->createMock( Input::class ) );
        $csvExtractor->extract();
    }

    /**
     * @throws SourceWatcherException
     */
    public function testLoadCsvWithDefaultOptions () : void
    {
        $csvExtractor = new CsvExtractor();

        $expected = [
            new Row( [
                $this->idIndex => 1,
                $this->nameIndex => $this->johnDoeName,
                $this->emailIndex => $this->johnDoeEmailAddress
            ] ),
            new Row( [
                $this->idIndex => 2,
                $this->nameIndex => $this->janeDoeName,
                $this->emailIndex => $this->janeDoeEmailAddress
            ] )
        ];

        $csvExtractor->setInput( new FileInput( $this->csvLocation ) );

        $this->assertEquals( $expected, $csvExtractor->extract() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testColumnsWithNoIndex1 () : void
    {
        $csvExtractor = new CsvExtractor();
        $csvExtractor->setColumns( [ $this->idIndex, $this->emailIndex ] );
        $csvExtractor->setInput( new FileInput( $this->csvLocation ) );

        $expected = [
            new Row( [ $this->idIndex => 1, $this->emailIndex => $this->johnDoeEmailAddress ] ),
            new Row( [ $this->idIndex => 2, $this->emailIndex => $this->janeDoeEmailAddress ] )
        ];

        $this->assertEquals( $expected, $csvExtractor->extract() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testColumnsWithNoIndex2 () : void
    {
        $csvExtractor = new CsvExtractor();
        $csvExtractor->setColumns( [ $this->idIndex, $this->nameIndex, $this->emailIndex ] );
        $csvExtractor->setInput( new FileInput( $this->csvLocation ) );

        $expected = [
            new Row( [
                $this->idIndex => 1,
                $this->nameIndex => $this->johnDoeName,
                $this->emailIndex => $this->johnDoeEmailAddress
            ] ),
            new Row( [
                $this->idIndex => 2,
                $this->nameIndex => $this->janeDoeName,
                $this->emailIndex => $this->janeDoeEmailAddress
            ] )
        ];

        $this->assertEquals( $expected, $csvExtractor->extract() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testGetColumnsWithDifferentNames () : void
    {
        $csvExtractor = new CsvExtractor();
        $csvExtractor->setColumns( [
            $this->idIndex => $this->idIndex,
            $this->emailIndex => $this->emailAddressIndex
        ] );
        $csvExtractor->setInput( new FileInput( $this->csvLocation ) );

        $expected = [
            new Row( [ $this->idIndex => 1, $this->emailAddressIndex => $this->johnDoeEmailAddress ] ),
            new Row( [ $this->idIndex => 2, $this->emailAddressIndex => $this->janeDoeEmailAddress ] )
        ];

        $this->assertEquals( $expected, $csvExtractor->extract() );
    }
}
