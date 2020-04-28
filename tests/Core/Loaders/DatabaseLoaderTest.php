<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Loaders;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
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
    public function testSetAndGetConnector () : void
    {
        $expectedConnector = $this->createMock( Connector::class );

        $databaseLoader = new DatabaseLoader();
        $databaseLoader->setConnector( $expectedConnector );

        $this->assertSame( $expectedConnector, $databaseLoader->getConnector() );
    }
}
