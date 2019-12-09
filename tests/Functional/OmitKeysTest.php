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

use function Functional\omit_keys;

class OmitKeysTest extends AbstractTestCase
{
    public static function getData()
    {
        return [
            [['foo' => 1], ['foo' => 1], []],
            [['foo' => 1], ['foo' => 1], ['bar']],
            [[], ['foo' => 1], ['foo']],
            [[], ['foo' => 1, 'bar' => 2], ['foo', 'bar']],
            [['bar' => 2], ['foo' => 1, 'bar' => 2], ['foo']],
            [[1 => 'b'], ['a', 'b', 'c'], [0, 2]],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function test(array $expected, array $input, array $keys)
    {
        $this->assertSame($expected, omit_keys($input, $keys));
        $this->assertSame($expected, omit_keys(new ArrayIterator($input), $keys));
    }

    public function testPassNonArrayOrTraversable()
    {
        $this->expectArgumentError("Functional\omit_keys() expects parameter 1 to be array or instance of Traversable");
        omit_keys(new \stdclass(), []);
    }
}
