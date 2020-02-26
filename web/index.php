<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Coco\SourceWatcher\Vendors\StackOverflow\StackOverflowWebPageHandler;
use Coco\SourceWatcher\Source\WebPageSource;

$url = "https://stackoverflow.com/jobs?q=PHP&l=Florida+USA&d=100&u=Miles";
$webPageSource = new WebPageSource ( $url ) ;
$html = $webPageSource->getHandler()->getHtml();
//echo $html;

$jobsUrl = "https://stackoverflow.com/jobs?q=PHP&l=Florida+USA&d=100&u=Miles";
$handler = new StackOverflowWebPageHandler( $jobsUrl );
$handler->read();
$results = $handler->getResults(); // array
//print_r($results);

$webHookURL = getenv('WEB_HOOK_URL');
//echo "webHookURL: " . $webHookURL . PHP_EOL;

foreach ( $results as $currentResult ) {
    $currentJobURL = $currentResult->removeUrlParameters();
    echo "currentJobURL: " . $currentJobURL . PHP_EOL;

    $data = array( "text" => $currentJobURL );
    $data_string = json_encode( $data );

    $ch = curl_init( $webHookURL );
    curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen( $data_string ) ) );

    sleep( 2 );

    $result = curl_exec( $ch );
}
