<?php

include __DIR__ . "/../../includes/cli-execution-only.php";

require_once __DIR__ . "/../../../vendor/autoload.php";

use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use Coco\SourceWatcher\Core\Loaders\DatabaseLoader;
use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;

$databaseLoader = new DatabaseLoader();

$mysqlConnector = new MySqlConnector();

$mysqlConnector->setUser( "user" );
$mysqlConnector->setPassword( "password" );
$mysqlConnector->setHost( "localhost" );
$mysqlConnector->setDbName( "mysql" );
$mysqlConnector->setTableName( "people" );

$databaseLoader->setConnector( $mysqlConnector );

$row = new Row( [ "name" => "John Smith" ] );

try {
    $databaseLoader->load( $row );
} catch ( SourceWatcherException $sourceWatcherException ) {
    echo $sourceWatcherException->getMessage() . PHP_EOL;
} catch ( Exception $exception ) {
    echo $exception->getMessage() . PHP_EOL;
}
