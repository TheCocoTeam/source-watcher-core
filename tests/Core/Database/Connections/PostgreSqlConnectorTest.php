<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Database\Connections;

use Coco\SourceWatcher\Core\Database\Connections\PostgreSqlConnector;
use Coco\SourceWatcher\Core\SourceWatcherException;
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
     * @throws SourceWatcherException
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

        $this->assertNotNull( $connector->connect() );
    }
}
