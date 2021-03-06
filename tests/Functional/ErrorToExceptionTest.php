<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ErrorException;
use RuntimeException;

use function Functional\error_to_exception;
use function trigger_error;

use const E_USER_ERROR;

class ErrorToExceptionTest extends AbstractTestCase
{
    public function testErrorIsThrownAsException()
    {
        $origFn = function () {
            trigger_error('Some error', E_USER_ERROR);
        };

        $fn = error_to_exception($origFn);

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Some error');

        $fn();
    }

    public function testFunctionIsWrapped()
    {
        $fn = error_to_exception('substr');

        $this->assertSame('f', $fn('foo', 0, 1));
    }

    public function testExceptionsAreHandledTransparently()
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

    public function testErrorHandlerNestingWorks()
    {
        $errorMessage = null;
        set_error_handler(
            static function ($level, $message) use (&$errorMessage) {
                $errorMessage = $message;
            }
        );

        $origFn = function () {
            trigger_error('Some error', E_USER_ERROR);
        };

        $fn = error_to_exception($origFn);
        try {
            $fn();
            $this->fail('ErrorException expected');
        } catch (ErrorException $e) {
            $this->assertNull($errorMessage);
        }

        $origFn();
        $this->assertSame('Some error', $errorMessage);
        restore_error_handler();
    }
}
