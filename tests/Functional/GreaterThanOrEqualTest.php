<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\greater_than_or_equal;

class GreaterThanOrEqualTest extends AbstractTestCase
{
    public function testGreaterThanOrEqualComparison(): void
    {
        $fn = greater_than_or_equal(2);

        self::assertTrue($fn(3));
        self::assertTrue($fn(2));
        self::assertFalse($fn(1));
    }
}
