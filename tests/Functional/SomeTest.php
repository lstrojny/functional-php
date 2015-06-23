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
use function Functional\some;

class SomeTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->goodArray = ['value', 'wrong'];
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = ['wrong', 'wrong', 'wrong'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test()
    {
        $this->assertTrue(some($this->goodArray, [$this, 'functionalCallback']));
        $this->assertTrue(some($this->goodIterator, [$this, 'functionalCallback']));
        $this->assertFalse(some($this->badArray, [$this, 'functionalCallback']));
        $this->assertFalse(some($this->badIterator, [$this, 'functionalCallback']));
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\some() must be callable");
        some($this->goodArray, 'undefinedFunction');
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\some() expects parameter 1 to be array or instance of Traversable');
        some('invalidCollection', 'strlen');
    }

    public function testExceptionThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        some($this->goodArray, [$this, 'exception']);
    }

    public function testExceptionThrownInCollection()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        some($this->goodIterator, [$this, 'exception']);
    }

    public function functionalCallback($value, $key, $collection)
    {
        InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
        return $value == 'value' && $key === 0;
    }
}
