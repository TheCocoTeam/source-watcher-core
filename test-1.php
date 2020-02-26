<?php

require_once __DIR__ . '/vendor/autoload.php';

use Coco\SourceWatcher\Source\WebPageSource;
use Coco\SourceWatcher\Vendors\StackOverflow\StackOverflowWebPageHandler;

$url = "https://stackoverflow.com/jobs?q=PHP&l=Florida+USA&d=100&u=Miles";
$webPageSource = new WebPageSource ( $url ) ;
$html = $webPageSource->getHandler()->getHtml();
//echo $html;

$jobsUrl = "https://stackoverflow.com/jobs?q=PHP&l=Florida+USA&d=100&u=Miles";
$handler = new StackOverflowWebPageHandler( $jobsUrl );
$handler->read();
$results = $handler->getResults();
print_r($results);
