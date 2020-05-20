<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\compare_on;
use function Functional\const_function;

class CompareOnTest extends AbstractTestCase
{
    public function testCompareValues()
    {
        $comparator = compare_on('strcmp');

        $this->assertSame(-1, $comparator('a', 'b'));
        $this->assertSame(0, $comparator('a', 'a'));
        $this->assertSame(1, $comparator('b', 'a'));
    }

    public function testCompareWithReducer()
    {
        $comparator = compare_on('strcmp', const_function(1));

        $this->assertSame(0, $comparator(0, 1));
    }
}
