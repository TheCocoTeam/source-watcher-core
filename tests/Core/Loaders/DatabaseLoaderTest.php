<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Loaders;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use Coco\SourceWatcher\Core\Loaders\DatabaseLoader;
use PHPUnit\Framework\TestCase;

/**
 * Class DatabaseLoaderTest
 * @package Coco\SourceWatcher\Tests\Core\Loaders
 */
class DatabaseLoaderTest extends TestCase
{
    /**
     *
     */
    public function testSetAndGetOutput () : void
    {
        $expectedConnector = $this->createMock( Connector::class );

        $databaseOutput = new DatabaseOutput( $expectedConnector );

        $databaseLoader = new DatabaseLoader();
        $databaseLoader->setOutput( $databaseOutput );

        $this->assertSame( $databaseOutput, $databaseLoader->getOutput() );
    }
}
