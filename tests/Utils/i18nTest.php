<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Utils;

use Coco\SourceWatcher\Utils\i18n;
use PHPUnit\Framework\TestCase;

/**
 * Class i18nTest
 * @package Coco\SourceWatcher\Tests\Utils
 */
class i18nTest extends TestCase
{
    /**
     *
     */
    public function testGetInstance () : void
    {
        $this->assertEquals( i18n::getInstance(), i18n::getInstance() );
    }

    /**
     *
     */
    public function testGetText () : void
    {
        $expectedString = "This is a test entry!";
        $actualString = i18n::getInstance()->getText( i18nTest::class, "This_Is_A_Test_Entry" );

        $this->assertNotNull( $actualString );
        $this->assertEquals( $expectedString, $actualString );
    }
}
