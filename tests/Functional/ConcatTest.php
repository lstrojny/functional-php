<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\concat;

class ConcatTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test()
    {
        $this->assertSame('foobar', concat('foo', 'bar'));
        $this->assertSame('foobarbaz', concat('foo', 'bar', 'baz'));
        $this->assertSame('', concat());
    }
}
