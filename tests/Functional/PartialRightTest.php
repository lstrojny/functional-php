<?php
namespace Functional\Tests;

use function Functional\partial_right;

class PartialRightTest extends AbstractPartialTestCase
{
    public function testWithNoArgs()
    {
        $newDiv = partial_right($this->ratio());
        $this->assertSame(2, $newDiv(4, 2));
    }

    public function testWithOneArg()
    {
        $divOne = partial_right($this->ratio(), 4);
        $this->assertSame(0.5, $divOne(2));
    }

    public function testWithTwoArgs()
    {
        $divTwo = partial_right($this->ratio(), 2, 4);
        $this->assertSame(0.5, $divTwo());
    }
}
