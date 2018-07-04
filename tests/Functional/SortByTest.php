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
use function Functional\sort_by;

class SortByTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['bear', 'cat', 'aardvark'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['c' => 'cat', 'b' => 'bear', 'a' => 'aardvark'];
        $this->hashIterator = new ArrayIterator($this->hash);
        $this->getProperty =
            function($e, $_) {
                return strlen($e);
            };
    }

    public function testPreserveKeys()
    {
        $this->assertSame(
            [1 => 'cat', 0 => 'bear', 2 => 'aardvark'],
            sort_by($this->list, $this->getProperty, true)
        );
    }

    public function testWithoutPreserveKeys()
    {
        $this->assertSame(
            [
                0 => 'cat',
                1 => 'bear',
                2 => 'aadrvark'
            ],
            sort_by($this->list, $this->getProperty, false)
        );
    }

    public function testReverseSort()
    {
        $this->assertSame(
            [
                0 => 'aardvark',
                1 => 'bear',
                2 => 'cat'
            ],
            sort_by(
                $this->list,
                function($e, $_) {
                    return -$this->getProperty($e, $_);
                },
                false
            )
        );
    }

}
