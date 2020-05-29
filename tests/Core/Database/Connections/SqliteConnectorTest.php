<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Database\Connections;

use Coco\SourceWatcher\Core\Database\Connections\SqliteConnector;
use PHPUnit\Framework\TestCase;

/**
 * Class SqliteConnectorTest
 * @package Coco\SourceWatcher\Tests\Core\Database\Connections
 */
class SqliteConnectorTest extends TestCase
{
    /**
     *
     */
    public function testSetGetPath () : void
    {
        $connector = new SqliteConnector();

        $givenPath = __DIR__ . "/../../samples/data/sqlite/people-db.sqlite";
        $expectedPath = __DIR__ . "/../../samples/data/sqlite/people-db.sqlite";

        $connector->setPath( $givenPath );

        $this->assertEquals( $expectedPath, $connector->getPath() );
    }

    /**
     *
     */
    public function testSetIsMemory () : void
    {
        $connector = new SqliteConnector();

        $givenMemoryValue = true;
        $expectedMemoryValue = true;

        $connector->setMemory( $givenMemoryValue );

        $this->assertEquals( $expectedMemoryValue, $connector->isMemory() );
    }
}
