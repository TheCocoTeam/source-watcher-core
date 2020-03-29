<?php

include __DIR__ . "/../../../../includes/cli-execution-only.php";

require_once __DIR__ . "/../../../../../vendor/autoload.php";

use Coco\SourceWatcher\Vendors\Virology\COVID_19\Florida\Covid19FloridaApiReader;

$covid19FloridaApiReader = new Covid19FloridaApiReader();

$totalPositiveCases = $covid19FloridaApiReader->getTotalPositiveCases();
echo "totalPositiveCases" . " = " . $totalPositiveCases . PHP_EOL;

$positiveResidents = $covid19FloridaApiReader->getPositiveResidents();
echo "positiveResidents" . " = " . $positiveResidents . PHP_EOL;

$hospitalized = $covid19FloridaApiReader->getHospitalized();
echo "hospitalized" . " = " . $hospitalized . PHP_EOL;

$deaths = $covid19FloridaApiReader->getDeaths();
echo "deaths" . " = " . $deaths . PHP_EOL;

$totalTests = $covid19FloridaApiReader->getTotalTests();
echo "totalTests" . " = " . $totalTests . PHP_EOL;

$totalNegativeResidents = $covid19FloridaApiReader->getTotalNegativeResidents();
echo "totalNegativeResidents" . " = " . $totalNegativeResidents . PHP_EOL;
