<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\unless;

class UnlessTest extends AbstractTestCase
{
    public function testUnlessConditionIsFalsy(): void
    {
        self::assertEquals('yes', unless(false, 'yes'));
    }

    public function testUnlessConditionIsTruthy(): void
    {
        self::assertNull(unless(true, 'yes'));
    }

    public function testUnlessConditionIsTruthyWithDefault(): void
    {
        self::assertEquals('no', unless(true, 'yes', 'no'));
    }

    public function testUnlessValueIsClosure(): void
    {
        self::assertEquals('fallback', unless(false, function () {
            return 'fallback';
        }));
    }

    public function testUnlessDefaultIsClosure(): void
    {
        self::assertEquals('default', unless(true, 'yes', function () {
            return 'default';
        }));
    }

    public function testUnlessConditionIsClosure(): void
    {
        self::assertEquals('yes', unless(function () {
            return false;
        }, 'yes'));
    }

    public function testUnlessConditionIsClosureReturningTruthy(): void
    {
        self::assertNull(unless(function () {
            return true;
        }, 'yes'));
    }
}
