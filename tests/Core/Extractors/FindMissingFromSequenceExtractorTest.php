<?php

namespace Coco\SourceWatcher\Tests\Core\Extractors;

use Coco\SourceWatcher\Core\Extractors\FindMissingFromSequenceExtractor;
use PHPUnit\Framework\TestCase;

/**
 * Class FindMissingFromSequenceExtractorTest
 *
 * @package Coco\SourceWatcher\Tests\Core\Extractors
 */
class FindMissingFromSequenceExtractorTest extends TestCase
{
    private FindMissingFromSequenceExtractor $findMissingFromSequenceExtractor;

    public function setUp () : void
    {
        $this->findMissingFromSequenceExtractor = new FindMissingFromSequenceExtractor();
    }

    public function testSetGetFilterField () : void
    {
        $filterField = "some_given_field";

        $this->findMissingFromSequenceExtractor->setFilterField( $filterField );

        $this->assertNotNull( $this->findMissingFromSequenceExtractor->getFilterField() );
        $this->assertEquals( $filterField, $this->findMissingFromSequenceExtractor->getFilterField() );
    }
}
