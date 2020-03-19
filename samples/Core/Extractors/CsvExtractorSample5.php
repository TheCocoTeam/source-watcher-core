<?php

include __DIR__ . "/../../includes/cli-execution-only.php";

require_once __DIR__ . "/../../../vendor/autoload.php";

use Coco\SourceWatcher\Core\Extractors\CsvExtractor;

$csvExtractor = new CsvExtractor();
$csvExtractor->setColumns( array() );
$csvExtractor->setDelimiter( "," );
$csvExtractor->setEnclosure( "" );

$csvExtractor->setInput( __DIR__ . "/../../data/csv/virology/coronavirusdataset/patient.csv" );
$patientsResult = $csvExtractor->extract();

$csvExtractor->setInput( __DIR__ . "/../../data/csv/virology/coronavirusdataset/route.csv" );
$routeResult = $csvExtractor->extract();

foreach ( $patientsResult as $currentPatientIndex => $currentPatientRow ) {
    $patientId = $currentPatientRow->get( "id" );

    $currentPatientRoute = array();

    foreach ( $routeResult as $currentRouteIndex => $currentRouteRow ) {
        $currentRouteRowPatientId = $currentRouteRow->get( "id" );

        if ( $patientId == $currentRouteRowPatientId ) {
            array_push( $currentPatientRoute, $currentRouteRow );
        }
    }

    $currentPatientRow->set( "route", $currentPatientRoute );
}

print_r( $patientsResult );
