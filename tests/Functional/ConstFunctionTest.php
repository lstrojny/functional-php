<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\const_function;

class ConstFunctionTest extends AbstractTestCase
{
    public function testWithValue()
    {
        $const = const_function('value');

        $this->assertSame('value', $const());
    }
}
