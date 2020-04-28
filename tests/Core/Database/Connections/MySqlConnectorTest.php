<?php

namespace Coco\SourceWatcher\Tests\Core\Database\Connections;

use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use PHPUnit\Framework\TestCase;

/**
 * Class MySqlConnectorTest
 * @package Coco\SourceWatcher\Tests\Core\Database\Connections
 */
class MySqlConnectorTest extends TestCase
{
    /**
     *
     */
    public function testSetGetUnixSocket () : void
    {
        $connector = new MySqlConnector();

        $given = "/var/run/mysqld/mysqld.sock";
        $expected = "/var/run/mysqld/mysqld.sock";

        $connector->setUnixSocket( $given );

        $this->assertEquals( $expected, $connector->getUnixSocket() );
    }

    /**
     *
     */
    public function testSetGetCharset () : void
    {
        $connector = new MySqlConnector();

        $given = "utf8";
        $expected = "utf8";

        $connector->setCharset( $given );

        $this->assertEquals( $expected, $connector->getCharset() );
    }
}
