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
use function Functional\take_left;

class TakeLeftTest extends AbstractTestCase
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
            $this->assertSame(['foo'], take_left($list, 1));
            $this->assertSame(['foo', 'bar'], take_left($list, 2));
            $this->assertSame(['foo', 'bar', 'baz', 'qux'], take_left($list, 10));
            $this->assertSame([], take_left($list, 0));

            $this->expectExceptionMessage(
                'Functional\take_left() expects parameter 2 to be positive integer, negative integer given'
            );
            take_left($list, -1);
        });
    }
}
