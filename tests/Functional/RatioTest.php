<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;

use function Functional\ratio;

class RatioTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->intArray = [1 => 1, 2, "foo" => 3, 4];
        $this->intIterator = new ArrayIterator($this->intArray);
        $this->floatArray = ["foo" => 1.5, 1.1, 1];
        $this->floatIterator = new ArrayIterator($this->floatArray);
    }

    public function test()
    {
        $this->assertSame(1, ratio([1]));
        $this->assertSame(1, ratio(new ArrayIterator([1])));
        $this->assertSame(1, ratio($this->intArray, 24));
        $this->assertSame(1, ratio($this->intIterator, 24));
        $this->assertEquals(-1, ratio($this->floatArray, -1.65), '', 0.01);
        $this->assertEquals(-1, ratio($this->floatIterator, -1.65), '', 0.01);
    }

    /** @dataProvider Functional\Tests\MathDataProvider::injectErrorCollection */
    public function testElementsOfWrongTypeAreIgnored($collection)
    {
        $this->assertEquals(0.333, ratio($collection), '', 0.001);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\ratio() expects parameter 1 to be array or instance of Traversable');
        ratio('invalidCollection', 'strlen');
    }
}
