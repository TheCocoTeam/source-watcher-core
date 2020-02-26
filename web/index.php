<?php

if ( php_sapi_name() !== "cli" ) {
    echo "This app only runs from the console :(" . PHP_EOL;
    exit();
}

require_once __DIR__ . '/../vendor/autoload.php';

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
    $stackOverflowSlackCommunicator = new StackOverflowSlackCommunicator( ( new StackOverflowWebPageSource( $jobsUrl ) )->getResults() );
    $stackOverflowSlackCommunicator->setWebHookUrl( $slackWebHookUrl );
    $stackOverflowSlackCommunicator->send();
} else {
    echo "check the jobs url and slack web hook url parameters";
}
