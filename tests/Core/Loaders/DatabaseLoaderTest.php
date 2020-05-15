<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Loaders;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use Coco\SourceWatcher\Core\IO\Outputs\Output;
use Coco\SourceWatcher\Core\Loaders\DatabaseLoader;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use PHPUnit\Framework\TestCase;

/**
 * Class DatabaseLoaderTest
 * @package Coco\SourceWatcher\Tests\Core\Loaders
 */
class DatabaseLoaderTest extends TestCase
{
    /**
     * This unit test is testing the getOutput and setOutput methods of the Loader abstract class.
     */
    public function testSetAndGetOutput () : void
    {
        $connectorMock = $this->createMock( Connector::class );

        $databaseOutput = new DatabaseOutput( $connectorMock );

        $databaseLoader = new DatabaseLoader();
        $databaseLoader->setOutput( $databaseOutput );

        $this->assertSame( $databaseOutput, $databaseLoader->getOutput() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertRowWithNoOutput () : void
    {
        $this->expectException( SourceWatcherException::class );

        $databaseLoader = new DatabaseLoader();
        $databaseLoader->load( new Row( [ "name" => "Jane Doe" ] ) );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertRowWithNonDatabaseOutput () : void
    {
        $this->expectException( SourceWatcherException::class );

        $databaseLoader = new DatabaseLoader();
        $databaseLoader->setOutput( $this->createMock( Output::class ) );
        $databaseLoader->load( new Row( [ "name" => "Jane Doe" ] ) );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertRowWithDatabaseOutputWithoutConnector () : void
    {
        $this->expectException( SourceWatcherException::class );

        $databaseLoader = new DatabaseLoader();
        $databaseLoader->setOutput( new DatabaseOutput() );
        $databaseLoader->load( new Row( [ "name" => "Jane Doe" ] ) );
    }
}
