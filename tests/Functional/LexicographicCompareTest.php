<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\lexicographic_compare;

class LexicographicCompareTest extends AbstractTestCase
{
    public function testLexicographicCompareComparison(): void
    {
        $fn = lexicographic_compare(2);

        self::assertEquals(-1, $fn(1));
        self::assertEquals(0, $fn(2));
        self::assertEquals(1, $fn(3));
    }
}
