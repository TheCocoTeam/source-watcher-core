<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Database\Connections;

use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use PHPUnit\Framework\TestCase;

/**
 * Class ConnectorTest
 * @package Coco\SourceWatcher\Tests\Core\Database\Connections
 */
class ConnectorTest extends TestCase
{
    /**
     *
     */
    public function testGetDriver () : void
    {
        $connector = new MySqlConnector();

        $expected = "pdo_mysql";

        $this->assertEquals( $expected, $connector->getDriver() );
    }

    /**
     *
     */
    public function testSetGetUser () : void
    {
        $connector = new MySqlConnector();

        $given = "user";
        $expected = "user";

        $connector->setUser( $given );

        $this->assertEquals( $expected, $connector->getUser() );
    }

    /**
     *
     */
    public function testSetGetPassword () : void
    {
        $connector = new MySqlConnector();

        $given = "password";
        $expected = "password";

        $connector->setPassword( $given );

        $this->assertEquals( $expected, $connector->getPassword() );
    }

    /**
     *
     */
    public function testSetGetTableName () : void
    {
        $connector = new MySqlConnector();

        $given = "people";
        $expected = "people";

        $connector->setTableName( $given );

        $this->assertEquals( $expected, $connector->getTableName() );
    }
}
