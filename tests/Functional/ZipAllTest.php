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

use BadFunctionCallException;
use function Functional\zip_all;

class ZipAllTest extends AbstractTestCase
{
    public function testEmpty()
    {
        $this->assertSame([], zip_all());
        $this->assertSame([], zip_all([]));
        $this->assertSame([], zip_all([], [], []));
        $this->assertSame([], zip_all([], [], function() {
            throw new BadFunctionCallException('Should not be called');
        }));
    }

    public function testMissingKeys()
    {
        $result = ['b' => [3, null], 'a' => [null, 2]];
        $this->assertSame(
            $result,
            zip_all(['b' => 3], ['a' => 2])
        );
    }

    public function testDifferentLength()
    {
        $this->assertSame(
            [[1, 3], [null, 4]],
            zip_all([1], [3, 4])
        );
    }

    public function testCallback()
    {
        $this->assertSame(
            [1, 8],
            zip_all([2, 8], [1, 9], 'min')
        );
    }

    public function testIterator()
    {
        $this->assertSame(
            ['a' => [2, 4], 'b' => [3, 5]],
            zip_all(
                new \ArrayIterator(['a' => 2, 'b' => 3]),
                new \ArrayIterator(['a' => 4, 'b' => 5])
            )
        );
    }
}
