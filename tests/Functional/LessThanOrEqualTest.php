<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\less_than_or_equal;

class LessThanOrEqualTest extends AbstractTestCase
{
    public function testLessThanOrEqualComparison()
    {
        $fn = less_than_or_equal(2);

        $this->assertFalse($fn(3));
        $this->assertTrue($fn(2));
        $this->assertTrue($fn(1));
    }
}
