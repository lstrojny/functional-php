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

use function Functional\last_index_of;

class LastIndexOfTest extends AbstractTestCase
{
    use IndexesTrait;

    public function test()
    {
        $this->assertSame(0, last_index_of($this->list, 'value1'));
        $this->assertSame(0, last_index_of($this->listIterator, 'value1'));
        $this->assertSame(2, last_index_of($this->list, 'value'));
        $this->assertSame(2, last_index_of($this->listIterator, 'value'));
        $this->assertSame(3, last_index_of($this->list, 'value2'));
        $this->assertSame(3, last_index_of($this->listIterator, 'value2'));
        $this->assertSame('k3', last_index_of($this->hash, 'val1'));
        $this->assertSame('k3', last_index_of($this->hashIterator, 'val1'));
        $this->assertSame('k2', last_index_of($this->hash, 'val2'));
        $this->assertSame('k2', last_index_of($this->hashIterator, 'val2'));
        $this->assertSame('k4', last_index_of($this->hash, 'val3'));
        $this->assertSame('k4', last_index_of($this->hashIterator, 'val3'));
    }

    public function testIfValueCouldNotBeFoundFalseIsReturned()
    {
        $this->assertFalse(last_index_of($this->list, 'invalidValue'));
        $this->assertFalse(last_index_of($this->listIterator, 'invalidValue'));
        $this->assertFalse(last_index_of($this->hash, 'invalidValue'));
        $this->assertFalse(last_index_of($this->hashIterator, 'invalidValue'));
    }

    public function testPassCallback()
    {
        $this->assertSame(
            3,
            last_index_of($this->list, function($element) {return $element;})
        );
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\last_index_of() expects parameter 1 to be array or instance of Traversable');
        last_index_of('invalidCollection', 'idx');
    }
}
