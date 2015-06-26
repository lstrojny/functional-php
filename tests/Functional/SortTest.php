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
use Functional as F;
use Functional\Exceptions\InvalidArgumentException;
use function Functional\sort;

class SortTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['cat', 'bear', 'aardvark'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['c' => 'cat', 'b' => 'bear', 'a' => 'aardvark'];
        $this->hashIterator = new ArrayIterator($this->hash);
        $this->sortCallback = function($left, $right, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return strcmp($left, $right);
        };
    }

    public function testPreserveKeys()
    {
        $this->assertSame([2 => 'aardvark', 1 => 'bear', 0 => 'cat'], F\sort($this->list, $this->sortCallback, true));
        $this->assertSame([2 => 'aardvark', 1 => 'bear', 0 => 'cat'], F\sort($this->listIterator, $this->sortCallback, true));
        $this->assertSame(['a' => 'aardvark', 'b' => 'bear', 'c' => 'cat'], F\sort($this->hash, $this->sortCallback, true));
        $this->assertSame(['a' => 'aardvark', 'b' => 'bear', 'c' => 'cat'], F\sort($this->hashIterator, $this->sortCallback, true));
    }

    public function testWithoutPreserveKeys()
    {
        $this->assertSame([0 => 'aardvark', 1 => 'bear', 2 => 'cat'], F\sort($this->list, $this->sortCallback, false));
        $this->assertSame([0 => 'aardvark', 1 => 'bear', 2 => 'cat'], F\sort($this->listIterator, $this->sortCallback, false));
        $this->assertSame([0 => 'aardvark', 1 => 'bear', 2 => 'cat'], F\sort($this->hash, $this->sortCallback, false));
        $this->assertSame([0 => 'aardvark', 1 => 'bear', 2 => 'cat'], F\sort($this->hashIterator, $this->sortCallback, false));
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\sort() must be callable");
        F\sort($this->list, 'undefinedFunction');
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('sort() expects parameter 1 to be array or instance of Traversable');
        F\sort('invalidCollection', 'strlen');
    }

    public function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        F\sort($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        F\sort($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        F\sort($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        F\sort($this->hashIterator, [$this, 'exception']);
    }
}
