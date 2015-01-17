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

class NoneTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->goodArray = array('value', 'value', 'value');
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = array('value', 'value', 'foo');
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    function test()
    {
        $this->assertTrue(none($this->goodArray, array($this, 'functionalCallback')));
        $this->assertTrue(none($this->goodIterator, array($this, 'functionalCallback')));
        $this->assertFalse(none($this->badArray, array($this, 'functionalCallback')));
        $this->assertFalse(none($this->badIterator, array($this, 'functionalCallback')));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\none() expects parameter 1 to be array or instance of Traversable');
        none('invalidCollection', 'strlen');
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\\none() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        none($this->goodArray, 'undefinedFunction');
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        none($this->goodArray, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        none($this->goodIterator, array($this, 'exception'));
    }

    function functionalCallback($value, $key, $collection)
    {
        Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
        return $value != 'value' && strlen($key) > 0;
    }
}
