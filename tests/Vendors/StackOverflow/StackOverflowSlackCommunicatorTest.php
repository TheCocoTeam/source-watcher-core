<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Vendors\StackOverflow;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Tests\Common\ParentTest;
use Coco\SourceWatcher\Vendors\StackOverflow\StackOverflowSlackCommunicator;
use Coco\SourceWatcher\Vendors\StackOverflow\StackOverflowWebPageSource;

/**
 * Class StackOverflowSlackCommunicatorTest
 *
 * @package Coco\SourceWatcher\Tests\Vendors\StackOverflow
 */
class StackOverflowSlackCommunicatorTest extends ParentTest
{
    public array $results;

    /**
     * @throws SourceWatcherException
     */
    public function setUp () : void
    {
        $webPageSource = new StackOverflowWebPageSource( $this->getEnvironmentVariable( "STACKOVERFLOW_FL_JOBS_PHP",
            null, null ) );

        $this->results = $webPageSource->getResults();
    }

    public function testCanNotSendToSlackChannel () : void
    {
        $communicator = new StackOverflowSlackCommunicator( $this->results );
        $this->assertFalse( $communicator->send() );
    }

    public function testCanSendToSlackChannel () : void
    {
        $communicator = new StackOverflowSlackCommunicator( $this->results );
        $communicator->setWebHookUrl( $this->getEnvironmentVariable( "SLACK_WEB_HOOK_PHP", null, null ) );
        $this->assertTrue( $communicator->send() );
    }
}
