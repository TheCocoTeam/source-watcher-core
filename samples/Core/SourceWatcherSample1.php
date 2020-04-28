<?php

include __DIR__ . "/../includes/cli-execution-only.php";

require_once __DIR__ . "/../../vendor/autoload.php";

use Coco\SourceWatcher\Core\SourceWatcher;

$sourceWatcher = new SourceWatcher();
$sourceWatcher->extract( "csv", "/host/shared/source-watcher/samples/data/csv/csv1.csv" );
