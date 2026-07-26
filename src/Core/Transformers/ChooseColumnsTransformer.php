<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Step\Transformer;

/**
 * Keep or remove configured columns from each row.
 */
class ChooseColumnsTransformer extends Transformer
{
    protected string $mode = "include";

    protected array $columns = [];

    protected array $availableOptions = [ "mode", "columns" ];

    /**
     * @throws SourceWatcherException
     */
    public function transform ( Row $row ) : void
    {
        $mode = strtolower( trim( $this->mode ) );
        $columns = $this->normalizeColumns();

        if ( !in_array( $mode, [ "include", "exclude" ], true ) ) {
            throw new SourceWatcherException( 'Choose Columns mode must be either "include" or "exclude".' );
        }

        if ( $mode === "include" ) {
            $attributes = [];

            foreach ( $columns as $column ) {
                if ( $row->offsetExists( $column ) ) {
                    $attributes[$column] = $row->get( $column );
                }
            }

            $row->setAttributes( $attributes );

            return;
        }

        foreach ( $columns as $column ) {
            $row->remove( $column );
        }
    }

    /**
     * @throws SourceWatcherException
     */
    private function normalizeColumns () : array
    {
        $columns = [];

        foreach ( $this->columns as $column ) {
            if ( !is_string( $column ) || trim( $column ) === "" ) {
                throw new SourceWatcherException( "Choose Columns columns must contain non-empty field names." );
            }

            $column = trim( $column );

            if ( !in_array( $column, $columns, true ) ) {
                $columns[] = $column;
            }
        }

        if ( empty( $columns ) ) {
            throw new SourceWatcherException( "Choose Columns requires at least one column." );
        }

        return $columns;
    }
}
