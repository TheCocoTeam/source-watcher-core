<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Vendors\StackOverflow;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Vendors\StackOverflow\StackOverflowWebPageSource;
use PHPUnit\Framework\TestCase;

/**
 * Class StackOverflowWebPageSourceTest
 *
 * @package Coco\SourceWatcher\Tests\Vendors\StackOverflow
 */
class StackOverflowWebPageSourceTest extends TestCase
{
    /**
     * @throws SourceWatcherException
     */
    public function testGetResults () : void
    {
        $url = "https://stackoverflow.com/jobs?q=PHP&l=Florida+USA&d=100&u=Miles";

        $webPageSource = new StackOverflowWebPageSource( $url );

        $results = $webPageSource->getResults();
        $this->assertNotNull( $results );
        $this->assertNotEmpty( $results );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testGetResultsChangeUrlAfterInstance () : void
    {
        $url = "https://stackoverflow.com/jobs?q=PHP&l=Florida+USA&d=100&u=Miles";
        $webPageSource = new StackOverflowWebPageSource( $url );

        $url = "https://stackoverflow.com/jobs?q=Java&l=Florida+USA&d=100&u=Miles";
        $webPageSource->setUrl( $url );

        $results = $webPageSource->getResults();
        $this->assertNotNull( $results );
        $this->assertNotEmpty( $results );
    }

    /**
     * @throws SourceWatcherException
     */
    public function testGetResultsOnEmptyUrl () : void
    {
        $this->expectException( SourceWatcherException::class );

        $webPageSource = new StackOverflowWebPageSource( "" );
        $webPageSource->getResults();
    }
}
