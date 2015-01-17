<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional;

use ArrayIterator;

class InvokeIfTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp(array('Functional\\invoke_if'));
    }

    function test()
    {
        $this->assertSame('methodValue', invoke_if($this, 'method', array(), 'defaultValue'));
        $this->assertSame('methodValue', invoke_if($this, 'method'));
        $arguments = array(1, 2, 3);
        $this->assertSame($arguments, invoke_if($this, 'returnArguments', $arguments));
        $this->assertNull(invoke_if($this, 'someMethod', $arguments));
        $this->assertNull(invoke_if(1, 'someMethod', $arguments));
        $this->assertNull(invoke_if(null, 'someMethod', $arguments));
    }

    function testReturnDefaultValueUsed()
    {
        $instance = new \stdClass();
        $this->assertSame('defaultValue', invoke_if($instance, 'someMethod', array(), 'defaultValue'));
        $this->assertSame($instance, invoke_if($this, 'someMethod', array(), $instance));
        $this->assertNull(invoke_if($this, 'someMethod', array(), null));
    }

    function method()
    {
        return 'methodValue';
    }

    function returnArguments()
    {
        return func_get_args();
    }
}
