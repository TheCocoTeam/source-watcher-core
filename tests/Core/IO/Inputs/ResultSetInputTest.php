<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Core\IO\Inputs;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\IO\Inputs\ResultSetInput;
use PHPUnit\Framework\TestCase;

class ResultSetInputTest extends TestCase
{
    public function testStoresRows () : void
    {
        $rows = [ new Row( [ "id" => 1 ] ) ];

        $this->assertSame( $rows, ( new ResultSetInput( $rows ) )->getInput() );
    }

    public function testNonArrayInputClearsRows () : void
    {
        $input = new ResultSetInput( [ new Row( [ "id" => 1 ] ) ] );

        $input->setInput( "not a result set" );

        $this->assertSame( [], $input->getInput() );
    }
}
