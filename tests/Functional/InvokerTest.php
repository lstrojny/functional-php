<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\invoker;

class InvokerTest extends AbstractTestCase
{
    public function testInvokerWithoutArguments(): void
    {
        $fn = invoker('valueMethod');
        self::assertSame('value', $fn($this));
    }

    public function testInvokerWithArguments(): void
    {
        $arguments = [1, 2, 3];
        $fn = invoker('argumentMethod', $arguments);
        self::assertSame($arguments, $fn($this));
    }

    public function testPassNoString(): void
    {
        $this->expectArgumentError('Functional\invoker() expects parameter 1 to be string');
        invoker([]);
    }

    public function testInvalidMethod(): void
    {
        if (!\class_exists('Error')) {
            self::markTestSkipped('Requires PHP 7');
        }

        $fn = invoker('undefinedMethod');

        $this->expectException('Error', 'Call to undefined method Functional\\Tests\\InvokerTest::undefinedMethod');
        $fn($this);
    }

    public function valueMethod(...$arguments): string
    {
        self::assertEmpty($arguments);

        return 'value';
    }

    public function argumentMethod(...$arguments)
    {
        self::assertNotEmpty($arguments);

        return $arguments;
    }
}
