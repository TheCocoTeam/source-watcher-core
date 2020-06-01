<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Database\Connections;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\Database\Connections\SqliteConnector;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;
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

        $givenPath = __DIR__ . "/../../../../samples/data/sqlite/people-db.sqlite";
        $expectedPath = __DIR__ . "/../../../../samples/data/sqlite/people-db.sqlite";

        $connector->setPath( $givenPath );

        $this->assertEquals( $expectedPath, $connector->getPath() );
    }

    /**
     *
     */
    public function testSetIsMemory () : void
    {
        $connector = new SqliteConnector();

        $givenMemoryValue = false;
        $expectedMemoryValue = false;

        $connector->setMemory( $givenMemoryValue );

        $this->assertEquals( $expectedMemoryValue, $connector->isMemory() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testGetConnection () : void
    {
        $connector = new SqliteConnector();
        $connector->setPath( __DIR__ . "/../../../../samples/data/sqlite/people-db.sqlite" );
        $connector->setMemory( false );

        $this->assertNotNull( $connector->getConnection() );
    }

    /**
     *
     */
    public function testInsertWithNoTableSpecified () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( Connector::class, "No_Table_Name_Found" ) );

        $connector = new SqliteConnector();
        $connector->setPath( __DIR__ . "/../../../../samples/data/sqlite/people-db.sqlite" );
        $connector->setMemory( false );

        $row = new Row( [ "name" => "John Doe", "email_address" => "johndoe@email.com" ] );

        $connector->insert( $row );
    }

    /**
     *
     */
    public function testInsertUsingWrongConnectionParameters () : void
    {
        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( Connector::class, "Unexpected_Error" ) );

        $connector = new SqliteConnector();
        $connector->setPath( __DIR__ . "/../../../../samples/data/sqlite/people-db.sqlite" );
        $connector->setMemory( false );

        $connector->setTableName( "some_non_existing_table" );

        $row = new Row( [ "name" => "John Doe", "email_address" => "johndoe@email.com" ] );

        $connector->insert( $row );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertUsingCorrectConnectionParameters () : void
    {
        $connector = new SqliteConnector();
        $connector->setPath( __DIR__ . "/../../../../samples/data/sqlite/people-db.sqlite" );
        $connector->setMemory( false );

        $connector->setTableName( "people" );

        $row = new Row( [ "name" => "John Doe", "email_address" => "johndoe@email.com" ] );

        $this->assertEquals( 1, $connector->insert( $row ) );
    }
}
