<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Database\Connections;

use Coco\SourceWatcher\Core\Database\Connections\PostgreSqlConnector;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Doctrine\DBAL\DBALException;
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

    /**
     *
     */
    public function testSetGetDefaultDatabaseName () : void
    {
        $connector = new PostgreSqlConnector();

        $givenDefaultDatabaseName = "people";
        $expectedDefaultDatabaseName = "people";

        $connector->setDefaultDatabaseName( $givenDefaultDatabaseName );

        $this->assertEquals( $expectedDefaultDatabaseName, $connector->getDefaultDatabaseName() );
    }

    /**
     *
     */
    public function testSetGetSslMode () : void
    {
        $connector = new PostgreSqlConnector();

        $givenSslMode = "allow";
        $expectedSslMode = "allow";

        $connector->setSslMode( $givenSslMode );

        $this->assertEquals( $expectedSslMode, $connector->getSslMode() );
    }

    /**
     *
     */
    public function testSetGetSslRootCert () : void
    {
        $connector = new PostgreSqlConnector();

        $givenSslRootCert = "~/.postgresql/root.crt";
        $expectedSslRootCert = "~/.postgresql/root.crt";

        $connector->setSslRootCert( $givenSslRootCert );

        $this->assertEquals( $expectedSslRootCert, $connector->getSslRootCert() );
    }

    /**
     *
     */
    public function testSetGetSslCert () : void
    {
        $connector = new PostgreSqlConnector();

        $givenSslCert = "~/.postgresql/postgresql.crt";
        $expectedSslCert = "~/.postgresql/postgresql.crt";

        $connector->setSslCert( $givenSslCert );

        $this->assertEquals( $expectedSslCert, $connector->getSslCert() );
    }

    /**
     *
     */
    public function testSetGetSslKey () : void
    {
        $connector = new PostgreSqlConnector();

        $givenSslKey = "~/.postgresql/postgresql.key";
        $expectedSslKey = "~/.postgresql/postgresql.key";

        $connector->setSslKey( $givenSslKey );

        $this->assertEquals( $expectedSslKey, $connector->getSslKey() );
    }

    /**
     *
     */
    public function testSetGetSslCrl () : void
    {
        $connector = new PostgreSqlConnector();

        $givenSslCrl = "~/.postgresql/root.crl";
        $expectedSslCrl = "~/.postgresql/root.crl";

        $connector->setSslCrl( $givenSslCrl );

        $this->assertEquals( $expectedSslCrl, $connector->getSslCrl() );
    }

    /**
     *
     */
    public function testSetGetApplicationName () : void
    {
        $connector = new PostgreSqlConnector();

        $givenAppName = "App Name for PG Stat Activity";
        $expectedAppName = "App Name for PG Stat Activity";

        $connector->setApplicationName( $givenAppName );

        $this->assertEquals( $expectedAppName, $connector->getApplicationName() );
    }

    /**
     * @throws DBALException
     */
    public function testGetConnection () : void
    {
        $connector = new PostgreSqlConnector();
        $connector->setUser( "admin" );
        $connector->setPassword( "secret" );
        $connector->setHost( "localhost" );
        $connector->setPort( 5432 );;
        $connector->setDbName( "people" );
        $connector->setCharset( "utf8" );
        $connector->setDefaultDatabaseName( "people" );
        $connector->setSslMode( "allow" );
        $connector->setSslRootCert( "~/.postgresql/root.crt" );
        $connector->setSslCert( "~/.postgresql/postgresql.crt" );
        $connector->setSslKey( "~/.postgresql/postgresql.key" );
        $connector->setSslCrl( "~/.postgresql/root.crl" );
        $connector->setApplicationName( "App Name for PG Stat Activity" );

        $this->assertNotNull( $connector->getConnection() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testInsertUsingEnvironmentVariables () : void
    {
        $user = array_key_exists( "UNIT_TEST_POSTGRESQL_USER", $_ENV ) ? $_ENV["UNIT_TEST_POSTGRESQL_USER"] : null;
        $password = array_key_exists( "UNIT_TEST_POSTGRESQL_PASSWORD", $_ENV ) ? $_ENV["UNIT_TEST_POSTGRESQL_PASSWORD"] : null;
        $host = array_key_exists( "UNIT_TEST_POSTGRESQL_HOST", $_ENV ) ? $_ENV["UNIT_TEST_POSTGRESQL_HOST"] : null;
        $port = array_key_exists( "UNIT_TEST_POSTGRESQL_PORT", $_ENV ) ? intval( $_ENV["UNIT_TEST_POSTGRESQL_PORT"] ) : 5432;
        $dbName = array_key_exists( "UNIT_TEST_POSTGRESQL_DB_NAME", $_ENV ) ? $_ENV["UNIT_TEST_POSTGRESQL_DB_NAME"] : null;
        $defaultDatabaseName = array_key_exists( "UNIT_TEST_POSTGRESQL_DEFAULT_DATABASE_NAME", $_ENV ) ? $_ENV["UNIT_TEST_POSTGRESQL_DEFAULT_DATABASE_NAME"] : null;

        $connector = new PostgreSqlConnector();
        $connector->setUser( $user );
        $connector->setPassword( $password );
        $connector->setHost( $host );
        $connector->setPort( $port );;
        $connector->setDbName( $dbName );
        $connector->setDefaultDatabaseName( $defaultDatabaseName );

        $connector->setTableName( "people" );

        $row = new Row( [ "name" => "John Doe", "email_address" => "johndoe@email.com" ] );

        $this->assertEquals( 1, $connector->insert( $row ) );
    }
}
