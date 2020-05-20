<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\Database\Connections\SqliteConnector;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use Coco\SourceWatcher\Core\Pipeline;
use Coco\SourceWatcher\Core\SourceWatcher;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Core\StepLoader;
use PHPUnit\Framework\TestCase;

/**
 * Class SourceWatcherTest
 * @package Coco\SourceWatcher\Tests\Core
 */
class SourceWatcherTest extends TestCase
{
    /**
     * @var SourceWatcher
     */
    private SourceWatcher $sourceWatcher;

    /**
     *
     */
    protected function setUp () : void
    {
        $this->sourceWatcher = new SourceWatcher();
    }

    /**
     *
     */
    protected function tearDown () : void
    {
        unset( $this->sourceWatcher );
    }

    /**
     *
     */
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
        $sourceWatcher = $this->sourceWatcher->extract( "Csv", new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ), [ "columns" => array( "name", "email" ) ] );
        $this->assertNotNull( $sourceWatcher );
        $this->assertInstanceOf( SourceWatcher::class, $sourceWatcher );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testExtractException () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "The extractor NonExistent can't be found." );

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

    /**
     * @throws SourceWatcherException
     */
    public function testTransformException () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "The transformer NonExistent can't be found." );

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

    /**
     * @throws SourceWatcherException
     */
    public function testLoadException () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "The loader NonExistent can't be found." );

        $this->sourceWatcher->load( "NonExistent", $this->createMock( DatabaseOutput::class ) );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testRun () : void
    {
        $connector = new SqliteConnector();
        $connector->setPath( __DIR__ . "/../../samples/data/sqlite/people-db.sqlite" );
        $connector->setTableName( "people" );

        $this->assertNull(
            $this->sourceWatcher
                ->extract( "Csv", new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ), [ "columns" => array( "name", "email" ) ] )
                ->transform( "RenameColumns", [ "columns" => array( "email" => "email_address" ) ] )
                ->load( "Database", new DatabaseOutput( $connector ) )
                ->run()
        );
    }
}
