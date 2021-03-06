<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\const_function;

class ConstFunctionTest extends AbstractTestCase
{
    public function test(): void
    {
        $const = const_function('value');

        self::assertSame('value', $const());
    }
}
