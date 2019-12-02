<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\match;
use function Functional\equal;
use function Functional\const_function;

class MatchTest extends AbstractTestCase
{
    public function testMatch()
    {
        $test = match([
            [equal('foo'), const_function('is foo')],
            [equal('bar'), const_function('is bar')],
            [equal('baz'), const_function('is baz')],
            [const_function(true), function ($x) {
                return 'default is ' . $x;
            }],
        ]);

        $this->assertEquals('is foo', $test('foo'));
        $this->assertEquals('is bar', $test('bar'));
        $this->assertEquals('is baz', $test('baz'));
        $this->assertEquals('default is qux', $test('qux'));
    }

    public function testNothingMatch()
    {
        $test = match([
            [equal('foo'), const_function('is foo')],
            [equal('bar'), const_function('is bar')],
        ]);

        $this->assertNull($test('baz'));
    }

    public function testMatchConditionIsArray()
    {
        $this->expectArgumentError('Functional\match() expects condition at key 1 to be array, string given');

        $callable = function () {
        };

        $test = match([
            [$callable, $callable],
            '',
        ]);
    }

    public function testMatchConditionLength()
    {
        $this->expectArgumentError('Functional\match() expects size of condition at key 1 to be greater than or equals to 2, 1 given');

        $callable = function () {
        };

        $test = match([
            [$callable, $callable],
            [''],
        ]);
    }

    public function testMatchConditionCallables()
    {
        $this->expectException(\Functional\Exceptions\InvalidArgumentException::class);
        $this->expectExceptionMessage('Functional\match() expects first two items of condition at key 1 to be callables');

        $callable = function () {
        };

        $test = match([
            [$callable, $callable],
            [$callable, ''],
        ]);
    }
}
