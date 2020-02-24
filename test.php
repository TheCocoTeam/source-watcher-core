<?php

require_once __DIR__ . '/vendor/autoload.php';

use Coco\SourceWatcher\Source\StackOverflow\StackOverflowPHPJobsWebPageSource;
use Coco\SourceWatcher\Source\WebPageSource;

$url = "https://stackoverflow.com/jobs?q=PHP&l=Florida+USA&d=100&u=Miles";
$webPageSource = new WebPageSource ( $url ) ;
$html = $webPageSource->getHandler()->getHtml();
//echo $html;

$stackOverflowJobs = new StackOverflowPHPJobsWebPageSource();
$results = $stackOverflowJobs->getResults();
print_r( $results );
