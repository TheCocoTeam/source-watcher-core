<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Step\Transformer;
use DateTimeImmutable;
use Throwable;

/**
 * Validate row fields and either fail or annotate the row with errors.
 */
class ValidateRowsTransformer extends Transformer
{
    protected array $rules = [];

    protected string $mode = "fail";

    protected string $errorField = "_validation_errors";

    protected array $availableOptions = [ "rules", "mode", "errorField" ];

    /**
     * @throws SourceWatcherException
     */
    public function transform ( Row $row ) : void
    {
        $mode = strtolower( trim( $this->mode ) );
        $errorField = trim( $this->errorField );

        if ( !in_array( $mode, [ "fail", "annotate" ], true ) ) {
            throw new SourceWatcherException( 'Validate Rows mode must be either "fail" or "annotate".' );
        }

        if ( $errorField === "" ) {
            throw new SourceWatcherException( "Validate Rows requires a non-empty error field." );
        }

        if ( empty( $this->rules ) ) {
            throw new SourceWatcherException( "Validate Rows requires at least one field rule." );
        }

        $errors = [];

        foreach ( $this->rules as $field => $rules ) {
            if ( !is_string( $field ) || trim( $field ) === "" || !is_array( $rules ) ) {
                throw new SourceWatcherException( "Validate Rows rules must map field names to rule objects." );
            }

            $fieldErrors = $this->validateField( $row, $field, $rules );

            foreach ( $fieldErrors as $error ) {
                $errors[] = $error;
            }
        }

        if ( $mode === "annotate" ) {
            $row->set( $errorField, $errors );
            return;
        }

        if ( $errors !== [] ) {
            throw new SourceWatcherException( "Row validation failed: " . implode( " ", $errors ) );
        }
    }

    /**
     * @throws SourceWatcherException
     */
    private function validateField ( Row $row, string $field, array $rules ) : array
    {
        $errors = [];
        $exists = $row->offsetExists( $field );
        $value = $row->get( $field );
        $empty = !$exists || $value === null || $value === "";
        $required = (bool) ( $rules["required"] ?? false );

        if ( $required && $empty ) {
            return [ sprintf( "%s is required.", $field ) ];
        }

        if ( $empty ) {
            return [];
        }

        if ( isset( $rules["type"] ) ) {
            if ( !is_string( $rules["type"] ) ) {
                throw new SourceWatcherException( sprintf( 'Validate Rows type for "%s" must be a string.', $field ) );
            }

            $type = strtolower( trim( $rules["type"] ) );

            if ( !$this->matchesType( $value, $type ) ) {
                $errors[] = sprintf( "%s must be of type %s.", $field, $type );
            }
        }

        if ( isset( $rules["format"] ) ) {
            if ( !is_string( $rules["format"] ) ) {
                throw new SourceWatcherException( sprintf( 'Validate Rows format for "%s" must be a string.', $field ) );
            }

            $format = strtolower( trim( $rules["format"] ) );

            if ( !$this->matchesFormat( $value, $format ) ) {
                $errors[] = sprintf( "%s must be a valid %s.", $field, $format );
            }
        }

        if ( isset( $rules["regex"] ) ) {
            if ( !is_string( $rules["regex"] ) || $rules["regex"] === "" ) {
                throw new SourceWatcherException( sprintf( 'Validate Rows regex for "%s" must be a pattern.', $field ) );
            }

            $matches = @preg_match( $rules["regex"], (string) $value );

            if ( $matches === false ) {
                throw new SourceWatcherException( sprintf( 'Validate Rows regex for "%s" is invalid.', $field ) );
            }

            if ( $matches !== 1 ) {
                $errors[] = sprintf( "%s does not match the required pattern.", $field );
            }
        }

        if ( isset( $rules["allowed"] ) ) {
            if ( !is_array( $rules["allowed"] ) ) {
                throw new SourceWatcherException( sprintf( 'Validate Rows allowed values for "%s" must be an array.', $field ) );
            }

            if ( !in_array( $value, $rules["allowed"], true ) ) {
                $errors[] = sprintf( "%s is not an allowed value.", $field );
            }
        }

        $this->validateNumericBounds( $field, $value, $rules, $errors );
        $this->validateLengthBounds( $field, $value, $rules, $errors );

        return $errors;
    }

    /**
     * @throws SourceWatcherException
     */
    private function matchesType ( mixed $value, string $type ) : bool
    {
        return match ( $type ) {
            "integer" => is_int( $value ),
            "float" => is_float( $value ),
            "numeric" => is_int( $value ) || is_float( $value ),
            "string" => is_string( $value ),
            "boolean" => is_bool( $value ),
            "array" => is_array( $value ),
            "date", "datetime" => $this->isDate( $value ),
            default => throw new SourceWatcherException( sprintf( 'Unsupported Validate Rows type "%s".', $type ) ),
        };
    }

    /**
     * @throws SourceWatcherException
     */
    private function matchesFormat ( mixed $value, string $format ) : bool
    {
        return match ( $format ) {
            "email" => is_string( $value ) && filter_var( $value, FILTER_VALIDATE_EMAIL ) !== false,
            "url" => is_string( $value ) && filter_var( $value, FILTER_VALIDATE_URL ) !== false,
            "uuid" => is_string( $value ) && preg_match(
                '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
                $value
            ) === 1,
            default => throw new SourceWatcherException( sprintf( 'Unsupported Validate Rows format "%s".', $format ) ),
        };
    }

    private function isDate ( mixed $value ) : bool
    {
        if ( !is_string( $value ) || trim( $value ) === "" ) {
            return false;
        }

        try {
            new DateTimeImmutable( $value );
            return true;
        } catch ( Throwable ) {
            return false;
        }
    }

    /**
     * @throws SourceWatcherException
     */
    private function validateNumericBounds ( string $field, mixed $value, array $rules, array &$errors ) : void
    {
        foreach ( [ "min", "max" ] as $bound ) {
            if ( !array_key_exists( $bound, $rules ) ) {
                continue;
            }

            if ( is_numeric( $value ) && is_numeric( $rules[$bound] ) ) {
                $actual = (float) $value;
                $expected = (float) $rules[$bound];
            } elseif ( $this->isDate( $value ) && $this->isDate( $rules[$bound] ) ) {
                $actual = ( new DateTimeImmutable( (string) $value ) )->getTimestamp();
                $expected = ( new DateTimeImmutable( (string) $rules[$bound] ) )->getTimestamp();
            } else {
                throw new SourceWatcherException(
                    sprintf( 'Validate Rows %s rule for "%s" requires comparable numeric or date values.', $bound, $field )
                );
            }

            if ( $bound === "min" && $actual < $expected ) {
                $errors[] = sprintf( "%s must be at least %s.", $field, $rules[$bound] );
            }

            if ( $bound === "max" && $actual > $expected ) {
                $errors[] = sprintf( "%s must be at most %s.", $field, $rules[$bound] );
            }
        }
    }

    /**
     * @throws SourceWatcherException
     */
    private function validateLengthBounds ( string $field, mixed $value, array $rules, array &$errors ) : void
    {
        if ( !is_string( $value ) && !is_array( $value ) ) {
            if ( isset( $rules["minLength"] ) || isset( $rules["maxLength"] ) ) {
                throw new SourceWatcherException(
                    sprintf( 'Validate Rows length rules for "%s" require a string or array.', $field )
                );
            }
            return;
        }

        $length = is_string( $value ) ? mb_strlen( $value ) : count( $value );

        if ( isset( $rules["minLength"] ) && $length < (int) $rules["minLength"] ) {
            $errors[] = sprintf( "%s must contain at least %d character(s) or item(s).", $field, $rules["minLength"] );
        }

        if ( isset( $rules["maxLength"] ) && $length > (int) $rules["maxLength"] ) {
            $errors[] = sprintf( "%s must contain at most %d character(s) or item(s).", $field, $rules["maxLength"] );
        }
    }
}
