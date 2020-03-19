<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Loaders;

use PHPUnit\Framework\TestCase;
use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use Coco\SourceWatcher\Core\Loaders\DatabaseLoader;

class DatabaseLoaderTest extends TestCase
{
    public function testSetAndGetConnector () : void
    {
        $expectedMySqlConnector = new MySqlConnector();

        $databaseLoader = new DatabaseLoader();
        $databaseLoader->setConnector( $expectedMySqlConnector );

        $this->assertSame( $expectedMySqlConnector, $databaseLoader->getConnector() );
    }
}
