<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use RuntimeException;

use function Functional\suppress_error;

class SuppressErrorTest extends AbstractTestCase
{
    public function testErrorIsSuppressed()
    {
        $fn = suppress_error('strpos');

        $this->assertNull($fn([], 0));
    }

    public function testFunctionIsWrapped()
    {
        $fn = suppress_error('substr');

        $this->assertSame('f', $fn('foo', 0, 1));
    }

    public function testExceptionsAreHandledTransparently()
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

    public function testErrorHandlerNestingWorks()
    {
        $errorMessage = null;
        set_error_handler(
            static function ($level, $message) use (&$errorMessage) {
                $errorMessage = $message;
            }
        );

        $fn = suppress_error('strpos');
        $this->assertNull($fn([], 0));

        strpos([], 0);
        $this->assertSame('strpos() expects parameter 1 to be string, array given', $errorMessage);
        restore_error_handler();
    }
}
