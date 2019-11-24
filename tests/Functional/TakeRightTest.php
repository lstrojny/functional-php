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

use function Functional\each;
use function Functional\take_right;

class TakeRightTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->list = ['foo', 'bar', 'baz', 'qux'];
        $this->listIterator = new ArrayIterator($this->list);
    }

    public function test()
    {
        each([$this->list, $this->listIterator], function ($list) {
            $this->assertSame(['qux'], take_right($list, 1));
            $this->assertSame(['baz', 'qux'], take_right($list, 2));
            $this->assertSame(['foo', 'bar', 'baz', 'qux'], take_right($list, 10));
            $this->assertSame([], take_right($list, 0));

            $this->expectExceptionMessage(
                'Functional\take_right() expects parameter 2 to be positive integer, negative integer given'
            );
            take_right($list, -1);
        });
    }

    public function testPreserveKeys()
    {
        each([$this->list, $this->listIterator], function ($list) {
            $this->assertSame([3 => 'qux'], take_right($list, 1, true));
            $this->assertSame([2 => 'baz', 3 => 'qux'], take_right($list, 2, true));

            // "special" cases should behave the same as with $preserveKeys = false
            $this->assertSame(['foo', 'bar', 'baz', 'qux'], take_right($list, 10, true));
            $this->assertSame([], take_right($list, 0, true));
        });
    }
}
