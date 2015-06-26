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
use Functional\Exceptions\InvalidArgumentException;
use function Functional\reject;

class RejectTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['value', 'wrong', 'value'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'value', 'k2' => 'wrong', 'k3' => 'value'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test()
    {
        $fn = function($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return $v == 'wrong' && strlen($k) > 0;
        };
        $this->assertSame([0 => 'value', 2 => 'value'], reject($this->list, $fn));
        $this->assertSame([0 => 'value', 2 => 'value'], reject($this->listIterator, $fn));
        $this->assertSame(['k1' => 'value', 'k3' => 'value'], reject($this->hash, $fn));
        $this->assertSame(['k1' => 'value', 'k3' => 'value'], reject($this->hashIterator, $fn));
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\\reject() must be callable");
        reject($this->list, 'undefinedFunction');
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\reject() expects parameter 1 to be array or instance of Traversable');
        reject('invalidCollection', 'strlen');
    }

    public function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        reject($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        reject($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        reject($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        reject($this->hashIterator, [$this, 'exception']);
    }
}
