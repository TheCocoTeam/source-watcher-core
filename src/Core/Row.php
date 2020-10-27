<?php

namespace Coco\SourceWatcher\Core;

use ArrayAccess;

/**
 * Class Row
 * @package Coco\SourceWatcher\Core
 */
class Row implements ArrayAccess, ArrayListAccess
{
    private array $attributes;

    public function __construct ( array $attributes )
    {
        $this->attributes = $attributes;
    }

    public function getAttributes () : array
    {
        return $this->attributes;
    }

    public function setAttributes ( array $attributes ) : void
    {
        $this->attributes = $attributes;
    }

    public function offsetExists ( $offset )
    {
        return array_key_exists( $offset, $this->attributes );
    }

    public function offsetGet ( $offset )
    {
        return $this->attributes[$offset];
    }

    public function offsetSet ( $offset, $value )
    {
        $this->attributes[$offset] = $value;
    }

    public function offsetUnset ( $offset )
    {
        unset( $this->attributes[$offset] );
    }

    public function get ( string $key )
    {
        return $this->attributes[$key] ?? null;
    }

    public function set ( string $key, $value ) : void
    {
        $this->attributes[$key] = $value;
    }

    public function remove ( string $key ) : void
    {
        unset( $this->attributes[$key] );
    }

    public function __get ( $key )
    {
        return $this->attributes[$key];
    }

    public function __set ( $key, $value )
    {
        $this->attributes[$key] = $value;
    }
}
