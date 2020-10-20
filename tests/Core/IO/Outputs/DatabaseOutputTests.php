<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\IO\Outputs;

use Coco\SourceWatcher\Core\Database\Connections\Connector;
use Coco\SourceWatcher\Core\IO\Outputs\DatabaseOutput;
use PHPUnit\Framework\TestCase;

/**
 * Class DatabaseOutputTests
 *
 * @package Coco\SourceWatcher\Tests\Core\IO\Outputs
 */
class DatabaseOutputTests extends TestCase
{
    public function testSetGetOutput () : void
    {
        $databaseOutput = new DatabaseOutput();

        $givenOutput = $this->createMock( Connector::class );
        $expectedOutput = $this->createMock( Connector::class );

        $databaseOutput->setOutput( $givenOutput );

        $this->assertEquals( $expectedOutput, $databaseOutput->getOutput() );
    }
}
