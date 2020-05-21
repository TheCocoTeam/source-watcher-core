<?php

include __DIR__ . "/../../../../includes/cli-execution-only.php";

require_once __DIR__ . "/../../../../../vendor/autoload.php";

use Coco\SourceWatcher\Vendors\Virology\COVID_19\Florida\Covid19FloridaApiReader;

$covid19FloridaApiReader = new Covid19FloridaApiReader();
$results = $covid19FloridaApiReader->getResults();

foreach ( $results as $key => $info ) {
    echo sprintf( "%s = %s", $info["description"], $info["value"] ) . PHP_EOL;
}
