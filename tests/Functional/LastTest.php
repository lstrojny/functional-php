<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
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
namespace Functional\Tests;

use ArrayIterator;
use Functional\Exceptions\InvalidArgumentException;
use function Functional\last;

class LastTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['first', 'second', 'third', 'fourth'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->badArray = ['foo', 'bar', 'baz'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test()
    {
        $fn = function($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
            return ($v == 'first' && $k == 0) || ($v == 'third' && $k == 2);
        };

        $this->assertSame('third', last($this->list, $fn));
        $this->assertSame('third', last($this->listIterator, $fn));
        $this->assertNull(last($this->badArray, $fn));
        $this->assertNull(last($this->badIterator, $fn));
    }

    public function testWithoutCallback()
    {
        $this->assertSame('fourth', last($this->list));
        $this->assertSame('fourth', last($this->list, null));
        $this->assertSame('fourth', last($this->listIterator));
        $this->assertSame('fourth', last($this->listIterator, null));
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError('Argument 2 passed to Functional\last() must be callable');
        last($this->list, 'undefinedFunction');
    }

    public function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        last($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInCollection()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        last($this->listIterator, [$this, 'exception']);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\last() expects parameter 1 to be array or instance of Traversable');
        last('invalidCollection', 'strlen');
    }
}
