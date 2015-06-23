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
use function Functional\falsy;

class FalsyTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->trueArray = [false, null, false, false, 0];
        $this->trueIterator = new ArrayIterator($this->trueArray);
        $this->trueHash = ['k1' => false, 'k2' => null, 'k3' => false, 'k4' => 0];
        $this->trueHashIterator = new ArrayIterator($this->trueHash);
        $this->falseArray = [false, null, 0, 'foo'];
        $this->falseIterator = new ArrayIterator($this->falseArray);
        $this->falseHash = ['k1' => false, 'k2' => 0, 'k3' => true, 'k4' => null];
        $this->falseHashIterator = new ArrayIterator($this->falseHash);
    }

    public function test()
    {
        $this->assertTrue(falsy([]));
        $this->assertTrue(falsy(new ArrayIterator([])));
        $this->assertTrue(falsy($this->trueArray));
        $this->assertTrue(falsy($this->trueIterator));
        $this->assertTrue(falsy($this->trueHash));
        $this->assertTrue(falsy($this->trueHashIterator));
        $this->assertFalse(falsy($this->falseArray));
        $this->assertFalse(falsy($this->falseIterator));
        $this->assertFalse(falsy($this->falseHash));
        $this->assertFalse(falsy($this->falseHashIterator));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\falsy() expects parameter 1 to be array or instance of Traversable');
        falsy('invalidCollection');
    }
}
