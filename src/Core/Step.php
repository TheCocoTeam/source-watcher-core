<?php

namespace Coco\SourceWatcher\Core;

use Coco\SourceWatcher\Utils\TextUtils;

/**
 * Class Step
 * @package Coco\SourceWatcher\Core
 */
class Step
{
    /**
     * @var array
     */
    protected array $availableOptions = array();

    /**
     * @param array $options
     */
    public function options ( array $options )
    {
        foreach ( $options as $optionName => $optionValue ) {
            $camelCaseOptionName = $this->textToCamelCase( $optionName );

            if ( in_array( $camelCaseOptionName, $this->availableOptions ) ) {
                $this->$camelCaseOptionName = $optionValue;
            }
        }
    }

    /**
     * @param string $word
     * @return string
     */
    protected function textToCamelCase ( string $word ) : string
    {
        $textUtils = new TextUtils();
        return $textUtils->textToCamelCase( $word );
    }
}
