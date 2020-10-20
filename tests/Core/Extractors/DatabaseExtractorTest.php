<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use Coco\SourceWatcher\Core\Extractors\DatabaseExtractor;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Tests\Common\ParentTest;

/**
 * Class DatabaseExtractorTest
 *
 * @package Coco\SourceWatcher\Tests\Core\Extractors
 */
class DatabaseExtractorTest extends ParentTest
{
    private string $tableName;
    private MySqlConnector $mysqlConnector;

    public function setUp () : void
    {
        $this->tableName = "people";

        $this->mysqlConnector = new MySqlConnector();
        $this->mysqlConnector->setUser( $this->getEnvironmentVariable( "UNIT_TEST_MYSQL_USERNAME", null ) );
        $this->mysqlConnector->setPassword( $this->getEnvironmentVariable( "UNIT_TEST_MYSQL_PASSWORD", null ) );
        $this->mysqlConnector->setHost( $this->getEnvironmentVariable( "UNIT_TEST_MYSQL_HOST", null ) );
        $this->mysqlConnector->setPort( $this->getEnvironmentVariable( "UNIT_TEST_MYSQL_PORT", 5432, "intval" ) );
        $this->mysqlConnector->setDbName( $this->getEnvironmentVariable( "UNIT_TEST_MYSQL_DATABASE", null ) );

        $this->mysqlConnector->setTableName( $this->tableName );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testExtractFromMySqlTable () : void
    {
        $query = "SELECT * FROM " . $this->tableName;

        $databaseExtractor = new DatabaseExtractor( $this->mysqlConnector, $query );

        $result = $databaseExtractor->extract();

        $this->assertNotEmpty( $result );
    }
}
