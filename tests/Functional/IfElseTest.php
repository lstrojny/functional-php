<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\if_else;
use function Functional\equal;
use function Functional\const_function;

class IfElseTest extends AbstractTestCase
{
    public function testIfElse(): void
    {
        $if_foo = if_else(equal('foo'), const_function('bar'), const_function('baz'));

        self::assertEquals('bar', $if_foo('foo'));
        self::assertEquals('baz', $if_foo('qux'));
    }
}
