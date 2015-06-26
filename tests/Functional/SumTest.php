<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional\Tests;

use ArrayIterator;
use function Functional\sum;
use Traversable;

class SumTest extends AbstractTestCase
{
    /** @var array */
    private $intArray;

    /** @var Traversable */
    private $intIterator;

    /** @var array */
    private $floatArray;

    /** @var Traversable */
    private $floatIterator;

    public function setUp()
    {
        parent::setUp();
        $this->intArray = [1 => 1, 2, "foo" => 3];
        $this->intIterator = new ArrayIterator($this->intArray);
        $this->floatArray = [1.1, 2.9, 3.5];
        $this->floatIterator = new ArrayIterator($this->floatArray);
    }

    public function test()
    {
        $this->assertSame(6, sum($this->intArray));
        $this->assertSame(6, sum($this->intIterator));
        $this->assertEquals(7.5, sum($this->floatArray), '', 0.01);
        $this->assertEquals(7.5, sum($this->floatIterator), '', 0.01);
        $this->assertSame(10, sum($this->intArray, 4));
        $this->assertSame(10, sum($this->intIterator, 4));
        $this->assertEquals(10, sum($this->floatArray, 2.5), '', 0.01);
        $this->assertEquals(10, sum($this->floatIterator, 2.5), '', 0.01);
    }

    /** @dataProvider Functional\Tests\MathDataProvider::injectErrorCollection */
    public function testElementsOfWrongTypeAreIgnored($collection)
    {
        $this->assertEquals(3.5, sum($collection), '', 0.1);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\sum() expects parameter 1 to be array or instance of Traversable');
        sum('invalidCollection');
    }
}
