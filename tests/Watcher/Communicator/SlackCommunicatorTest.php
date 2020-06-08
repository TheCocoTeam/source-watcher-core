<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Watcher\Communicator;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;
use Coco\SourceWatcher\Watcher\Communicator\SlackCommunicator;
use PHPUnit\Framework\TestCase;

/**
 * Class SlackCommunicatorTest
 * @package Coco\SourceWatcher\Tests\Watcher\Communicator
 */
class SlackCommunicatorTest extends TestCase
{
    /**
     * @var mixed|string|null
     */
    private string $slackWebHook;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var string
     */
    private string $contentType;

    /**
     * @var array
     */
    private array $mockResults;

    /**
     *
     */
    public function setUp () : void
    {
        $this->slackWebHook = array_key_exists( "UNIT_TEST_SLACK_WEB_HOOK", $_ENV ) ? $_ENV["UNIT_TEST_SLACK_WEB_HOOK"] : null;

        $this->method = "POST";

        $this->contentType = "Content-Type: application/json";

        $this->mockResults = [ "blocks" => [ [ "type" => "section", "text" => [ "type" => "mrkdwn", "text" => "a" ] ], [ "type" => "section", "text" => [ "type" => "mrkdwn", "text" => "b" ] ], [ "type" => "section", "text" => [ "type" => "mrkdwn", "text" => "c" ] ] ] ];
    }

    /**
     *
     */
    public function tearDown () : void
    {
        unset( $this->slackWebHook );
        unset( $this->method );
        unset( $this->contentType );
        unset( $this->mockResults );
    }

    /**
     *
     */
    public function testSetGetWebHookUrl () : void
    {
        $communicator = new SlackCommunicator();

        $givenSlackWebHook = $this->slackWebHook;
        $expectedSlackWebHook = $this->slackWebHook;

        $communicator->setWebHookUrl( $givenSlackWebHook );

        $this->assertNotNull( $communicator->getWebHookUrl() );
        $this->assertEquals( $expectedSlackWebHook, $communicator->getWebHookUrl() );
    }

    /**
     *
     */
    public function testSetGetMethod () : void
    {
        $communicator = new SlackCommunicator();

        $givenMethod = $this->method;
        $expectedMethod = $this->method;

        $communicator->setMethod( $givenMethod );

        $this->assertNotNull( $communicator->getMethod() );
        $this->assertEquals( $expectedMethod, $communicator->getMethod() );
    }

    /**
     *
     */
    public function testSetGetContentType () : void
    {
        $communicator = new SlackCommunicator();

        $givenContentType = $this->contentType;
        $expectedContentType = $this->contentType;

        $communicator->setContentType( $givenContentType );

        $this->assertNotNull( $communicator->getContentType() );
        $this->assertEquals( $expectedContentType, $communicator->getContentType() );
    }

    /**
     *
     */
    public function testSetGetData () : void
    {
        $communicator = new SlackCommunicator();

        $givenData = json_encode( $this->mockResults );
        $expectedData = json_encode( $this->mockResults );

        $communicator->setData( $givenData );

        $this->assertNotNull( $communicator->getData() );
        $this->assertEquals( $expectedData, $communicator->getData() );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testSend () : void
    {
        $communicator = new SlackCommunicator();
        $communicator->setWebHookUrl( $this->slackWebHook );
        $communicator->setMethod( $this->method );
        $communicator->setContentType( $this->contentType );
        $communicator->setData( json_encode( $this->mockResults ) );

        $result = $communicator->send();
        $this->assertNotNull( $result );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testNoWebHookException () : void
    {
        $communicator = new SlackCommunicator();
        $communicator->setWebHookUrl( "" );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( SlackCommunicator::class, "No_Web_Hook" ) );

        $communicator->send();
    }

    /**
     * @throws SourceWatcherException
     */
    public function testNoMethodException () : void
    {
        $communicator = new SlackCommunicator();
        $communicator->setWebHookUrl( $this->slackWebHook );
        $communicator->setMethod( "" );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( SlackCommunicator::class, "No_Method" ) );

        $communicator->send();
    }

    /**
     * @throws SourceWatcherException
     */
    public function testNoContentTypeException () : void
    {
        $communicator = new SlackCommunicator();
        $communicator->setWebHookUrl( $this->slackWebHook );
        $communicator->setMethod( $this->method );
        $communicator->setContentType( "" );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( SlackCommunicator::class, "No_Content_Type" ) );

        $communicator->send();
    }

    /**
     * @throws SourceWatcherException
     */
    public function testNoDataException () : void
    {
        $communicator = new SlackCommunicator();
        $communicator->setWebHookUrl( $this->slackWebHook );
        $communicator->setMethod( $this->method );
        $communicator->setContentType( $this->contentType );

        $this->expectException( SourceWatcherException::class );
        $this->expectExceptionMessage( i18n::getInstance()->getText( SlackCommunicator::class, "No_Data" ) );

        $communicator->send();
    }
}
