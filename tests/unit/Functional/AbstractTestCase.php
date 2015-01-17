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

use DomainException;
use PHPUnit_Framework_TestCase as TestCase;
use Functional as F;

class AbstractTestCase extends TestCase
{
    private $functions = array();

    function setUp()
    {
        $this->functions = F\flatten(
            func_num_args() > 0
               ? func_get_arg(0)
               : array(ucfirst(strtolower(str_replace('Test', '', get_class($this)))))
        );

        foreach ($this->functions as $function) {
            if (!function_exists($function)) {
                $this->markTestSkipped(
                    sprintf(
                        'Function "%s()" not implemented in %s version',
                        $function,
                        extension_loaded('functional') ? 'native C' : 'PHP userland'
                    )
                );
                break;
            }
        }
    }

    function expectArgumentError($message)
    {
        try {
            $extension = new \ReflectionExtension('functional');
            $extensionFunctions = array_keys($extension->getFunctions());

            $isDefinedInExtension = F\every(
                $this->functions,
                function ($function) use ($extensionFunctions) {
                    return in_array($function, $extensionFunctions, true);
                }
            );

            if ($isDefinedInExtension) {
                $this->setExpectedException('PHPUnit_Framework_Error_Warning', $message);
            } else {
                $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', $message);
            }
        } catch (\ReflectionException $e) {
            $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', $message);
        }
    }

    function exception()
    {
        if (func_num_args() < 3) {
            throw new DomainException('Callback exception');
        }

        $args = func_get_args();
        $this->assertGreaterThanOrEqual(3, count($args));
        throw new DomainException(sprintf('Callback exception: %s', $args[1]));
    }
}
