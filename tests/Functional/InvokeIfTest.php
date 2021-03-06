<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\invoke_if;

class InvokeIfTest extends AbstractTestCase
{
    public function test(): void
    {
        self::assertSame('methodValue', invoke_if($this, 'method', [], 'defaultValue'));
        self::assertSame('methodValue', invoke_if($this, 'method'));
        $arguments = [1, 2, 3];
        self::assertSame($arguments, invoke_if($this, 'returnArguments', $arguments));
        self::assertNull(invoke_if($this, 'someMethod', $arguments));
        self::assertNull(invoke_if(1, 'someMethod', $arguments));
        self::assertNull(invoke_if(null, 'someMethod', $arguments));
    }

    public function testReturnDefaultValueUsed(): void
    {
        $instance = new \stdClass();
        self::assertSame('defaultValue', invoke_if($instance, 'someMethod', [], 'defaultValue'));
        self::assertSame($instance, invoke_if($this, 'someMethod', [], $instance));
        self::assertNull(invoke_if($this, 'someMethod', [], null));
    }

    public function method(): string
    {
        return 'methodValue';
    }

    public function returnArguments(): array
    {
        return \func_get_args();
    }
}
