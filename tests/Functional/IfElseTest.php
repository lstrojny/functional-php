<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\if_else;
use function Functional\equal;
use function Functional\const_function;

class IfElseTest extends AbstractTestCase
{
    public function testIfElse()
    {
        $if_foo = if_else(equal('foo'), const_function('bar'), const_function('baz'));

        $this->assertEquals('bar', $if_foo('foo'));
        $this->assertEquals('baz', $if_foo('qux'));
    }
}
