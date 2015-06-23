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
use function Functional\truthy;

class TruthyTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->trueArray = [true, true, 'foo', true, true, 1];
        $this->trueIterator = new ArrayIterator($this->trueArray);
        $this->trueHash = ['k1' => true, 'k2' => 'foo', 'k3' => true, 'k4' => 1];
        $this->trueHashIterator = new ArrayIterator($this->trueHash);
        $this->falseArray = [true, 0, true, null];
        $this->falseIterator = new ArrayIterator($this->falseArray);
        $this->falseHash = ['k1' => true, 'k2' => 0, 'k3' => true, 'k4' => null];
        $this->falseHashIterator = new ArrayIterator($this->falseHash);
    }

    public function test()
    {
        $this->assertTrue(truthy([]));
        $this->assertTrue(truthy(new ArrayIterator([])));
        $this->assertTrue(truthy($this->trueArray));
        $this->assertTrue(truthy($this->trueIterator));
        $this->assertTrue(truthy($this->trueHash));
        $this->assertTrue(truthy($this->trueHashIterator));
        $this->assertFalse(truthy($this->falseArray));
        $this->assertFalse(truthy($this->falseIterator));
        $this->assertFalse(truthy($this->falseHash));
        $this->assertFalse(truthy($this->falseHashIterator));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\truthy() expects parameter 1 to be array or instance of Traversable');
        truthy('invalidCollection');
    }
}
