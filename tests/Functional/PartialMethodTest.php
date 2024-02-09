<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\partial_method;

class PartialMethodTest extends AbstractPartialTestCase
{
    public function testWithNoArgs(): void
    {
        $method = partial_method('execute');
        self::assertSame('default', $method($this));
    }

    public function testWithTwoArgs(): void
    {
        $method = partial_method('execute', ['hello', 'world']);
        self::assertSame('hello world', $method($this));
    }

    public function testWithInvalidMethod(): void
    {
        $method = partial_method('undefinedMethod');
        self::assertNull($method($this));
    }

    public function testWithInvalidMethodAndDefaultValue(): void
    {
        $method = partial_method('undefinedMethod', [], 'defaultValue');
        self::assertSame('defaultValue', $method($this));
    }

    public function testWithNonObjectAndDefaultValue(): void
    {
        $method = partial_method('undefinedMethod', [], 'defaultValue');
        self::assertSame('defaultValue', $method('non-object'));
    }

    public function testWithInvalidMethodName(): void
    {
        $this->expectArgumentError('Functional\partial_method() expects parameter 1 to be string, integer given');
        partial_method(1);
    }

    public function execute($arg1 = null, $arg2 = null): string
    {
        return $arg1 ? $arg1 . ' ' . $arg2 : 'default';
    }
}
