<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\when;

class WhenTest extends AbstractTestCase
{
    public function testWhenConditionIsTruthy(): void
    {
        self::assertEquals('yes', when(true, 'yes'));
    }

    public function testWhenConditionIsFalsy(): void
    {
        self::assertNull(when(false, 'yes'));
    }

    public function testWhenConditionIsFalsyWithDefault(): void
    {
        self::assertEquals('no', when(false, 'yes', 'no'));
    }

    public function testWhenValueIsClosure(): void
    {
        self::assertEquals('condition was true', when(true, function ($condition) {
            return $condition ? 'condition was true' : 'condition was false';
        }));
    }

    public function testWhenDefaultIsClosure(): void
    {
        self::assertEquals('fallback', when(false, 'yes', function () {
            return 'fallback';
        }));
    }

    public function testWhenConditionIsClosure(): void
    {
        self::assertEquals('yes', when(function () {
            return true;
        }, 'yes'));
    }

    public function testWhenConditionIsClosureReturningFalsy(): void
    {
        self::assertNull(when(function () {
            return false;
        }, 'yes'));
    }
}
