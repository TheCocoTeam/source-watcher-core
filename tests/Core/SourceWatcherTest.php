<?php declare(strict_types=1);

namespace Coco\SourceWatcher\Tests\Core;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use Coco\SourceWatcher\Core\Pipeline;
use Coco\SourceWatcher\Core\SourceWatcher;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Core\StepLoader;
use PHPUnit\Framework\TestCase;

class SourceWatcherTest extends TestCase
{
    private SourceWatcher $sourceWatcher;

    protected function setUp () : void
    {
        $this->sourceWatcher = new SourceWatcher();
    }

    protected function tearDown () : void
    {
        unset( $this->sourceWatcher );
    }

    public function testGetStepLoaderAndPipeline () : void
    {
        $stepLoader = $this->sourceWatcher->getStepLoader();
        $this->assertNotNull( $stepLoader );
        $this->assertInstanceOf( StepLoader::class, $stepLoader );

        $pipeline = $this->sourceWatcher->getPipeline();
        $this->assertNotNull( $stepLoader );
        $this->assertInstanceOf( Pipeline::class, $pipeline );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testExtract () : void
    {
        $sourceWatcher = $this->sourceWatcher->extract( "Csv", new FileInput( __DIR__ . "/../data/csv/csv1.csv" ), [ "columns" => array( "name", "email" ) ] );
        $this->assertNotNull( $sourceWatcher );
        $this->assertInstanceOf( SourceWatcher::class, $sourceWatcher );
    }

    public function testExtractException () : void
    {
        $this->expectException( SourceWatcherException::class );

        $this->sourceWatcher->extract( "NonExistent", $this->createMock( FileInput::class ), [] );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testTransform () : void
    {
        $sourceWatcher = $this->sourceWatcher->transform( "RenameColumns", [ "columns" => array( "email" => "email_address" ) ] );
        $this->assertNotNull( $sourceWatcher );
        $this->assertInstanceOf( SourceWatcher::class, $sourceWatcher );
    }

    public function testTransformException () : void
    {
        $this->expectException( SourceWatcherException::class );

        $this->sourceWatcher->transform( "NonExistent", [] );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testLoad () : void
    {
        $sourceWatcher = $this->sourceWatcher->load( "Database", new DatabaseOutput( $this->createMock( Connector::class ) ) );
        $this->assertNotNull( $sourceWatcher );
        $this->assertInstanceOf( SourceWatcher::class, $sourceWatcher );
    }

    public function testLoadException () : void
    {
        $this->expectException( SourceWatcherException::class );

        $this->sourceWatcher->load( "NonExistent", $this->createMock( DatabaseOutput::class ) );
    }
}
