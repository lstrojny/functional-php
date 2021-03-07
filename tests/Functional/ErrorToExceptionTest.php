<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ErrorException;
use RuntimeException;

use function Functional\error_to_exception;

use const E_USER_ERROR;

class ErrorToExceptionTest extends AbstractTestCase
{
    public function testErrorIsThrownAsException(): void
    {
        $origFn = function () {
            \trigger_error('Some error', E_USER_ERROR);
        };

        $fn = error_to_exception($origFn);

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Some error');

        $fn();
    }

    public function testFunctionIsWrapped(): void
    {
        $fn = error_to_exception('substr');

        self::assertSame('f', $fn('foo', 0, 1));
    }

    public function testExceptionsAreHandledTransparently(): void
    {
        $expectedException = new RuntimeException();
        $fn = error_to_exception(
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

        $fn = error_to_exception($origFn);
        try {
            $fn();
            self::fail('ErrorException expected');
        } catch (ErrorException $e) {
            self::assertNull($errorMessage);
        }

        $origFn();
        self::assertSame('Some error', $errorMessage);
        \restore_error_handler();
    }
}
