<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Transformer;
use Coco\SourceWatcher\Core\Row;

class RenameColumnsTransformer extends Transformer
{
    /**
     * @var array
     */
    protected array $columns = [];

    /**
     * @var array
     */
    protected array $availableOptions = [ "columns" ];

    /**
     * @param Row $row
     */
    public function transform ( Row $row )
    {
        foreach ( $this->columns as $oldColumnName => $newColumnName ) {
            $value = $row->get( $oldColumnName );
            $row->remove( $oldColumnName );
            $row->set( $newColumnName, $value );
        }
    }
}
