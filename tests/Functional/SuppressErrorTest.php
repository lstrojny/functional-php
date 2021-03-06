<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use RuntimeException;

use function Functional\suppress_error;

use const E_USER_ERROR;

class SuppressErrorTest extends AbstractTestCase
{
    public function testErrorIsSuppressed(): void
    {
        $origFn = function () {
            \trigger_error('Some error', E_USER_ERROR);
        };

        $fn = suppress_error($origFn);

        self::assertNull($fn());
    }

    public function testFunctionIsWrapped(): void
    {
        $fn = suppress_error('substr');

        self::assertSame('f', $fn('foo', 0, 1));
    }

    public function testExceptionsAreHandledTransparently(): void
    {
        $expectedException = new RuntimeException();
        $fn = suppress_error(
            function () use ($expectedException) {
                throw $expectedException;
            }
        );

        $this->expectException(RuntimeException::class);

        $fn();
    }

    public function testErrorHandlerNestingWorks(): void
    {
        $errorMessage = null;
        \set_error_handler(
            static function ($level, $message) use (&$errorMessage) {
                $errorMessage = $message;
            }
        );

        $origFn = static function () {
            \trigger_error('Some error', E_USER_ERROR);
        };

        $fn = suppress_error($origFn);
        self::assertNull($fn([], 0));

        self::assertNull($errorMessage);
        $origFn();
        self::assertSame('Some error', $errorMessage);
        \restore_error_handler();
    }
}
