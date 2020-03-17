<?php

include __DIR__ . "/../../includes/cli-execution-only.php";

require_once __DIR__ . "/../../../vendor/autoload.php";

use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;

$connector = new MySqlConnector();
$connector->setUser( "user" );
$connector->setPassword( "password" );
$connector->setHost( "localhost" );
$connector->setPort( 3306 );
$connector->setDbName( "mysql" );

$connection = $connector->connect();

$queryBuilder = $connection->createQueryBuilder();
$queryBuilder = $queryBuilder->select( "VERSION()" );

$version = $queryBuilder->execute()->fetchColumn( 0 );
echo $version . PHP_EOL;
