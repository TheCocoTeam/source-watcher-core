# Source Watcher Core [![codecov](https://codecov.io/gh/TheCocoTeam/source-watcher-core/branch/master/graph/badge.svg)](https://codecov.io/gh/TheCocoTeam/source-watcher-core) [![travis-ci](https://travis-ci.com/TheCocoTeam/source-watcher-core.svg?branch=master)](https://travis-ci.com/github/TheCocoTeam/source-watcher-core) [![scrutinizer-ci](https://scrutinizer-ci.com/g/TheCocoTeam/source-watcher-core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/TheCocoTeam/source-watcher-core/?branch=master)

PHP project which allows extracting, transform and load data from different sources including databases, files and services.

## ETL example

Assume that you have some information in a CSV file, and you want to import the content of the file to a MySQL database.

The CSV file is a standard comma-separated value file with some headers: *id*, *name* and *email*.

You have a table in your MySQL database with some fields: *id*, *name* and *email_address*.

First you would need to extract the information from your CSV file, only the fields that you want to insert into your MySQL database.

After you have extracted the information, you want to rename the CSV header email to match the database field email_address.

Finally, you want to save the information in your database table.

```php
<?php
use Coco\SourceWatcher\Core\Database\Connections\MySqlConnector;
use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use Coco\SourceWatcher\Core\SourceWatcher;
use Coco\SourceWatcher\Core\SourceWatcherException;

$mysqlConnector = new MySqlConnector();
$mysqlConnector->setUser( "user" );
$mysqlConnector->setPassword( "password" );
$mysqlConnector->setHost( "host" );
$mysqlConnector->setPort( 3306 );
$mysqlConnector->setDbName( "tests" );
$mysqlConnector->setTableName( "people" );

$sourceWatcher = new SourceWatcher();

try {
    $sourceWatcher
        ->extract( "Csv", new FileInput( __DIR__ . "/../data/csv/csv1.csv" ), [ "columns" => array( "name", "email" ) ] )
        ->transform( "RenameColumns", [ "columns" => array( "email" => "email_address" ) ] )
        ->load( "Database", new DatabaseOutput( $mysqlConnector ) )
        ->run();
} catch ( SourceWatcherException $exception ) {
    echo sprintf( "Something unexpected went wrong: %s", $exception->getMessage() );
}
```

## Docker container

Build the Docker image:

```shell
sudo docker-compose -f docker-compose.yml build
```

Alternatively, you can simply build the Docker image with:

```shell
sudo docker-compose build
```

An optional step, list the Docker images to confirm it's there:

```shell
sudo docker image ls
```

Start your Docker container:

```shell
sudo docker-compose up
```

You should be able to access http://localhost:8080/
