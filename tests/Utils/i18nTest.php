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
     * @var string
     */
    private string $envFileLocation = __DIR__ . "/../../" . ".env";

    /**
     * @var bool
     */
    private bool $preExistingEnvFile = false;

    /**
     * @var bool[][]
     */
    private array $envConditionsPerFunction = [ "testGetInstance" => [ "add_i18n_property" => true ], "testGetText" => [ "add_i18n_property" => true ], "testNoEnvFileDefaultToEnglish" => [ "add_i18n_property" => false ] ];

    /**
     *
     */
    public function setUp () : void
    {
        $nextFunctionToBeCalled = $this->getName();

        if ( file_exists( $this->envFileLocation ) ) {
            $this->preExistingEnvFile = true;
        } else {
            $addi18nProperty = $this->envConditionsPerFunction[$nextFunctionToBeCalled]["add_i18n_property"];

            $envFile = fopen( $this->envFileLocation, "w" );

            if ( $addi18nProperty ) {
                $i18nSetting = "I18N_LANGUAGE=en_US";
                fwrite( $envFile, $i18nSetting );
            }

            fclose( $envFile );
        }
    }

    /**
     *
     */
    public function tearDown () : void
    {
        if ( !$this->preExistingEnvFile ) {
            unlink( $this->envFileLocation );
        }
    }

    /**
     *
     */
    public function testGetInstance () : void
    {
        $this->assertEquals( new i18n(), i18n::getInstance() );
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

    /**
     *
     */
    public function testNoEnvFileDefaultToEnglish () : void
    {
        $expectedString = "This is a test entry!";
        $actualString = i18n::getInstance()->getText( i18nTest::class, "This_Is_A_Test_Entry" );

        $this->assertNotNull( $actualString );
        $this->assertEquals( $expectedString, $actualString );
    }
}
