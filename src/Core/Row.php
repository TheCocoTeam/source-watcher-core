<?php

namespace Coco\SourceWatcher\Core;

use ArrayAccess;

class Row implements ArrayAccess, ArrayListAccess
{
    /**
     * @var array
     */
    private array $attributes;

    /**
     * Row constructor.
     * @param array $attributes
     */
    public function __construct ( array $attributes )
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getAttributes () : array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes ( array $attributes ) : void
    {
        $this->attributes = $attributes;
    }

    public function transform ()
    {

    }

    /**
     * @inheritDoc
     */
    public function offsetExists ( $offset )
    {
        return array_key_exists( $offset, $this->attributes );
    }

    /**
     * @inheritDoc
     */
    public function offsetGet ( $offset )
    {
        return $this->attributes[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet ( $offset, $value )
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
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
