<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core\Api;

use Coco\SourceWatcher\Core\Api\ApiReader;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;
use PHPUnit\Framework\TestCase;

class ApiReaderTest extends TestCase
{
    public function testSetGetResourceURL () : void
    {
        $apiReader = new ApiReader();

        $givenResourceURL = "https://api.github.com/emojis";
        $expectedResourceURL = "https://api.github.com/emojis";

        $apiReader->setResourceURL( $givenResourceURL );

        $this->assertEquals( $expectedResourceURL, $apiReader->getResourceURL() );
    }

    public function testSetGetTimeout () : void
    {
        $apiReader = new ApiReader();

        $givenTimeout = 10;
        $expectedTimeout = 10;

        $apiReader->setTimeout( $givenTimeout );

        $this->assertEquals( $expectedTimeout, $apiReader->getTimeout() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testGetExceptionFromNoResourceURL () : void
    {
        $apiReader = new ApiReader();

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( "en_US", ApiReader::class, "No_Resource_URL_Found" ) );

        $apiReader->read();
    }
}
