<?php

if ( php_sapi_name() !== "cli" ) {
    echo "This app only runs from the console :(" . PHP_EOL;
    exit();
}

ini_set( "max_execution_time", 0 );

require_once __DIR__ . "/../../../vendor/autoload.php";

use Coco\SourceWatcher\Vendors\StackOverflow\StackOverflowSlackCommunicator;
use Coco\SourceWatcher\Vendors\StackOverflow\StackOverflowWebPageSource;

if ( $argc < 3 ) {
    echo "You need to pass the env var names for the jobs url and slack web hook url" . PHP_EOL;
    exit();
}

$envVarNameForJobsUrl = $argv[1];
$jobsUrl = getenv( $envVarNameForJobsUrl );

$envVarNameForSlackWebHookUrl = $argv[2];
$slackWebHookUrl = getenv( $envVarNameForSlackWebHookUrl );

if ( $jobsUrl != null && $jobsUrl != "" && $slackWebHookUrl != null && $slackWebHookUrl != "" ) {
    $source = new StackOverflowWebPageSource( $jobsUrl );
    $results = $source->getResults();

    try {
        $date_utc = new DateTime( "now", new DateTimeZone( "UTC" ) );
        echo $date_utc->format( DateTime::RFC850 ) . ": Found " . sizeof( $results ) . " results from " . $jobsUrl . PHP_EOL;

        $communicator = new StackOverflowSlackCommunicator( $results );
        $communicator->setWebHookUrl( $slackWebHookUrl );
        $communicator->send();
    } catch ( Exception $e ) {

    }
} else {
    echo "check the jobs url and slack web hook url parameters" . PHP_EOL;
}
