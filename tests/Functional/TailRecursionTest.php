<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use PHPUnit\Framework\TestCase;

use function Functional\tail_recursion;

class TailRecursionTest extends TestCase
{
    public function testTailRecursion1()
    {
        $fact = tail_recursion(function ($n, $acc = 1) use (&$fact) {
            if ($n == 0) {
                return $acc;
            }
            return $fact($n - 1, $acc * $n);
        });
        $this->assertEquals(3628800, $fact(10));
    }

    public function testTailRecursion2()
    {
        $sum_of_range = tail_recursion(function ($from, $to, $acc = 0) use (&$sum_of_range) {
            if ($from > $to) {
                return $acc;
            }
            return $sum_of_range($from + 1, $to, $acc + $from);
        });

        $this->assertEquals(50005000, $sum_of_range(1, 10000));
    }
}
