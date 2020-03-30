<?php

include __DIR__ . "/../../includes/cli-execution-only.php";

require_once __DIR__ . "/../../../vendor/autoload.php";

use Coco\SourceWatcher\Core\Extractors\JsonExtractor;

$jsonExtractor = new JsonExtractor();
$jsonExtractor->setColumns( array( 'isbn' => '$..isbn', 'title' => '$..title' ) );

$jsonExtractor->setInput( __DIR__ . "/../../data/json/books.json" );
$booksResult = $jsonExtractor->extract();
// @todo: The last 2 rows are missing the isbn. Possible bug?
print_r($booksResult);
