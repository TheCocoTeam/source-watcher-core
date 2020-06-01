<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Database\Connections;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;
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
        $connector->setCharset( "utf8" );

        $this->assertNotNull( $connector->getConnection() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertWithNoTableSpecified () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( Connector::class, "No_Table_Name_Found" ) );

        $connector = new MySqlConnector();
        $connector->setUser( "admin" );
        $connector->setPassword( "secret" );
        $connector->setHost( "localhost" );
        $connector->setPort( 3306 );;
        $connector->setDbName( "people" );
        $connector->setUnixSocket( "/var/run/mysqld/mysqld.sock" );
        $connector->setCharset( "utf8" );

        $row = new Row( [ "name" => "John Doe", "email_address" => "johndoe@email.com" ] );

        $connector->insert( $row );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertUsingWrongConnectionParameters () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( Connector::class, "Connection_Object_Not_Connected_Cannot_Insert" ) );

        $connector = new MySqlConnector();
        $connector->setUser( "admin" );
        $connector->setPassword( "secret" );
        $connector->setHost( "localhost" );
        $connector->setPort( 3306 );;
        $connector->setDbName( "people" );
        $connector->setUnixSocket( "/var/run/mysqld/mysqld.sock" );
        $connector->setCharset( "utf8" );

        $connector->setTableName( "people" );

        $row = new Row( [ "name" => "John Doe", "email_address" => "johndoe@email.com" ] );

        $connector->insert( $row );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertUsingEnvironmentVariables () : void
    {
        $username = array_key_exists( "UNIT_TEST_MYSQL_USERNAME", $_ENV ) ? $_ENV["UNIT_TEST_MYSQL_USERNAME"] : getenv("UNIT_TEST_MYSQL_USERNAME");
        $password = array_key_exists( "UNIT_TEST_MYSQL_PASSWORD", $_ENV ) ? $_ENV["UNIT_TEST_MYSQL_PASSWORD"] : null;
        $host = array_key_exists( "UNIT_TEST_MYSQL_HOST", $_ENV ) ? $_ENV["UNIT_TEST_MYSQL_HOST"] : null;
        $port = array_key_exists( "UNIT_TEST_MYSQL_PORT", $_ENV ) ? intval( $_ENV["UNIT_TEST_MYSQL_PORT"] ) : 3306;
        $database = array_key_exists( "UNIT_TEST_MYSQL_DATABASE", $_ENV ) ? $_ENV["UNIT_TEST_MYSQL_DATABASE"] : null;

        $connector = new MySqlConnector();
        $connector->setUser( $username );
        $connector->setPassword( $password );
        $connector->setHost( $host );
        $connector->setPort( $port );
        $connector->setDbName( $database );

        $connector->setTableName( "people" );

        $row = new Row( [ "name" => "John Doe", "email_address" => "johndoe@email.com" ] );

        $this->assertEquals( 1, $connector->insert( $row ) );
    }
}
