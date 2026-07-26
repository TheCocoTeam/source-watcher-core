<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core;

use Coco\SourceWatcher\Core\Extractors\CsvExtractor;
use Coco\SourceWatcher\Core\Extractors\FindMissingFromSequenceExtractor;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\Loaders\DatabaseLoader;
use Coco\SourceWatcher\Core\Pipeline\Pipeline;
use Coco\SourceWatcher\Core\Step\Loader;
use Coco\SourceWatcher\Core\Step\Transformer;
use Coco\SourceWatcher\Core\Transformers\RenameColumnsTransformer;
use Coco\SourceWatcher\Core\Transformers\FilterRowsTransformer;
use Coco\SourceWatcher\Core\Transformers\SortRowsTransformer;
use Coco\SourceWatcher\Core\Transformers\DeduplicateRowsTransformer;
use Coco\SourceWatcher\Core\Transformers\ChooseColumnsTransformer;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

/**
 * Class PipelineTest
 *
 * @package Coco\SourceWatcher\Tests\Core
 */
#[AllowMockObjectsWithoutExpectations]
class PipelineTest extends TestCase
{
    private Pipeline $pipeline;

    protected function setUp () : void
    {
        $this->pipeline = new Pipeline();

        $csvExtractor = new CsvExtractor();
        $csvExtractor->setInput( new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ) );

        // pipe the extractor
        $this->pipeline->pipe( $csvExtractor );

        $transformer = new RenameColumnsTransformer();
        $transformer->options( [ "columns" => [ "email" => "email_address" ] ] );

        // pipe the transformer
        $this->pipeline->pipe( $transformer );

        // pipe the loader
        $this->pipeline->pipe( $this->createMock( DatabaseLoader::class ) );
    }

    protected function tearDown () : void
    {
        unset( $this->pipeline );
    }

    public function testExecute () : void
    {
        $this->assertNull( $this->pipeline->execute() );
    }

    public function testGetResults () : void
    {
        $this->pipeline->execute();

        $this->assertNotNull( $this->pipeline->getResults() );
    }

    public function testFilterRowsTransformerRemovesNonMatchingRows () : void
    {
        $pipeline = new Pipeline();

        $csvExtractor = new CsvExtractor();
        $csvExtractor->setInput( new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ) );
        $pipeline->pipe( $csvExtractor );

        $filter = new FilterRowsTransformer();
        $filter->options( [
            "conditions" => [
                [ "field" => "id", "operator" => "greaterThan", "value" => 6 ],
            ],
        ] );
        $pipeline->pipe( $filter );

        $pipeline->execute();

        $results = $pipeline->getResults();
        $this->assertCount( 3, $results );
        $this->assertSame( [ "9", "7", "8" ], array_map( fn( $row ) => $row["id"], $results ) );
    }

    public function testSortRowsTransformerReordersCompleteResult () : void
    {
        $pipeline = new Pipeline();

        $csvExtractor = new CsvExtractor();
        $csvExtractor->setInput( new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ) );
        $pipeline->pipe( $csvExtractor );

        $sort = new SortRowsTransformer();
        $sort->options( [
            "fields" => [
                [ "field" => "id", "direction" => "asc", "type" => "numeric" ],
            ],
        ] );
        $pipeline->pipe( $sort );

        $pipeline->execute();

        $this->assertSame(
            [ "3", "4", "5", "6", "7", "8", "9" ],
            array_map( fn( $row ) => $row["id"], $pipeline->getResults() )
        );
    }

    public function testDeduplicateRowsTransformerRemovesDuplicateKeys () : void
    {
        $pipeline = new Pipeline();

        $extractor = $this->createMock( CsvExtractor::class );
        $extractor->method( "extract" )->willReturn( [
            new \Coco\SourceWatcher\Core\Data\Row( [ "id" => 1, "name" => "first" ] ),
            new \Coco\SourceWatcher\Core\Data\Row( [ "id" => 1, "name" => "duplicate" ] ),
            new \Coco\SourceWatcher\Core\Data\Row( [ "id" => 2, "name" => "other" ] ),
        ] );
        $pipeline->pipe( $extractor );

        $deduplicate = new DeduplicateRowsTransformer();
        $deduplicate->options( [ "keyFields" => [ "id" ], "keep" => "first" ] );
        $pipeline->pipe( $deduplicate );

        $pipeline->execute();

        $this->assertSame(
            [ "first", "other" ],
            array_map( fn( $row ) => $row["name"], $pipeline->getResults() )
        );
    }

    public function testChooseColumnsTransformerShapesPipelineRows () : void
    {
        $pipeline = new Pipeline();

        $csvExtractor = new CsvExtractor();
        $csvExtractor->setInput( new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ) );
        $pipeline->pipe( $csvExtractor );

        $chooseColumns = new ChooseColumnsTransformer();
        $chooseColumns->options( [ "mode" => "include", "columns" => [ "name", "id" ] ] );
        $pipeline->pipe( $chooseColumns );

        $pipeline->execute();

        $this->assertSame(
            [ "name" => "Avery", "id" => "9" ],
            $pipeline->getResults()[0]->getAttributes()
        );
    }

    public function testIterator () : void
    {
        $this->pipeline->execute();

        foreach ( $this->pipeline as $key => $value ) {
            $this->assertNotNull( $key );
            $this->assertNotNull( $value );
        }
    }

    public function testSetGetSteps () : void
    {
        $this->pipeline = new Pipeline();

        $givenSteps = [];
        $expectedSteps = [];

        $transformer = $this->createMock( Transformer::class );
        $givenSteps[] = $transformer;
        $expectedSteps[] = $transformer;

        $loader = $this->createMock( Loader::class );
        $givenSteps[] = $loader;
        $expectedSteps[] = $loader;

        $this->pipeline->setSteps( $givenSteps );

        $this->assertEquals( $expectedSteps, $this->pipeline->getSteps() );
    }

    public function testPipeStep () : void
    {
        $this->pipeline = new Pipeline();

        $transformer = $this->createMock( Transformer::class );

        $this->assertNull( $this->pipeline->pipe( $transformer ) );
    }

    public function testRewind () : void
    {
        $this->pipeline->execute();
        $this->pipeline->rewind();
        $this->assertTrue( $this->pipeline->valid() );
    }

    public function testValidWhenNoResults () : void
    {
        $this->pipeline = new Pipeline();
        $this->pipeline->setSteps( [] );
        $this->assertFalse( $this->pipeline->valid() );
    }

    public function testPipeExecutionExtractorReceivesInputFromPreviousStep () : void
    {
        $this->pipeline = new Pipeline();
        $csv = new CsvExtractor();
        $csv->setInput( new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ) );
        $this->pipeline->pipe( $csv );
        $findMissing = new FindMissingFromSequenceExtractor();
        $findMissing->setFilterField( "id" );
        $this->pipeline->pipe( $findMissing );
        $this->pipeline->execute();
        $results = $this->pipeline->getResults();
        $this->assertIsArray( $results );
    }

    /**
     * Loader that throws in load() is caught and logged; pipeline continues
     */
    public function testLoaderExceptionIsCaught () : void
    {
        $this->pipeline = new Pipeline();
        $csv = new CsvExtractor();
        $csv->setInput( new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ) );
        $this->pipeline->pipe( $csv );

        $loader = $this->createMock( Loader::class );
        $loader->method( "load" )->willThrowException( new \Exception( "load failed" ) );
        $this->pipeline->pipe( $loader );

        $this->pipeline->execute();
        $this->assertNotEmpty( $this->pipeline->getResults() );
    }

    /**
     * When logs directory cannot be created or is not writable, Pipeline uses NullHandler (e.g. CI); no exception
     */
    public function testPipelineConstructsWhenLogsDirNotWritable () : void
    {
        $pathAsFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "pipeline-logs-" . getmypid();
        touch( $pathAsFile );
        try {
            $pipeline = new Pipeline( $pathAsFile );
            $pipeline->setSteps( [] );
            $this->assertFalse( $pipeline->valid() );
        } finally {
            @unlink( $pathAsFile );
        }
    }
}
