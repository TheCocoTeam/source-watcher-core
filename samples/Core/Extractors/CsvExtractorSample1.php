<?php

include __DIR__ . "/../../includes/cli-execution-only.php";

require_once __DIR__ . "/../../../vendor/autoload.php";

use Coco\SourceWatcher\Core\Extractors\CsvExtractor;

$csvExtractor = new CsvExtractor();
$csvExtractor->setInput( __DIR__ . "/../../data/csv/csv1.csv" );
$csvExtractor->setColumns( array() );
$csvExtractor->setDelimiter( "," );
$csvExtractor->setEnclosure( "" );
$result = $csvExtractor->extract();
print_r( $result );
