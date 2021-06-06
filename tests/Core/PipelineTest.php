<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core;

use Coco\SourceWatcher\Core\Extractors\CsvExtractor;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\Loader;
use Coco\SourceWatcher\Core\Loaders\DatabaseLoader;
use Coco\SourceWatcher\Core\Pipeline;
use Coco\SourceWatcher\Core\Transformer;
use Coco\SourceWatcher\Core\Transformers\RenameColumnsTransformer;
use PHPUnit\Framework\TestCase;

/**
 * Class PipelineTest
 *
 * @package Coco\SourceWatcher\Tests\Core
 */
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
}
