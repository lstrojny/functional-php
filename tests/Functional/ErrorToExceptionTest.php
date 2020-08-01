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

class ErrorToExceptionTest extends AbstractTestCase
{
    public function testErrorIsThrownAsException()
    {
        $fn = error_to_exception('strpos');

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('strpos() expects parameter 1 to be string, array given');

        $fn([], 0);
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

        $fn = error_to_exception('strpos');
        try {
            $fn([], 0);
            $this->fail('ErrorException expected');
        } catch (ErrorException $e) {
            $this->assertNull($errorMessage);
        }

        strpos([], 0);
        $this->assertSame('strpos() expects parameter 1 to be string, array given', $errorMessage);
        restore_error_handler();
    }
}
