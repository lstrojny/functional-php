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
use function Functional\difference;

class DifferenceTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->intArray = [1 => 1, 2, "foo" => 3, 4];
        $this->intIterator = new ArrayIterator($this->intArray);
        $this->floatArray = ["foo" => 4.5, 1.1, 1];
        $this->floatIterator = new ArrayIterator($this->floatArray);
    }

    public function test()
    {
        $this->assertSame(-10, difference($this->intArray));
        $this->assertSame(-10, difference($this->intIterator));
        $this->assertEquals(-6.6, difference($this->floatArray), '', 0.01);
        $this->assertEquals(-6.6, difference($this->floatIterator), '', 0.01);
        $this->assertSame(0, difference($this->intArray, 10));
        $this->assertSame(0, difference($this->intIterator, 10));
        $this->assertEquals(-10, difference($this->floatArray, -3.4), '', 0.01);
        $this->assertEquals(-10, difference($this->floatIterator, -3.4), '', 0.01);
    }

    /** @dataProvider Functional\Tests\MathDataProvider::injectErrorCollection */
    public function testElementsOfWrongTypeAreIgnored($collection)
    {
        $this->assertEquals(-3.5, difference($collection), '', 0.1);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\difference() expects parameter 1 to be array or instance of Traversable');
        difference('invalidCollection', 'strlen');
    }
}
