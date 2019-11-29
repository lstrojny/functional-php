<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;

use function Functional\each;
use function Functional\take_right;

class TakeRightTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->list = ['foo', 'bar', 'baz', 'qux'];
        $this->listIterator = new ArrayIterator($this->list);
    }

    public function test()
    {
        each([$this->list, $this->listIterator], function ($list) {
            $this->assertSame(['qux'], take_right($list, 1));
            $this->assertSame(['baz', 'qux'], take_right($list, 2));
            $this->assertSame(['foo', 'bar', 'baz', 'qux'], take_right($list, 10));
            $this->assertSame([], take_right($list, 0));

            $this->expectExceptionMessage(
                'Functional\take_right() expects parameter 2 to be positive integer, negative integer given'
            );
            take_right($list, -1);
        });
    }

    public function testPreserveKeys()
    {
        each([$this->list, $this->listIterator], function ($list) {
            $this->assertSame([3 => 'qux'], take_right($list, 1, true));
            $this->assertSame([2 => 'baz', 3 => 'qux'], take_right($list, 2, true));

            // "special" cases should behave the same as with $preserveKeys = false
            $this->assertSame(['foo', 'bar', 'baz', 'qux'], take_right($list, 10, true));
            $this->assertSame([], take_right($list, 0, true));
        });
    }
}
