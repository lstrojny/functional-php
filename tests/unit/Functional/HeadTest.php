<?php
/**
 * Copyright (C) 2011 - 2012 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional;

use ArrayIterator;

class HeadTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('first', 'second', 'third');
        $this->iterator = new ArrayIterator($this->array);
        $this->badArray = array('foo', 'bar', 'baz');
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
            return $v == 'second' && $k == 1;
        };

        $this->assertSame('second', head($this->array, $fn));
        $this->assertSame('second', head($this->iterator, $fn));
        $this->assertNull(head($this->badArray, $fn));
        $this->assertNull(head($this->badIterator, $fn));
    }

    function testWithoutCallback()
    {
        $this->assertSame('first', head($this->array));
        $this->assertSame('first', head($this->array, null));
        $this->assertSame('first', head($this->iterator));
        $this->assertSame('first', head($this->iterator, null));
        $this->assertSame('foo', head($this->badArray));
        $this->assertSame('foo', head($this->badArray, null));
        $this->assertSame('foo', head($this->badIterator));
        $this->assertSame('foo', head($this->badIterator, null));
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError('Functional\head() expects parameter 2 to be a valid callback, function \'undefinedFunction\' not found or invalid function name');
        head($this->array, 'undefinedFunction');
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        head($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInCollection()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        head($this->iterator, array($this, 'exception'));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\head() expects parameter 1 to be array or instance of Traversable');
        head('invalidCollection', 'strlen');
    }
}
