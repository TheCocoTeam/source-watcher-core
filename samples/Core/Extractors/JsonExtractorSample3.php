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
$jsonExtractor->setColumns( array( "color" => "colors.*.color" ) );

$jsonExtractor->setInput( __DIR__ . "/../../data/json/colors.json" );
$booksResult = $jsonExtractor->extract();

foreach ( $booksResult as $index => $value ) {
    echo "index: " . $index . PHP_EOL;
    echo "value: " . print_r( $value, true ) . PHP_EOL;

    $color = $value->color;

    $jsonExtractor->setColumns(
        array(
            "category" => "colors.[?(@.color=$color)].category",
            "type" => "colors.[?(@.color=$color)].type",
            "code" => "colors.[?(@.color=$color)].code"
        )
    );
    $colorResult = $jsonExtractor->extract();
    print_r( $colorResult );
}
