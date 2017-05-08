<?php
/**
 * Copyright (C) 2011-2017 by Ezinwa Okpoechi <brainmaestro@outlook.com>
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

use function Functional\flip;
use function Functional\filter;
use function Functional\id;

class FlipTest extends AbstractTestCase
{
    public function testFlippedMergeStrings()
    {
        $flippedMergeStrings = flip('Functional\Tests\merge_strings');
        $this->assertSame(merge_strings('one', 'two'), $flippedMergeStrings('two', 'one'));
        $this->assertSame(merge_strings('rick', 'and', 'morty'), $flippedMergeStrings('morty', 'and', 'rick'));
    }

    public function testFlippedSubtract()
    {
        $flippedSubtract = flip('Functional\Tests\subtract');
        $this->assertSame(subtract(5, 3, 2), $flippedSubtract(2, 3, 5));
    }

    public function testFlippedFilter()
    {
        $data = [1, 2, 3, 4];
        $getEven = function($number) {
            return $number % 2 == 0;
        };
        $flippedFilter = flip('Functional\filter');

        $this->assertSame(filter($data, $getEven), $flippedFilter($getEven, $data));
    }

    public function testFlippedId()
    {
        $flippedId = flip('Functional\id');
        $this->assertSame(id(1), $flippedId(1));
    }
}

function merge_strings(string $head, string $tail, ...$other)
{
    return $head . $tail . implode('', $other);
}

function subtract(int $first, int $second, int $third)
{
    return $first - $second - $third;
}
