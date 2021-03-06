<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\identical;

class IdenticalTest extends AbstractTestCase
{
    public function testIdenticalComparison(): void
    {
        $fn = identical(2);

        self::assertTrue($fn(2));
        self::assertFalse($fn('2'));
    }
}
