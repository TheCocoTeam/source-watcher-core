<?php

include __DIR__ . "/../../includes/cli-execution-only.php";

require_once __DIR__ . "/../../../vendor/autoload.php";

use Coco\SourceWatcher\Core\Extractors\JsonExtractor;

/**
 * Expression syntax
 *
 *  Symbol            Description
 *
 *  $                   The root object/element (not strictly necessary)
 *  @                   The current object/element
 *  . or []             Child operator
 *  ..                  Recursive descent
 *  *                   Wildcard. All child elements regardless their index.
 *  [,]                 Array indices as a set
 *  [start:end:step]    Array slice operator borrowed from ES4/Python.
 *  ?()                 Filters a result set by a script expression
 *  ()                  Uses the result of a script expression as the index
 */

$jsonExtractor = new JsonExtractor();
$jsonExtractor->setColumns( array( "cca2" => "$.*.cca2", "name" => "$.*.name.official" ) );

$jsonExtractor->setInput( __DIR__ . "/../../data/json/countries.json" );
$countriesResult = $jsonExtractor->extract();
print_r( $countriesResult );
