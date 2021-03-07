<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\partial_left;

class PartialLeftTest extends AbstractPartialTestCase
{
    public function testWithNoArgs(): void
    {
        $ratio = partial_left($this->ratio());
        self::assertSame(2, $ratio(4, 2));
    }

    public function testWithOneArg(): void
    {
        $ratio = partial_left($this->ratio(), 4);
        self::assertSame(2, $ratio(2));
    }

    public function testWithTwoArgs(): void
    {
        $ratio = partial_left($this->ratio(), 2, 4);
        self::assertSame(0.5, $ratio());
    }
}
