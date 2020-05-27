<?php

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use Coco\SourceWatcher\Core\Extractors\XmlExtractor;
use PHPUnit\Framework\TestCase;

/**
 * Class XmlExtractorTest
 * @package Coco\SourceWatcher\Tests\Core\Extractors
 */
class XmlExtractorTest extends TestCase
{
    /**
     *
     */
    public function testExtract () : void
    {
        $xmlExtractor = new XmlExtractor();

        $this->assertNull( $xmlExtractor->extract() );
    }
}
