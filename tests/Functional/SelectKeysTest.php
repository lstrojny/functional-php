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

use function Functional\select_keys;

class SelectKeysTest extends AbstractTestCase
{
    public static function getData()
    {
        return [
            [[], ['foo' => 1], []],
            [[], ['foo' => 1], ['bar']],
            [['foo' => 1], ['foo' => 1], ['foo']],
            [['foo' => 1, 'bar' => 2], ['foo' => 1, 'bar' => 2], ['foo', 'bar']],
            [[0 => 'a', 2 => 'c'], ['a', 'b', 'c'], [0, 2]],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function test(array $expected, array $input, array $keys)
    {
        $this->assertSame($expected, select_keys($input, $keys));
        $this->assertSame($expected, select_keys(new ArrayIterator($input), $keys));
    }

    public function testPassNonArrayOrTraversable()
    {
        $this->expectArgumentError("Functional\select_keys() expects parameter 1 to be array or instance of Traversable");
        select_keys(new \stdclass(), []);
    }
}
