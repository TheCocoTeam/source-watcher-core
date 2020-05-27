<?php

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use Coco\SourceWatcher\Core\Extractors\DatabaseExtractor;
use PHPUnit\Framework\TestCase;

/**
 * Class DatabaseExtractorTest
 * @package Coco\SourceWatcher\Tests\Core\Extractors
 */
class DatabaseExtractorTest extends TestCase
{
    /**
     *
     */
    public function testExtract () : void
    {
        $dbExtractor = $this->createMock( DatabaseExtractor::class );

        $this->assertNull( $dbExtractor->extract() );
    }
}
