<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Vendors\Virology\COVID_19\Florida;

use Coco\SourceWatcher\Core\Api\ApiReader;
use Coco\SourceWatcher\Vendors\Virology\COVID_19\Florida\Covid19FloridaApiReader;
use PHPUnit\Framework\TestCase;

/**
 * Class Covid19FloridaApiReaderTest
 * @package Coco\SourceWatcher\Tests\Vendors\Virology\COVID_19\Florida
 */
class Covid19FloridaApiReaderTest extends TestCase
{
    /**
     *
     */
    public function testConstructor () : void
    {
        $reader = new Covid19FloridaApiReader();
        $this->assertNotNull( $reader );
        $this->assertInstanceOf( Covid19FloridaApiReader::class, $reader );
        $this->assertInstanceOf( ApiReader::class, $reader );
    }

    /**
     *
     */
    public function testGetResults () : void
    {
        $reader = new Covid19FloridaApiReader();
        $results = $reader->getResults();

        $this->assertNotNull( $results );

        $this->assertArrayHasKey( "T_positive", $results );

        $this->assertArrayHasKey( "T_NegRes", $results );
        $this->assertArrayHasKey( "T_NegNotFLRes", $results );

        $this->assertArrayHasKey( "T_total", $results );

        $this->assertArrayHasKey( "Deaths", $results );

        $this->assertArrayHasKey( "C_FLRes", $results );
        $this->assertArrayHasKey( "C_FLResOut", $results );

        $this->assertArrayHasKey( "C_HospYes_Res", $results );
        $this->assertArrayHasKey( "C_HospYes_NonRes", $results );
    }
}
