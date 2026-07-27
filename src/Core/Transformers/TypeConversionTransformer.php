<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Step\Transformer;
use DateTimeImmutable;
use Throwable;

/**
 * Convert configured row fields to normalized scalar and date types.
 */
class TypeConversionTransformer extends Transformer
{
    protected array $fields = [];

    protected string $mode = "strict";

    protected bool $emptyAsNull = true;

    protected string $nullHandling = "preserve";

    protected array $availableOptions = [ "fields", "mode", "emptyAsNull", "nullHandling" ];

    /**
     * @throws SourceWatcherException
     */
    public function transform ( Row $row ) : void
    {
        $mode = strtolower( trim( $this->mode ) );
        $nullHandling = strtolower( trim( $this->nullHandling ) );

        if ( !in_array( $mode, [ "strict", "forgiving" ], true ) ) {
            throw new SourceWatcherException( 'Type Conversion mode must be either "strict" or "forgiving".' );
        }

        if ( !in_array( $nullHandling, [ "preserve", "error" ], true ) ) {
            throw new SourceWatcherException( 'Type Conversion nullHandling must be either "preserve" or "error".' );
        }

        if ( empty( $this->fields ) ) {
            throw new SourceWatcherException( "Type Conversion requires at least one field." );
        }

        foreach ( $this->fields as $field => $configuration ) {
            if ( !is_string( $field ) || trim( $field ) === "" ) {
                throw new SourceWatcherException( "Type Conversion field names must be non-empty strings." );
            }

            if ( !$row->offsetExists( $field ) ) {
                continue;
            }

            $type = $this->normalizeType( $field, $configuration );
            $value = $row->get( $field );

            if ( $value === "" && $this->emptyAsNull ) {
                $value = null;
            }

            if ( $value === null ) {
                if ( $nullHandling === "error" ) {
                    throw new SourceWatcherException(
                        sprintf( 'Type Conversion field "%s" cannot be null.', $field )
                    );
                }

                $row->set( $field, null );
                continue;
            }

            try {
                $row->set( $field, $this->convert( $field, $value, $type ) );
            } catch ( SourceWatcherException $exception ) {
                if ( $mode === "strict" ) {
                    throw $exception;
                }
            }
        }
    }

    /**
     * @throws SourceWatcherException
     */
    private function normalizeType ( string $field, mixed $configuration ) : string
    {
        $type = is_array( $configuration ) ? ( $configuration["type"] ?? null ) : $configuration;

        if ( !is_string( $type ) ) {
            throw new SourceWatcherException(
                sprintf( 'Type Conversion field "%s" requires a type.', $field )
            );
        }

        $type = strtolower( trim( $type ) );

        if ( !in_array( $type, [ "integer", "float", "string", "boolean", "date", "datetime" ], true ) ) {
            throw new SourceWatcherException(
                sprintf( 'Unsupported Type Conversion type "%s" for field "%s".', $type, $field )
            );
        }

        return $type;
    }

    /**
     * @throws SourceWatcherException
     */
    private function convert ( string $field, mixed $value, string $type ) : mixed
    {
        return match ( $type ) {
            "integer" => $this->toInteger( $field, $value ),
            "float" => $this->toFloat( $field, $value ),
            "string" => $this->toString( $field, $value ),
            "boolean" => $this->toBoolean( $field, $value ),
            "date" => $this->toDate( $field, $value, "Y-m-d" ),
            "datetime" => $this->toDate( $field, $value, "Y-m-d H:i:s" ),
        };
    }

    /**
     * @throws SourceWatcherException
     */
    private function toInteger ( string $field, mixed $value ) : int
    {
        if ( is_int( $value ) ) {
            return $value;
        }

        if ( is_float( $value ) && floor( $value ) === $value ) {
            return (int) $value;
        }

        if ( is_string( $value ) && preg_match( '/^[+-]?\d+$/', trim( $value ) ) === 1 ) {
            return (int) trim( $value );
        }

        throw $this->conversionException( $field, "integer", $value );
    }

    /**
     * @throws SourceWatcherException
     */
    private function toFloat ( string $field, mixed $value ) : float
    {
        if ( !is_numeric( $value ) ) {
            throw $this->conversionException( $field, "float", $value );
        }

        return (float) $value;
    }

    /**
     * @throws SourceWatcherException
     */
    private function toString ( string $field, mixed $value ) : string
    {
        if ( !is_scalar( $value ) ) {
            throw $this->conversionException( $field, "string", $value );
        }

        return (string) $value;
    }

    /**
     * @throws SourceWatcherException
     */
    private function toBoolean ( string $field, mixed $value ) : bool
    {
        if ( is_bool( $value ) ) {
            return $value;
        }

        if ( $value === 1 || $value === "1" ) {
            return true;
        }

        if ( $value === 0 || $value === "0" ) {
            return false;
        }

        if ( is_string( $value ) ) {
            $normalized = strtolower( trim( $value ) );

            if ( in_array( $normalized, [ "true", "yes", "on" ], true ) ) {
                return true;
            }

            if ( in_array( $normalized, [ "false", "no", "off" ], true ) ) {
                return false;
            }
        }

        throw $this->conversionException( $field, "boolean", $value );
    }

    /**
     * @throws SourceWatcherException
     */
    private function toDate ( string $field, mixed $value, string $format ) : string
    {
        if ( !is_scalar( $value ) ) {
            throw $this->conversionException( $field, $format === "Y-m-d" ? "date" : "datetime", $value );
        }

        try {
            return ( new DateTimeImmutable( (string) $value ) )->format( $format );
        } catch ( Throwable ) {
            throw $this->conversionException( $field, $format === "Y-m-d" ? "date" : "datetime", $value );
        }
    }

    private function conversionException ( string $field, string $type, mixed $value ) : SourceWatcherException
    {
        $displayValue = is_scalar( $value ) || $value === null
            ? var_export( $value, true )
            : get_debug_type( $value );

        return new SourceWatcherException(
            sprintf( 'Type Conversion could not convert field "%s" value %s to %s.', $field, $displayValue, $type )
        );
    }
}
