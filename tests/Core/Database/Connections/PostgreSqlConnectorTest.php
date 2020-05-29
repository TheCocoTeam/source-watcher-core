<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Database\Connections;

use Coco\SourceWatcher\Core\Database\Connections\PostgreSqlConnector;
use PHPUnit\Framework\TestCase;

/**
 * Class PostgreSqlConnectorTest
 * @package Coco\SourceWatcher\Tests\Core\Database\Connections
 */
class PostgreSqlConnectorTest extends TestCase
{
    /**
     *
     */
    public function testSetGetCharset () : void
    {
        $connector = new PostgreSqlConnector();

        $givenCharset = "utf8";
        $expectedCharset = "utf8";

        $connector->setCharset( $givenCharset );

        $this->assertEquals( $expectedCharset, $connector->getCharset() );
    }
}
