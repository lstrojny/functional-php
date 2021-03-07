<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\with;

class WithTest extends AbstractTestCase
{
    public function testWithNull(): void
    {
        self::assertNull(
            with(null, function () {
                throw new \Exception('Should not be called');
            })
        );
    }

    public function testWithValue(): void
    {
        self::assertSame(
            2,
            with(
                1,
                function ($value) {
                    return $value + 1;
                }
            )
        );
    }

    public function testPassNonCallable(): void
    {
        $this->expectCallableArgumentError('Functional\with', 2);
        with(null, 'undefinedFunction');
    }

    public function testDefaultValue(): void
    {
        self::assertSame(
            'foo',
            with(
                null,
                static function () {
                },
                false,
                'foo'
            )
        );
    }
}
