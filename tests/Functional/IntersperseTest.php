<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;

use function Functional\intersperse;

class IntersperseTest extends AbstractTestCase
{
    public function test(): void
    {
        $array = ['a', 'b', 'c'];
        $traversable = new ArrayIterator($array);

        $expected = ['a', '-', 'b', '-', 'c'];

        self::assertSame($expected, intersperse($array, '-'));
        self::assertSame($expected, intersperse($traversable, '-'));
    }

    public function testEmptyCollection(): void
    {
        self::assertSame([], intersperse([], '-'));
        self::assertSame([], intersperse(new ArrayIterator([]), '-'));
    }

    public function testIntersperseWithArray(): void
    {
        self::assertSame(['a', ['-'], 'b'], intersperse(['a', 'b'], ['-']));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError(
            'Functional\intersperse() expects parameter 1 to be array or ' .
            'instance of Traversable'
        );
        intersperse('invalidCollection', '-');
    }
}
