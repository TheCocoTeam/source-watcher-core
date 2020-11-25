<?php

include __DIR__ . "/../includes/cli-execution-only.php";

require_once __DIR__ . "/../../vendor/autoload.php";

use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use Coco\SourceWatcher\Core\SourceWatcher;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable( __DIR__ . "/../../" );
$dotenv->load();

$mysqlConnector = new MySqlConnector();
$mysqlConnector->setUser( $_ENV["UNIT_TEST_MYSQL_USERNAME"] );
$mysqlConnector->setPassword( $_ENV["UNIT_TEST_MYSQL_PASSWORD"] );
$mysqlConnector->setHost( $_ENV["UNIT_TEST_MYSQL_HOST"] );
$mysqlConnector->setPort( $_ENV["UNIT_TEST_MYSQL_PORT"] );
$mysqlConnector->setDbName( $_ENV["UNIT_TEST_MYSQL_DATABASE"] );
$mysqlConnector->setTableName( "people" );

$sourceWatcher = new SourceWatcher();

try {
    $sourceWatcher
        ->extract( "Csv", new FileInput( __DIR__ . "/../data/csv/csv1.csv" ), [ "columns" => [ "name", "email" ] ] )
        ->transform( "RenameColumns", [ "columns" => [ "email" => "email_address" ] ] )
        ->load( "Database", new DatabaseOutput( $mysqlConnector ) )
        ->run();
} catch ( SourceWatcherException $exception ) {
    echo sprintf( "Something unexpected went wrong: %s", $exception->getMessage() );
}
