<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
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
        $getEven = function ($number) {
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
