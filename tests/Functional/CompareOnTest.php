<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\compare_on;
use function Functional\const_function;

class CompareOnTest extends AbstractTestCase
{
    public function testCompareValues(): void
    {
        $comparator = compare_on('strcmp');

        self::assertSame(-1, $comparator('a', 'b'));
        self::assertSame(0, $comparator('a', 'a'));
        self::assertSame(1, $comparator('b', 'a'));
    }

    public function testCompareWithReducer(): void
    {
        $comparator = compare_on('strcmp', const_function(1));

        self::assertSame(0, $comparator(0, 1));
    }
}
