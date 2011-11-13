<?php
/**
 * Copyright (C) 2011 by Lars Strojny <lstrojny@php.net>
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

class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $functions = func_num_args() ? func_get_arg(0) : array(str_replace('Test', '', get_class($this)));

        foreach ($functions as $function) {
            if (!function_exists($function)) {
                $this->markTestSkipped('Function "' . $function . '" does not exist');
            }
        }
    }

    function expectArgumentError($msg)
    {
        if (extension_loaded('functional')) {
            $this->setExpectedException('PHPUnit_Framework_Error_Warning', $msg);
        } else {
            $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', $msg);
        }
    }

    function exception()
    {
        if (func_num_args() < 3) {
            throw new \DomainException('Callback exception');
        }
        list($v, $k, $c) = func_get_args();
        throw new \DomainException('Callback exception: ' . $k);
    }
}
