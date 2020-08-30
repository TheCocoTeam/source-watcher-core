<?php

namespace Coco\SourceWatcher\Tests\Vendors\StackOverflow;

use Coco\SourceWatcher\Vendors\StackOverflow\StackOverflowWebPageSource;
use PHPUnit\Framework\TestCase;

class StackOverflowWebPageSourceTest extends TestCase
{
    public function testGetResults () : void
    {
        $url = "https://stackoverflow.com/jobs?q=PHP&l=Florida+USA&d=100&u=Miles";

        $webPageSource = new StackOverflowWebPageSource( $url );

        $results = $webPageSource->getResults();
        $this->assertNotNull( $results );
        $this->assertNotEmpty( $results );
    }
}
