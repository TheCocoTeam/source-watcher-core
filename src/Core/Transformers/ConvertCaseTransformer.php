<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\Transformer;

/**
 * Class ConvertCaseTransformer
 * @package Coco\SourceWatcher\Core\Transformers
 */
class ConvertCaseTransformer extends Transformer
{
    /**
     *
     */
    const CONVERT_CASE_MODE_UPPER = MB_CASE_UPPER;

    /**
     *
     */
    const CONVERT_CASE_MODE_LOWER = MB_CASE_LOWER;

    /**
     *
     */
    const CONVERT_CASE_MODE_TITLE = MB_CASE_TITLE;


    /**
     * @var array
     */
    protected array $columns = [];

    /**
     * @var string
     */
    protected string $encoding = "UTF-8";

    /**
     * @var int
     */
    protected int $mode = self::CONVERT_CASE_MODE_LOWER;

    /**
     * @var array|string[]
     */
    protected array $availableOptions = [ "columns", "encoding", "mode" ];

    /**
     * @param Row $row
     * @return mixed|void
     */
    public function transform ( Row $row )
    {
        foreach ( $this->columns as $oldColumnName ) {
            $value = $row->get( $oldColumnName );

            $row->remove( $oldColumnName );

            $newColumnName = mb_convert_case( $oldColumnName, $this->mode, $this->encoding );

            $row->set( $newColumnName, $value );
        }
    }
}
