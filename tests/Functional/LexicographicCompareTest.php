<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\lexicographic_compare;

class LexicographicCompareTest extends AbstractTestCase
{
    public function testLexicographicCompareComparison()
    {
        $fn = lexicographic_compare(2);

        $this->assertEquals(-1, $fn(1));
        $this->assertEquals(0, $fn(2));
        $this->assertEquals(1, $fn(3));
    }
}
