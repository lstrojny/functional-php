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

use function Functional\compose;

class ComposeTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test()
    {
        $input = range(0, 10);

        $plus2 = function ($x) { return $x + 2; };
        $times4 = function ($x) { return $x * 4; };
        $square = function ($x) { return $x * $x; };

        $composed = compose($plus2, $times4, $square);

        $composed_values = array_map(function ($x) use ($composed) {
            return $composed($x);
        }, $input);

        $manual_values = array_map(function ($x) use ($plus2, $times4, $square) {
            return $square($times4($plus2($x)));
        }, $input);

        $this->assertEquals($composed_values, $manual_values);
    }

    public function testPassNoFunctions()
    {
        $input = range(0, 10);

        $composed = compose();

        $composed_values = array_map(function ($x) use ($composed) {
            return $composed($x);
        }, $input);

        $this->assertEquals($composed_values, $input);
    }
}
