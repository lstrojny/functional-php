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
use function Functional\each;

class EachTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->cb = $this->getMockBuilder('cb')
                         ->setMethods(['call'])
                         ->getMock();


        $this->list = ['value0', 'value1', 'value2', 'value3'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k0' => 'value0', 'k1' => 'value1', 'k2' => 'value2'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function testArray()
    {
        $this->prepareCallback($this->list);
        $this->assertNull(each($this->list, [$this->cb, 'call']));
    }

    public function testIterator()
    {
        $this->prepareCallback($this->listIterator);
        $this->assertNull(each($this->listIterator, [$this->cb, 'call']));
    }

    public function testHash()
    {
        $this->prepareCallback($this->hash);
        $this->assertNull(each($this->hash, [$this->cb, 'call']));
    }

    public function testHashIterator()
    {
        $this->prepareCallback($this->hashIterator);
        $this->assertNull(each($this->hashIterator, [$this->cb, 'call']));
    }

    public function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        each($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInCollection()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        each($this->listIterator, [$this, 'exception']);
    }

    public function prepareCallback($collection)
    {
        $i = 0;
        foreach ($collection as $key => $value) {
            $this->cb->expects($this->at($i++))->method('call')->with($value, $key, $collection);
        }
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\\each() must be callable");
        each($this->list, 'undefinedFunction');
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\each() expects parameter 1 to be array or instance of Traversable');
        each('invalidCollection', 'strlen');
    }
}
