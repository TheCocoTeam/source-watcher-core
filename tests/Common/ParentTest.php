<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Common;

use PHPUnit\Framework\TestCase;

/**
 * Class ParentTest
 *
 * @package Coco\SourceWatcher\Tests\Common
 */
class ParentTest extends TestCase
{
    protected function getEnvironmentVariable ( string $variableName, $default, $castingFunctionName = null )
    {
        if ( !empty( $castingFunctionName ) ) {
            return array_key_exists( $variableName, $_ENV ) ? call_user_func( $castingFunctionName,
                $_ENV[$variableName] ) : $default;
        }

        return array_key_exists( $variableName, $_ENV ) ? $_ENV[$variableName] : $default;
    }
}
