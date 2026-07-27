<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use DateTimeImmutable;
use Throwable;

/**
 * Evaluate the constrained expression language used by Derive Field.
 */
class ExpressionEvaluator
{
    private array $tokens = [];

    private int $position = 0;

    private Row $row;

    private bool $validationOnly = false;

    /**
     * Validate expression syntax and supported functions without row data.
     *
     * @throws SourceWatcherException
     */
    public function validate ( string $expression ) : void
    {
        $this->validationOnly = true;

        try {
            $this->evaluate( $expression, new Row( [] ) );
        } finally {
            $this->validationOnly = false;
        }
    }

    /**
     * @throws SourceWatcherException
     */
    public function evaluate ( string $expression, Row $row ) : mixed
    {
        $this->tokens = $this->tokenize( $expression );
        $this->position = 0;
        $this->row = $row;

        if ( $this->tokens === [] ) {
            throw new SourceWatcherException( "Derive Field expression cannot be empty." );
        }

        $result = $this->parseExpression();

        if ( !$this->isAtEnd() ) {
            throw new SourceWatcherException(
                sprintf( 'Unexpected token "%s" in Derive Field expression.', $this->current()["value"] )
            );
        }

        return $result;
    }

    /**
     * @throws SourceWatcherException
     */
    private function tokenize ( string $expression ) : array
    {
        $tokens = [];
        $length = strlen( $expression );
        $index = 0;

        while ( $index < $length ) {
            $character = $expression[$index];

            if ( ctype_space( $character ) ) {
                $index++;
                continue;
            }

            if ( str_contains( "+-*/(),", $character ) ) {
                $tokens[] = [ "type" => $character, "value" => $character ];
                $index++;
                continue;
            }

            if ( $character === "'" || $character === '"' ) {
                [ $value, $index ] = $this->readString( $expression, $index, $character );
                $tokens[] = [ "type" => "string", "value" => $value ];
                continue;
            }

            if ( ctype_digit( $character ) || ( $character === "." && $index + 1 < $length && ctype_digit( $expression[$index + 1] ) ) ) {
                $start = $index;
                $dots = 0;

                while ( $index < $length && ( ctype_digit( $expression[$index] ) || $expression[$index] === "." ) ) {
                    if ( $expression[$index] === "." ) {
                        $dots++;
                    }
                    $index++;
                }

                if ( $dots > 1 ) {
                    throw new SourceWatcherException( "Invalid numeric literal in Derive Field expression." );
                }

                $value = substr( $expression, $start, $index - $start );
                $tokens[] = [
                    "type" => "number",
                    "value" => str_contains( $value, "." ) ? (float) $value : (int) $value,
                ];
                continue;
            }

            if ( ctype_alpha( $character ) || $character === "_" ) {
                $start = $index;

                while (
                    $index < $length &&
                    ( ctype_alnum( $expression[$index] ) || $expression[$index] === "_" || $expression[$index] === "." )
                ) {
                    $index++;
                }

                $tokens[] = [
                    "type" => "identifier",
                    "value" => substr( $expression, $start, $index - $start ),
                ];
                continue;
            }

            throw new SourceWatcherException(
                sprintf( 'Invalid character "%s" in Derive Field expression.', $character )
            );
        }

        return $tokens;
    }

    /**
     * @throws SourceWatcherException
     */
    private function readString ( string $expression, int $index, string $quote ) : array
    {
        $index++;
        $length = strlen( $expression );
        $value = "";

        while ( $index < $length ) {
            $character = $expression[$index];

            if ( $character === $quote ) {
                return [ $value, $index + 1 ];
            }

            if ( $character === "\\" ) {
                $index++;

                if ( $index >= $length ) {
                    break;
                }

                $escaped = $expression[$index];
                $value .= match ( $escaped ) {
                    "n" => "\n",
                    "r" => "\r",
                    "t" => "\t",
                    "\\", "'", '"' => $escaped,
                    default => "\\" . $escaped,
                };
                $index++;
                continue;
            }

            $value .= $character;
            $index++;
        }

        throw new SourceWatcherException( "Unterminated string literal in Derive Field expression." );
    }

    /**
     * @throws SourceWatcherException
     */
    private function parseExpression () : mixed
    {
        return $this->parseAddition();
    }

    /**
     * @throws SourceWatcherException
     */
    private function parseAddition () : mixed
    {
        $value = $this->parseMultiplication();

        while ( $this->match( "+" ) || $this->match( "-" ) ) {
            $operator = $this->previous()["type"];
            $right = $this->parseMultiplication();
            $value = $this->arithmetic( $value, $right, $operator );
        }

        return $value;
    }

    /**
     * @throws SourceWatcherException
     */
    private function parseMultiplication () : mixed
    {
        $value = $this->parseUnary();

        while ( $this->match( "*" ) || $this->match( "/" ) ) {
            $operator = $this->previous()["type"];
            $right = $this->parseUnary();
            $value = $this->arithmetic( $value, $right, $operator );
        }

        return $value;
    }

    /**
     * @throws SourceWatcherException
     */
    private function parseUnary () : mixed
    {
        if ( $this->match( "-" ) ) {
            $value = $this->parseUnary();

            if ( !is_numeric( $value ) ) {
                throw new SourceWatcherException( "Derive Field unary minus requires a numeric value." );
            }

            return -$value;
        }

        return $this->parsePrimary();
    }

    /**
     * @throws SourceWatcherException
     */
    private function parsePrimary () : mixed
    {
        if ( $this->match( "number" ) || $this->match( "string" ) ) {
            return $this->previous()["value"];
        }

        if ( $this->match( "(" ) ) {
            $value = $this->parseExpression();
            $this->consume( ")", 'Expected ")" in Derive Field expression.' );

            return $value;
        }

        if ( $this->match( "identifier" ) ) {
            $identifier = $this->previous()["value"];
            $lowerIdentifier = strtolower( $identifier );

            if ( $this->match( "(" ) ) {
                return $this->parseFunction( $identifier );
            }

            return match ( $lowerIdentifier ) {
                "null" => null,
                "true" => true,
                "false" => false,
                default => $this->validationOnly ? 0 : $this->row->get( $identifier ),
            };
        }

        throw new SourceWatcherException( "Expected a value in Derive Field expression." );
    }

    /**
     * @throws SourceWatcherException
     */
    private function parseFunction ( string $name ) : mixed
    {
        $arguments = [];

        if ( !$this->check( ")" ) ) {
            do {
                $arguments[] = $this->parseExpression();
            } while ( $this->match( "," ) );
        }

        $this->consume( ")", sprintf( 'Expected ")" after function "%s".', $name ) );

        return $this->callFunction( strtolower( $name ), $arguments );
    }

    /**
     * @throws SourceWatcherException
     */
    private function callFunction ( string $name, array $arguments ) : mixed
    {
        return match ( $name ) {
            "concat" => implode( "", array_map( fn( mixed $value ) => $value === null ? "" : (string) $value, $arguments ) ),
            "coalesce" => $this->coalesce( $arguments ),
            "upper" => mb_strtoupper( (string) $this->argument( $name, $arguments, 1 )[0] ),
            "lower" => mb_strtolower( (string) $this->argument( $name, $arguments, 1 )[0] ),
            "trim" => trim( (string) $this->argument( $name, $arguments, 1 )[0] ),
            "length" => mb_strlen( (string) $this->argument( $name, $arguments, 1 )[0] ),
            "date_format" => $this->dateFormat( $arguments ),
            default => throw new SourceWatcherException( sprintf( 'Unsupported Derive Field function "%s".', $name ) ),
        };
    }

    private function coalesce ( array $arguments ) : mixed
    {
        foreach ( $arguments as $argument ) {
            if ( $argument !== null ) {
                return $argument;
            }
        }

        return null;
    }

    /**
     * @throws SourceWatcherException
     */
    private function argument ( string $name, array $arguments, int $count ) : array
    {
        if ( count( $arguments ) !== $count ) {
            throw new SourceWatcherException(
                sprintf( 'Derive Field function "%s" expects %d argument(s).', $name, $count )
            );
        }

        return $arguments;
    }

    /**
     * @throws SourceWatcherException
     */
    private function dateFormat ( array $arguments ) : string
    {
        [ $value, $format ] = $this->argument( "date_format", $arguments, 2 );

        if ( $this->validationOnly ) {
            return "";
        }

        try {
            return ( new DateTimeImmutable( (string) $value ) )->format( (string) $format );
        } catch ( Throwable ) {
            throw new SourceWatcherException( "Derive Field date_format received an invalid date." );
        }
    }

    /**
     * @throws SourceWatcherException
     */
    private function arithmetic ( mixed $left, mixed $right, string $operator ) : int|float
    {
        if ( !is_numeric( $left ) || !is_numeric( $right ) ) {
            throw new SourceWatcherException( "Derive Field arithmetic requires numeric values." );
        }

        if ( $operator === "/" && (float) $right === 0.0 && !$this->validationOnly ) {
            throw new SourceWatcherException( "Derive Field cannot divide by zero." );
        }

        if ( $operator === "/" && $this->validationOnly ) {
            return 0;
        }

        return match ( $operator ) {
            "+" => $left + $right,
            "-" => $left - $right,
            "*" => $left * $right,
            "/" => $left / $right,
        };
    }

    private function match ( string $type ) : bool
    {
        if ( !$this->check( $type ) ) {
            return false;
        }

        $this->position++;

        return true;
    }

    private function check ( string $type ) : bool
    {
        return !$this->isAtEnd() && $this->current()["type"] === $type;
    }

    /**
     * @throws SourceWatcherException
     */
    private function consume ( string $type, string $message ) : void
    {
        if ( !$this->match( $type ) ) {
            throw new SourceWatcherException( $message );
        }
    }

    private function current () : array
    {
        return $this->tokens[$this->position];
    }

    private function previous () : array
    {
        return $this->tokens[$this->position - 1];
    }

    private function isAtEnd () : bool
    {
        return $this->position >= count( $this->tokens );
    }
}
