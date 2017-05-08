<?php
/**
 * Copyright (C) 2011-2017 by Lars Strojny <lstrojny@php.net>
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
use function Functional\intersperse;

class IntersperseTest extends AbstractTestCase
{
    public function test()
    {
        $array = ['a', 'b', 'c'];
        $traversable = new ArrayIterator($array);
        
        $expected = ['a', '-', 'b', '-', 'c'];
        
        $this->assertSame($expected, intersperse($array, '-'));
        $this->assertSame($expected, intersperse($traversable, '-'));
    }

    public function testEmptyCollection()
    {
        $this->assertSame([], intersperse([], '-'));
        $this->assertSame([], intersperse(new ArrayIterator([]), '-'));
    }
    
    public function testIntersperseWithArray()
    {
        $this->assertSame(['a', ['-'], 'b'], intersperse(['a', 'b'], ['-']));
    }
    
    public function testPassNoCollection()
    {
        $this->expectArgumentError(
            'Functional\intersperse() expects parameter 1 to be array or '.
            'instance of Traversable');
        intersperse('invalidCollection', '-');
    }
}
