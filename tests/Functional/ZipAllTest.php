<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use BadFunctionCallException;

use function Functional\zip_all;

class ZipAllTest extends AbstractTestCase
{
    public function testEmpty()
    {
        $this->assertSame([], zip_all());
        $this->assertSame([], zip_all([]));
        $this->assertSame([], zip_all([], [], []));
        $this->assertSame([], zip_all([], [], function () {
            throw new BadFunctionCallException('Should not be called');
        }));
    }

    public function testMissingKeys()
    {
        $result = ['b' => [3, null], 'a' => [null, 2]];
        $this->assertSame(
            $result,
            zip_all(['b' => 3], ['a' => 2])
        );
    }

    public function testDifferentLength()
    {
        $this->assertSame(
            [[1, 3], [null, 4]],
            zip_all([1], [3, 4])
        );
    }

    public function testCallback()
    {
        $this->assertSame(
            [1, 8],
            zip_all([2, 8], [1, 9], 'min')
        );
    }

    public function testIterator()
    {
        $this->assertSame(
            ['a' => [2, 4], 'b' => [3, 5]],
            zip_all(
                new \ArrayIterator(['a' => 2, 'b' => 3]),
                new \ArrayIterator(['a' => 4, 'b' => 5])
            )
        );
    }
}
