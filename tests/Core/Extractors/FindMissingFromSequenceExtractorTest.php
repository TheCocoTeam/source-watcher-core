<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Extractors\FindMissingFromSequenceExtractor;
use Coco\SourceWatcher\Core\IO\Inputs\ExtractorResultInput;
use Coco\SourceWatcher\Core\Step\Extractor;
use PHPUnit\Framework\TestCase;

/** Stub for testing extractors that consume another extractor's result. */
class StubExtractorForFindMissing extends Extractor
{
    public function extract () { return $this->result ?? []; }
}

/**
 * Class FindMissingFromSequenceExtractorTest
 *
 * @package Coco\SourceWatcher\Tests\Core\Extractors
 */
class FindMissingFromSequenceExtractorTest extends TestCase
{
    private FindMissingFromSequenceExtractor $findMissingFromSequenceExtractor;

    public function setUp () : void
    {
        $this->findMissingFromSequenceExtractor = new FindMissingFromSequenceExtractor();
    }

    public function testSetGetFilterField () : void
    {
        $filterField = "some_given_field";

        $this->findMissingFromSequenceExtractor->setFilterField( $filterField );

        $this->assertNotNull( $this->findMissingFromSequenceExtractor->getFilterField() );
        $this->assertEquals( $filterField, $this->findMissingFromSequenceExtractor->getFilterField() );
    }

    public function testExtractFindsMissingIds () : void
    {
        $stub = new StubExtractorForFindMissing();
        $stub->setResult( [
            new Row( [ "id" => 1 ] ),
            new Row( [ "id" => 3 ] ),
            new Row( [ "id" => 5 ] ),
        ] );
        $input = new ExtractorResultInput( $stub );
        $this->findMissingFromSequenceExtractor->setInput( $input );
        $this->findMissingFromSequenceExtractor->setFilterField( "id" );

        $result = $this->findMissingFromSequenceExtractor->extract();

        $this->assertCount( 2, $result );
        $this->assertSame( 2, $result[0]["id"] );
        $this->assertSame( 4, $result[1]["id"] );
        $this->assertSame( $result, $this->findMissingFromSequenceExtractor->getResult() );
    }

    public function testExtractReturnsEmptyWhenNoGaps () : void
    {
        $stub = new StubExtractorForFindMissing();
        $stub->setResult( [
            new Row( [ "id" => 1 ] ),
            new Row( [ "id" => 2 ] ),
        ] );
        $input = new ExtractorResultInput( $stub );
        $this->findMissingFromSequenceExtractor->setInput( $input );
        $this->findMissingFromSequenceExtractor->setFilterField( "id" );

        $result = $this->findMissingFromSequenceExtractor->extract();

        $this->assertCount( 0, $result );
    }

    public function testExtractAcceptsIntegerLikeNumericStrings () : void
    {
        $stub = new StubExtractorForFindMissing();
        $stub->setResult( [
            new Row( [ "id" => "1" ] ),
            new Row( [ "id" => "3" ] ),
        ] );
        $this->findMissingFromSequenceExtractor->setInput( new ExtractorResultInput( $stub ) );

        $result = $this->findMissingFromSequenceExtractor->extract();

        $this->assertCount( 1, $result );
        $this->assertSame( 2, $result[0]["id"] );
    }

    public function testExtractRejectsItemsThatAreNotRows () : void
    {
        $stub = new StubExtractorForFindMissing();
        $stub->setResult( [ [ "id" => 1 ] ] );
        $this->findMissingFromSequenceExtractor->setInput( new ExtractorResultInput( $stub ) );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "requires every input item to be a Row; item 0 is array" );

        $this->findMissingFromSequenceExtractor->extract();
    }

    public function testExtractRejectsRowsMissingTheFilterField () : void
    {
        $stub = new StubExtractorForFindMissing();
        $stub->setResult( [ new Row( [ "name" => "Alice" ] ) ] );
        $this->findMissingFromSequenceExtractor->setInput( new ExtractorResultInput( $stub ) );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( 'requires field "id" on every input row; row 0 does not contain it' );

        $this->findMissingFromSequenceExtractor->extract();
    }

    public function testExtractRejectsNonIntegerSequenceValues () : void
    {
        $stub = new StubExtractorForFindMissing();
        $stub->setResult( [ new Row( [ "id" => "1.5" ] ) ] );
        $this->findMissingFromSequenceExtractor->setInput( new ExtractorResultInput( $stub ) );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( 'requires field "id" to contain an integer value; row 0 contains string' );

        $this->findMissingFromSequenceExtractor->extract();
    }
}
