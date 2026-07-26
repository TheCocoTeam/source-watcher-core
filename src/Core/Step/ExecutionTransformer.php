<?php

namespace Coco\SourceWatcher\Core\Step;

use Coco\SourceWatcher\Core\Data\Row;

/**
 * A transformer that operates on the complete result set instead of one row
 * at a time.
 */
abstract class ExecutionTransformer extends Transformer
{
    final public function transform ( Row $row ) : bool
    {
        return true;
    }

    abstract public function transformRows ( array $rows ) : array;
}
