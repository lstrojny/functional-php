<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\juxt;

class JuxtTest extends AbstractTestCase
{
    public function test(): void
    {
        $juxted = juxt('strtoupper', 'strtolower', 'strrev');

        self::assertSame(['HELLO', 'hello', 'olleH'], $juxted('Hello'));
    }

    public function testWithMultipleArguments(): void
    {
        $minMax = juxt('min', 'max');

        self::assertSame([1, 5], $minMax(3, 1, 5, 2));
    }

    public function testWithArrayFunctions(): void
    {
        $stats = juxt('array_sum', 'count');

        self::assertSame([15, 5], $stats([1, 2, 3, 4, 5]));
    }

    public function testWithClosures(): void
    {
        $double = static function ($x) {
            return $x * 2;
        };
        $square = static function ($x) {
            return $x * $x;
        };
        $negate = static function ($x) {
            return -$x;
        };

        $juxted = juxt($double, $square, $negate);

        self::assertSame([10, 25, -5], $juxted(5));
    }

    public function testWithNoFunctions(): void
    {
        $juxted = juxt();

        self::assertSame([], $juxted('anything'));
    }

    public function testWithSingleFunction(): void
    {
        $juxted = juxt('strlen');

        self::assertSame([5], $juxted('hello'));
    }
}
