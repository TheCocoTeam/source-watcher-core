<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Database\Connections;

use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientServerDatabaseConnectorTest
 *
 * @package Coco\SourceWatcher\Tests\Core\Database\Connections
 */
class ClientServerDatabaseConnectorTest extends TestCase
{
    public function testSetGetHost () : void
    {
        $connector = new MySqlConnector();

        $given = "localhost";
        $expected = "localhost";

        $connector->setHost( $given );

        $this->assertEquals( $expected, $connector->getHost() );
    }

    public function testSetGetPort () : void
    {
        $connector = new MySqlConnector();

        $given = 3306;
        $expected = 3306;

        $connector->setPort( $given );

        $this->assertEquals( $expected, $connector->getPort() );
    }

    public function testSetGetDbName () : void
    {
        $connector = new MySqlConnector();

        $given = "test";
        $expected = "test";

        $connector->setDbName( $given );

        $this->assertEquals( $expected, $connector->getDbName() );
    }
}
