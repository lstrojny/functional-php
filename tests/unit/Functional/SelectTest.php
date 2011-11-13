<?php
/**
 * Copyright (C) 2011 by Lars Strojny <lstrojny@php.net>
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

class SelectTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value', 'wrong', 'value');
        $this->iterator = new ArrayIterator($this->array);
        $this->keyedArray = array('k1' => 'value', 'k2' => 'wrong', 'k3' => 'value');
        $this->keyedIterator = new ArrayIterator($this->keyedArray); 
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return $v == 'value' && strlen($k) > 0;
        };
        $this->assertSame(array('value', 2 => 'value'), select($this->array, $fn));
        $this->assertSame(array('value', 2 => 'value'), select($this->iterator, $fn));
        $this->assertSame(array('k1' => 'value', 'k3' => 'value'), select($this->keyedArray, $fn));
        $this->assertSame(array('k1' => 'value', 'k3' => 'value'), select($this->keyedIterator, $fn));
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\select() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        select($this->array, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\select() expects parameter 1 to be array or instance of Traversable');
        select('invalidCollection', 'strlen');
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        select($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInKeyedArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        select($this->keyedArray, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        select($this->iterator, array($this, 'exception'));
    }

    function testExceptionIsThrownInKeyedIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        select($this->keyedIterator, array($this, 'exception'));
    }
}
