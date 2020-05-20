<?php

$matrix = [ [ 1, 2, 3 ], [ 4, 5, 6 ], [ 7, 8, 9 ] ];
//print_r( $matrix ) . PHP_EOL;

for ( $row = 0; $row < sizeof( $matrix ); $row++ ) {
    for ( $column = 0; $column < sizeof( $matrix[0] ); $column++ ) {
        echo $matrix[$row][$column] . " ";
    }
    echo PHP_EOL;
}

function findNeighbours ( array $matrix, int $row, int $column ) : array
{
    $neighbours = [];

    for ( $currentRow = $row - 1; $currentRow <= $row + 1; $currentRow++ ) {
        for ( $currentColumn = $column - 1; $currentColumn <= $column + 1; $currentColumn++ ) {
            if ( isElementInRange( $currentRow, $currentColumn, $matrix ) ) {
                if ( $currentRow != $row || $currentColumn != $column ) {
                    $neighbours[] = [ "row" => $currentRow, "column" => $currentColumn ];
                }
            }
        }
    }

    return $neighbours;
}

function isElementInRange ( $row, $column, $matrix ) : bool
{
    $inRange = true;

    if ( $row < 0 || $column < 0 ) {
        $inRange = false;
    }

    if ( $row > sizeof( $matrix ) - 1 ) {
        $inRange = false;
    }

    if ( $column > sizeof( $matrix[0] ) - 1 ) {
        $inRange = false;
    }

    return $inRange;
}

for ( $row = 0; $row < sizeof( $matrix ); $row++ ) {
    for ( $column = 0; $column < sizeof( $matrix[0] ); $column++ ) {
        $neighbours = findNeighbours( $matrix, $row, $column );
        echo "neighbours for row $row, column $column are:" . PHP_EOL;
        print_r( $neighbours ) . PHP_EOL;
    }
}
