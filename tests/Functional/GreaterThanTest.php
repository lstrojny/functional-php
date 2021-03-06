<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\greater_than;

class GreaterThanTest extends AbstractTestCase
{
    public function testGreaterThanComparison(): void
    {
        $fn = greater_than(2);

        self::assertTrue($fn(3));
        self::assertFalse($fn(2));
        self::assertFalse($fn(1));
    }
}
