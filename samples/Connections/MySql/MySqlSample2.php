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

$qbResult = $queryBuilder->insert( "people" )->values( array( "name" => "'Jane Doe'" ) );
echo $qbResult . PHP_EOL;
$qbResult->execute();

$qbResult = $queryBuilder->insert( "people" )->values( array( "name" => "'John Doe'" ) );
echo $qbResult . PHP_EOL;
$qbResult->execute();

$connection->insert( "people", array( "name" => "Jane Doe" ) );
$connection->insert( "people", array( "name" => "John Doe" ) );
