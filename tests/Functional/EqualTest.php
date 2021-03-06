<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\equal;

class EqualTest extends AbstractTestCase
{
    public function testEqual(): void
    {
        self::assertTrue(equal(2)(2));
        self::assertFalse(equal(2)(3));
        self::assertFalse(equal(3)(2));
        self::assertTrue(equal(3)(3));
    }
}
