<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\invoker;

class InvokerTest extends AbstractTestCase
{
    public function testInvokerWithoutArguments()
    {
        $fn = invoker('valueMethod');
        $this->assertSame('value', $fn($this));
    }

    public function testInvokerWithArguments()
    {
        $arguments = [1, 2, 3];
        $fn = invoker('argumentMethod', $arguments);
        $this->assertSame($arguments, $fn($this));
    }

    public function testPassNoString()
    {
        $this->expectArgumentError('Functional\invoker() expects parameter 1 to be string');
        invoker([]);
    }

    public function testInvalidMethod()
    {
        if (!class_exists('Error')) {
            $this->markTestSkipped('Requires PHP 7');
        }

        $fn = invoker('undefinedMethod');

        $this->expectException('Error', 'Call to undefined method Functional\\Tests\\InvokerTest::undefinedMethod');
        $fn($this);
    }

    public function valueMethod(...$arguments)
    {
        $this->assertEmpty($arguments);

        return 'value';
    }

    public function argumentMethod(...$arguments)
    {
        $this->assertNotEmpty($arguments);

        return $arguments;
    }
}
