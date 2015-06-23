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
