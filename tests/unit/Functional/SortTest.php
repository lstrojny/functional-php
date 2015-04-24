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
namespace Functional;

use ArrayIterator;
use Functional as F;

class SortTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('cat', 'bear', 'aardvark');
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array('c' => 'cat', 'b' => 'bear', 'a' => 'aardvark');
        $this->hashIterator = new ArrayIterator($this->hash);
        $this->sortCallback = function($left, $right, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return strcmp($left, $right);
        };
    }

    function testPreserveKeys()
    {
        $this->assertSame(array(2 => 'aardvark', 1 => 'bear', 0 => 'cat'), F\sort($this->array, $this->sortCallback, true));
        $this->assertSame(array(2 => 'aardvark', 1 => 'bear', 0 => 'cat'), F\sort($this->iterator, $this->sortCallback, true));
        $this->assertSame(array('a' => 'aardvark', 'b' => 'bear', 'c' => 'cat'), F\sort($this->hash, $this->sortCallback, true));
        $this->assertSame(array('a' => 'aardvark', 'b' => 'bear', 'c' => 'cat'), F\sort($this->hashIterator, $this->sortCallback, true));
    }

    function testWithoutPreserveKeys()
    {
        $this->assertSame(array(0 => 'aardvark', 1 => 'bear', 2 => 'cat'), F\sort($this->array, $this->sortCallback, false));
        $this->assertSame(array(0 => 'aardvark', 1 => 'bear', 2 => 'cat'), F\sort($this->iterator, $this->sortCallback, false));
        $this->assertSame(array(0 => 'aardvark', 1 => 'bear', 2 => 'cat'), F\sort($this->hash, $this->sortCallback, false));
        $this->assertSame(array(0 => 'aardvark', 1 => 'bear', 2 => 'cat'), F\sort($this->hashIterator, $this->sortCallback, false));
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("sort() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        F\sort($this->array, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('sort() expects parameter 1 to be array or instance of Traversable');
        F\sort('invalidCollection', 'strlen');
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        F\sort($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInHash()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        F\sort($this->hash, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        F\sort($this->iterator, array($this, 'exception'));
    }

    function testExceptionIsThrownInHashIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        F\sort($this->hashIterator, array($this, 'exception'));
    }
}