<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use PHPUnit\Framework\Error\Deprecated as DeprecatedError;

use function Functional\with;

class WithTest extends AbstractTestCase
{
    public function testWithNull()
    {
        $this->assertNull(
            with(null, function () {
                throw new \Exception('Should not be called');
            })
        );
    }

    public function testWithValue()
    {
        $this->assertSame(
            2,
            with(
                1,
                function ($value) {
                    return $value + 1;
                }
            )
        );
    }

    public function testWithCallback()
    {
        DeprecatedError::$enabled = false;

        $this->assertSame(
            'value',
            with(
                function () {
                    return 'value';
                },
                function ($value) {
                    return $value;
                }
            )
        );

        DeprecatedError::$enabled = true;
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\with() must be callable");
        with(null, 'undefinedFunction');
    }

    public function testDefaultValue()
    {
        $this->assertSame(
            'foo',
            with(
                null,
                function () {
                },
                true,
                'foo'
            )
        );
    }
}
