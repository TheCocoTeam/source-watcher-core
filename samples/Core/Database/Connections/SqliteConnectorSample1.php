<?php

include __DIR__ . "/../../../includes/cli-execution-only.php";

require_once __DIR__ . "/../../../../vendor/autoload.php";

use Coco\SourceWatcher\Core\Database\Connections\SqliteConnector;
use Coco\SourceWatcher\Core\Loaders\DatabaseLoader;
use Coco\SourceWatcher\Core\Row;

$connector = new SqliteConnector();
$connector->setPath( "/host/shared/sqlite/test/people-db.sqlite" );
$connector->setTableName( "people" );

$databaseLoader = new DatabaseLoader();
$databaseLoader->setConnector( $connector );

$row = new Row( [ "name" => "John Doe" ] );

$databaseLoader->load( $row );
