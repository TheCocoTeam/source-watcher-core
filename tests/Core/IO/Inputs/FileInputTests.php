<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\IO\Inputs;

use Coco\SourceWatcher\Core\IO\Inputs\FileInput;
use PHPUnit\Framework\TestCase;

/**
 * Class FileInputTests
 *
 * @package Coco\SourceWatcher\Tests\Core\IO\Inputs
 */
class FileInputTests extends TestCase
{
    public function testSetGetInput () : void
    {
        $fileInput = new FileInput();

        $givenFileLocation = "/some/file/location";
        $expectedFileLocation = "/some/file/location";

        $fileInput->setInput( $givenFileLocation );

        $this->assertEquals( $expectedFileLocation, $fileInput->getInput() );
    }
}
