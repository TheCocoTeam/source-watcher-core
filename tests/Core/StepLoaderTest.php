<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Core;

use Coco\SourceWatcher\Core\Extractor;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Core\StepLoader;
use PHPUnit\Framework\TestCase;

/**
 * Class StepLoaderTest
 * @package Coco\SourceWatcher\Tests\Core
 */
class StepLoaderTest extends TestCase
{
    public function testGetExtractor () : void
    {
        try {
            $stepLoader = new StepLoader();
            $extractor = $stepLoader->getStep( Extractor::class, "csv" );
            $this->assertNotNull( $extractor );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }
    }

    public function testGetExtractorValidated () : void
    {
        try {
            $stepLoader = new StepLoader();
            $extractor = $stepLoader->getStep( Extractor::class, "csv" );
            $this->assertInstanceOf( Extractor::class, $extractor );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }
    }

    public function testGetExtractorNoParentClassName () : void
    {
        $this->expectException( SourceWatcherException::class );

        $stepLoader = new StepLoader();
        $stepLoader->getStep( "", "csv" );
    }

    public function testGetExtractorNoStepName () : void
    {
        $this->expectException( SourceWatcherException::class );

        $stepLoader = new StepLoader();
        $stepLoader->getStep( Extractor::class, "" );
    }

    public function testGetNonExistingParentStep () : void
    {
        $this->expectException( SourceWatcherException::class );

        $stepLoader = new StepLoader();
        $stepLoader->getStep( "Sequencer", "csv" );
    }

    public function testGetNonExistingStepName () : void
    {
        try {
            $stepLoader = new StepLoader();
            $extractor = $stepLoader->getStep( Extractor::class, "rainbow" );
            $this->assertNull( $extractor );
        } catch ( SourceWatcherException $sourceWatcherException ) {

        }
    }
}
