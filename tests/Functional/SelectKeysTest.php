<?php
/**
 * Copyright (C) 2011-2017 by Lars Strojny <lstrojny@php.net>
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
use function Functional\select_keys;

class SelectKeysTest extends AbstractTestCase
{
    public static function getData()
    {
        return [
            [[], ['foo' => 1], []],
            [[], ['foo' => 1], ['bar']],
            [['foo' => 1], ['foo' => 1], ['foo']],
            [['foo' => 1, 'bar' => 2], ['foo' => 1, 'bar' => 2], ['foo', 'bar']],
            [[0 => 'a', 2 => 'c'], ['a', 'b', 'c'], [0, 2]],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function test(array $expected, array $input, array $keys)
    {
        $this->assertSame($expected, select_keys($input, $keys));
        $this->assertSame($expected, select_keys(new ArrayIterator($input), $keys));
    }

    public function testPassNonArrayOrTraversable()
    {
        $this->expectArgumentError("Functional\select_keys() expects parameter 1 to be array or instance of Traversable");
        select_keys(new \stdclass(), []);
    }
}
