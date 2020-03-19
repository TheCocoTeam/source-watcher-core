<?php

include __DIR__ . "/../../includes/cli-execution-only.php";

require_once __DIR__ . "/../../../vendor/autoload.php";

use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use Coco\SourceWatcher\Core\Loaders\DatabaseLoader;
use Coco\SourceWatcher\Core\Row;

$connector = new MySqlConnector();
$connector->setUser( "user" );
$connector->setPassword( "password" );
$connector->setHost( "localhost" );
$connector->setPort( 3306 );
$connector->setDbName( "mysql" );

$connector->setTableName( "people" );

$databaseLoader = new DatabaseLoader();
$databaseLoader->setConnector( $connector );

$row = new Row( [ "name" => "John Doe" ] );

$databaseLoader->load( $row );
