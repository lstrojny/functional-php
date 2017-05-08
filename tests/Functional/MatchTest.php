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

use function Functional\match;
use function Functional\equal;
use function Functional\const_function;

class MatchTest extends AbstractTestCase
{
    public function testMatch()
    {
        $test = match([
            [equal('foo'), const_function('is foo')],
            [equal('bar'), const_function('is bar')],
            [equal('baz'), const_function('is baz')],
            [const_function(true), function ($x) { return 'default is '.$x; }],
        ]);

        $this->assertEquals('is foo', $test('foo'));
        $this->assertEquals('is bar', $test('bar'));
        $this->assertEquals('is baz', $test('baz'));
        $this->assertEquals('default is qux', $test('qux'));
    }

    public function testNothingMatch()
    {
        $test = match([
            [equal('foo'), const_function('is foo')],
            [equal('bar'), const_function('is bar')],
        ]);

        $this->assertNull($test('baz'));
    }

    public function testMatchConditionIsArray()
    {
        $this->expectArgumentError('Functional\match() expects condition at key 1 to be array, string given');

        $callable = function () {};

        $test = match([
            [$callable, $callable],
            '',
        ]);
    }

    public function testMatchConditionLength()
    {
        $this->expectArgumentError('Functional\match() expects size of condition at key 1 to be greater than or equals to 2, 1 given');

        $callable = function () {};

        $test = match([
            [$callable, $callable],
            [''],
        ]);
    }

    public function testMatchConditionCallables()
    {
        $this->expectException(\Functional\Exceptions\InvalidArgumentException::class);
        $this->expectExceptionMessage('Functional\match() expects first two items of condition at key 1 to be callables');

        $callable = function () {};

        $test = match([
            [$callable, $callable],
            [$callable, ''],
        ]);
    }
}
