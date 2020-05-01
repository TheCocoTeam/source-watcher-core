<?php

include __DIR__ . "/../includes/cli-execution-only.php";

require_once __DIR__ . "/../../vendor/autoload.php";

use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use Coco\SourceWatcher\Core\SourceWatcher;

$mysqlConnector = new MySqlConnector();
$mysqlConnector->setUser( "user" );
$mysqlConnector->setPassword( "password" );
$mysqlConnector->setHost( "host" );
$mysqlConnector->setPort( 3306 );
$mysqlConnector->setDbName( "tests" );
$mysqlConnector->setTableName( "people" );

$sourceWatcher = new SourceWatcher();
$sourceWatcher
    ->extract( "Csv", new FileInput( __DIR__ . "/../data/csv/csv1.csv" ), [ "columns" => array( "name", "email" ) ] )
    ->transform( "RenameColumns", [ "columns" => array( "email" => "email_address" ) ] )
    ->load( "Database", new DatabaseOutput( $mysqlConnector ) )
    ->run();
