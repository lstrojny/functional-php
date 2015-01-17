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

class PartitionTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value1', 'value2', 'value3');
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array('k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3');
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return is_int($k) ? ($k % 2 == 0) : ($v[3] % 2 == 0);
        };
        $this->assertSame(array(array(0 => 'value1', 2 => 'value3'), array(1 => 'value2')), partition($this->array, $fn));
        $this->assertSame(array(array(0 => 'value1', 2 => 'value3'), array(1 => 'value2')), partition($this->iterator, $fn));
        $this->assertSame(array(array('k2' => 'val2'), array('k1' => 'val1', 'k3' => 'val3')), partition($this->hash, $fn));
        $this->assertSame(array(array('k2' => 'val2'), array('k1' => 'val1', 'k3' => 'val3')), partition($this->hashIterator, $fn));
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        partition($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInHash()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        partition($this->hash, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        partition($this->iterator, array($this, 'exception'));
    }

    function testExceptionIsThrownInHashIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        partition($this->hashIterator, array($this, 'exception'));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\partition() expects parameter 1 to be array or instance of Traversable');
        partition('invalidCollection', 'strlen');
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\partition() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        partition($this->array, 'undefinedFunction');
    }
}
