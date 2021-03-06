<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\concat;

class ConcatTest extends AbstractTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test(): void
    {
        self::assertSame('foobar', concat('foo', 'bar'));
        self::assertSame('foobarbaz', concat('foo', 'bar', 'baz'));
        self::assertSame('', concat());
    }
}
