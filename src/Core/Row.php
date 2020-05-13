<?php

namespace Coco\SourceWatcher\Core;

use ArrayAccess;

/**
 * Class Row
 * @package Coco\SourceWatcher\Core
 */
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

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get ( string $key )
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set ( string $key, $value ) : void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function remove ( string $key ) : void
    {
        unset( $this->attributes[$key] );
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get ( $key )
    {
        return $this->attributes[$key];
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set ( $key, $value )
    {
        $this->attributes[$key] = $value;
    }
}
