<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\less_than;

class LessThanTest extends AbstractTestCase
{
    public function testLessThanComparison(): void
    {
        $fn = less_than(2);

        self::assertFalse($fn(2));
        self::assertTrue($fn(1));
    }
}
