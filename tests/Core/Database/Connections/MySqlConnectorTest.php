<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Database\Connections;

use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;
use Doctrine\DBAL\Connection;
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

    /**
     * @throws SourceWatcherException
     */
    public function testGetConnection () : void
    {
        $connector = new MySqlConnector();
        $connector->setUser( "admin" );
        $connector->setPassword( "secret" );
        $connector->setHost( "localhost" );
        $connector->setPort( 3306 );;
        $connector->setDbName( "people" );
        $connector->setUnixSocket( "/var/run/mysqld/mysqld.sock" );
        $connector->setCharset( "utf-8" );

        $this->assertNotNull( $connector->connect() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertWithNoTableSpecified () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( MySqlConnector::class, "No_Table_Name_Found" ) );

        $connector = new MySqlConnector();
        $connector->setUser( "admin" );
        $connector->setPassword( "secret" );
        $connector->setHost( "localhost" );
        $connector->setPort( 3306 );;
        $connector->setDbName( "people" );
        $connector->setUnixSocket( "/var/run/mysqld/mysqld.sock" );
        $connector->setCharset( "utf-8" );

        $row = new Row( [ "id" => "1", "name" => "John Doe", "email_address" => "johndoe@email.com" ] );

        $connector->insert( $row );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertUsingWrongConnectionParameters () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( MySqlConnector::class, "Connection_Object_Not_Connected_Cannot_Insert" ) );

        $connector = new MySqlConnector();
        $connector->setUser( "admin" );
        $connector->setPassword( "secret" );
        $connector->setHost( "localhost" );
        $connector->setPort( 3306 );;
        $connector->setDbName( "people" );
        $connector->setUnixSocket( "/var/run/mysqld/mysqld.sock" );
        $connector->setCharset( "utf-8" );

        $connector->setTableName( "people" );

        $row = new Row( [ "id" => "1", "name" => "John Doe", "email_address" => "johndoe@email.com" ] );

        $connector->insert( $row );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertWithMockConnection () : void
    {
        $mockConnection = $this->createMock( Connection::class );
        $mockConnection->method( "isConnected" )->willReturn( true );

        $mockMySqlConnector = $this->createMock( MySqlConnector::class );
        $mockMySqlConnector->method( "connect" )->willReturn( $mockConnection );
        $mockMySqlConnector->method( "insert" )->willReturn( 1 );

        $mockMySqlConnector->setTableName( "people" );

        $row = new Row( [ "id" => "1", "name" => "John Doe", "email_address" => "johndoe@email.com" ] );

        $expectedNumberOfAffectedRows = 1;

        $this->assertEquals( $expectedNumberOfAffectedRows, $mockMySqlConnector->insert( $row ) );
    }
}
