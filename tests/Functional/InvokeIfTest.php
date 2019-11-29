<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\invoke_if;

class InvokeIfTest extends AbstractTestCase
{
    public function test()
    {
        $this->assertSame('methodValue', invoke_if($this, 'method', [], 'defaultValue'));
        $this->assertSame('methodValue', invoke_if($this, 'method'));
        $arguments = [1, 2, 3];
        $this->assertSame($arguments, invoke_if($this, 'returnArguments', $arguments));
        $this->assertNull(invoke_if($this, 'someMethod', $arguments));
        $this->assertNull(invoke_if(1, 'someMethod', $arguments));
        $this->assertNull(invoke_if(null, 'someMethod', $arguments));
    }

    public function testReturnDefaultValueUsed()
    {
        $instance = new \stdClass();
        $this->assertSame('defaultValue', invoke_if($instance, 'someMethod', [], 'defaultValue'));
        $this->assertSame($instance, invoke_if($this, 'someMethod', [], $instance));
        $this->assertNull(invoke_if($this, 'someMethod', [], null));
    }

    public function method()
    {
        return 'methodValue';
    }

    public function returnArguments()
    {
        return func_get_args();
    }
}
