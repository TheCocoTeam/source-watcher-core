<?php

/**
 * Sample: CSV → FilterRows → SQLite.
 * Keeps records whose numeric id is greater than 6 and whose email belongs to
 * example.com, then loads the matching rows into SQLite.
 */
require_once __DIR__ . "/bootstrap.php";

use Coco\SourceWatcher\Core\Database\Connections\SqliteConnector;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use Coco\SourceWatcher\Core\Pipeline\SourceWatcher;

$sqlitePath = __DIR__ . "/data/sqlite/filtered-people-out.sqlite";
$dataDir = dirname( $sqlitePath );

if ( !is_dir( $dataDir ) ) {
    mkdir( $dataDir, 0755, true );
}

$pdo = new PDO( "sqlite:" . $sqlitePath );
$pdo->exec( "CREATE TABLE IF NOT EXISTS filtered_people (id INTEGER, name TEXT, email TEXT)" );

$connector = new SqliteConnector();
$connector->setPath( $sqlitePath );
$connector->setTableName( "filtered_people" );

$sourceWatcher = new SourceWatcher();

try {
    $sourceWatcher
        ->extract( "Csv", new FileInput( __DIR__ . "/data/csv/csv1.csv" ) )
        ->transform( "FilterRows", [
            "match" => "all",
            "conditions" => [
                [ "field" => "id", "operator" => "greaterThan", "value" => 6 ],
                [ "field" => "email", "operator" => "regex", "value" => "/@example\\.com$/" ],
            ],
        ] )
        ->load( "Database", new DatabaseOutput( $connector ) )
        ->run();
} catch ( SourceWatcherException $exception ) {
    echo sprintf( "Something unexpected went wrong: %s", $exception->getMessage() );
}
