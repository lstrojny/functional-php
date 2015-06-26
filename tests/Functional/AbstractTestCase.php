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
namespace Functional\Tests;

use DomainException;
use Functional\Exceptions\InvalidArgumentException;
use PHPUnit_Framework_TestCase as TestCase;
use Functional as F;
use Traversable;

class AbstractTestCase extends TestCase
{
    /** @var array */
    protected $list;

    /** @var Traversable */
    protected $listIterator;

    /** @var array */
    protected $hash;

    /** @var Traversable */
    protected $hashIterator;

    private $functions = [];

    public function setUp()
    {
        $this->functions = F\flatten(
            (array) (
                func_num_args() > 0
               ? func_get_arg(0)
               : $this->getFunctionName()
            )
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

    protected function expectArgumentError($message)
    {
        if (strpos($message, 'callable') !== false) {
            $expectedExceptionClass = version_compare('7.0', PHP_VERSION) < 1 ? 'TypeError' : 'PHPUnit_Framework_Error';
            $expectedMessage = defined('HHVM_VERSION')
                ? str_replace('must be callable', 'must be an instance of callable', $message)
                : $message;
            $this->setExpectedException($expectedExceptionClass, $expectedMessage);
        } else {
            $this->setExpectedException(InvalidArgumentException::class, $message);
        }
    }

    public function exception()
    {
        if (func_num_args() < 3) {
            throw new DomainException('Callback exception');
        }

        $args = func_get_args();
        $this->assertGreaterThanOrEqual(3, count($args));
        throw new DomainException(sprintf('Callback exception: %s', $args[1]));
    }

    protected function sequenceToArray(Traversable $sequence, $limit)
    {
        $values = [];
        $sequence->rewind();
        for ($a = 0; $a < $limit; $a++) {
            $values[] = $sequence->current();
            $sequence->next();
        }

        return $values;
    }

    private function getFunctionName()
    {
        $testName = get_class($this);
        $namespaceSeperatorPosition = strrpos($testName, '\\') + 1;
        $testName = substr($testName, $namespaceSeperatorPosition);
        $function = strtolower(
            implode(
                '_',
                array_slice(
                    preg_split('/([A-Z][a-z]+)/', $testName, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY),
                    0,
                    -1
                )
            )
        );

        return 'Functional\\' . $function;
    }
}
