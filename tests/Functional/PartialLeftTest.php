<?php
namespace Functional\Tests;

use function Functional\partial_left;

class PartialLeftTest extends AbstractPartialTestCase
{
    public function testWithNoArgs()
    {
        $newDiv = partial_left($this->ratio());
        $this->assertSame(2, $newDiv(4, 2));
    }

    public function testWithOneArg()
    {
        $divOne = partial_left($this->ratio(), 4);
        $this->assertSame(2, $divOne(2));
    }

    public function testWithTwoArgs()
    {
        $divTwo = partial_left($this->ratio(), 2, 4);
        $this->assertSame(0.5, $divTwo());
    }
}
