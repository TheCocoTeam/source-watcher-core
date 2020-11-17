<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use Coco\SourceWatcher\Core\Database\Connections\SqliteConnector;
use Coco\SourceWatcher\Core\IO\Inputs\DatabaseInput;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use Coco\SourceWatcher\Core\Pipeline;
use Coco\SourceWatcher\Core\SourceWatcher;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Core\StepLoader;
use PHPUnit\Framework\TestCase;

/**
 * Class SourceWatcherTest
 *
 * @package Coco\SourceWatcher\Tests\Core
 */
class SourceWatcherTest extends TestCase
{
    private string $columnsIndex;
    private SourceWatcher $sourceWatcher;
    private string $nonExistentArtifactName;
    private SqliteConnector $sqliteConnector;
    private MySqlConnector $mysqlConnector;

    protected function setUp () : void
    {
        $this->columnsIndex = "columns";
        $this->sourceWatcher = new SourceWatcher();
        $this->nonExistentArtifactName = "NonExistent";

        $this->sqliteConnector = new SqliteConnector();
        $this->sqliteConnector->setPath( __DIR__ . "/../../samples/data/sqlite/people-db.sqlite" );
        $this->sqliteConnector->setTableName( "people" );

        $this->mysqlConnector = new MySqlConnector();
        $this->mysqlConnector->setUser( "admin" );
        $this->mysqlConnector->setPassword( "secret" );
        $this->mysqlConnector->setHost( "localhost" );
        $this->mysqlConnector->setPort( 3306 );
        $this->mysqlConnector->setDbName( "people" );
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
        $sourceWatcherInstance = $this->sourceWatcher->extract( "Csv",
            new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ),
            [ $this->columnsIndex => [ "name", "email" ] ] ); // #NOSONAR
        $this->assertNotNull( $sourceWatcherInstance );
        $this->assertInstanceOf( SourceWatcher::class, $sourceWatcherInstance );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testExtractException () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "The extractor NonExistent can't be found." );

        $this->sourceWatcher->extract( $this->nonExistentArtifactName, $this->createMock( FileInput::class ), [] );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testTransform () : void
    {
        $sourceWatcherInstance = $this->sourceWatcher->transform( "RenameColumns",
            [ $this->columnsIndex => [ "email" => "email_address" ] ] );
        $this->assertNotNull( $sourceWatcherInstance );
        $this->assertInstanceOf( SourceWatcher::class, $sourceWatcherInstance );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testTransformException () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "The transformer NonExistent can't be found." );

        $this->sourceWatcher->transform( $this->nonExistentArtifactName, [] );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testLoad () : void
    {
        $sourceWatcherInstance = $this->sourceWatcher->load( "Database",
            new DatabaseOutput( $this->createMock( Connector::class ) ) );
        $this->assertNotNull( $sourceWatcherInstance );
        $this->assertInstanceOf( SourceWatcher::class, $sourceWatcherInstance );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testLoadException () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( "The loader NonExistent can't be found." );

        $this->sourceWatcher->load( $this->nonExistentArtifactName, $this->createMock( DatabaseOutput::class ) );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testRun () : void
    {
        $this->assertNull( $this->sourceWatcher
            ->extract( "Csv", new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ),
                [ $this->columnsIndex => [ "name", "email" ] ] )
            ->transform( "RenameColumns", [ $this->columnsIndex => [ "email" => "email_address" ] ] )
            ->load( "Database", new DatabaseOutput( $this->sqliteConnector ) )
            ->run()
        );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testSaveWithoutName () : void
    {
        $this->sourceWatcher
            ->extract( "Csv", new FileInput( __DIR__ . "/../../samples/data/csv/csv1.csv" ),
                [ $this->columnsIndex => [ "name", "email" ] ] )
            ->transform( "RenameColumns", [ $this->columnsIndex => [ "email" => "email_address" ] ] )
            ->load( "Database", new DatabaseOutput( $this->sqliteConnector ) );

        $transformationFile = $this->sourceWatcher->save();
        $this->assertNotNull( $transformationFile );
        $this->assertFileExists( $transformationFile );
        $this->assertStringNotEqualsFile( $transformationFile, "" );

        $this->sourceWatcher->flush();

        $this->sourceWatcher
            ->extract( "Json", new FileInput( __DIR__ . "/../../samples/data/json/colors.json" ),
                [ $this->columnsIndex => [ "color" => "colors.*.color" ] ] )
            ->transform( "RenameColumns", [ $this->columnsIndex => [ "color" => "colour" ] ] )
            ->load( "Database", new DatabaseOutput( $this->sqliteConnector ) );

        $transformationFile = $this->sourceWatcher->save();
        $this->assertNotNull( $transformationFile );
        $this->assertFileExists( $transformationFile );
        $this->assertStringNotEqualsFile( $transformationFile, "" );

        $this->sourceWatcher->flush();

        $this->sourceWatcher
            ->extract( "Database", new DatabaseInput( $this->mysqlConnector ),
                [ "query" => "SELECT * FROM people ORDER BY last_name, first_name" ] )
            ->transform( "RenameColumns", [ $this->columnsIndex => [ "email" => "email_address" ] ] )
            ->load( "Database", new DatabaseOutput( $this->sqliteConnector ) );

        $transformationFile = $this->sourceWatcher->save();
        $this->assertNotNull( $transformationFile );
        $this->assertFileExists( $transformationFile );
        $this->assertStringNotEqualsFile( $transformationFile, "" );

        $this->sourceWatcher->flush();
    }
}
