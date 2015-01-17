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

class EachTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->cb = $this->getMockBuilder('cb')
                         ->setMethods(array('call'))
                         ->getMock();


        $this->array = array('value0', 'value1', 'value2', 'value3');
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array('k0' => 'value0', 'k1' => 'value1', 'k2' => 'value2');
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function testArray()
    {
        $this->prepareCallback($this->array);
        $this->assertNull(each($this->array, array($this->cb, 'call')));
    }

    function testIterator()
    {
        $this->prepareCallback($this->iterator);
        $this->assertNull(each($this->iterator, array($this->cb, 'call')));
    }

    function testHash()
    {
        $this->prepareCallback($this->hash);
        $this->assertNull(each($this->hash, array($this->cb, 'call')));
    }

    function testHashIterator()
    {
        $this->prepareCallback($this->hashIterator);
        $this->assertNull(each($this->hashIterator, array($this->cb, 'call')));
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        each($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInCollection()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        each($this->iterator, array($this, 'exception'));
    }

    function prepareCallback($collection)
    {
        $i = 0;
        foreach ($collection as $key => $value) {
            $this->cb->expects($this->at($i++))->method('call')->with($value, $key, $collection);
        }
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\\each() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        each($this->array, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\each() expects parameter 1 to be array or instance of Traversable');
        each('invalidCollection', 'strlen');
    }
}
