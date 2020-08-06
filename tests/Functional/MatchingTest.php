<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use PHPUnit\Framework\Error\Deprecated;

use function Functional\matching;
use function Functional\match;
use function Functional\equal;
use function Functional\const_function;

use const PHP_VERSION_ID;

class MatchingTest extends AbstractTestCase
{
    public function testMatching()
    {
        $test = matching(
            [
                [equal('foo'), const_function('is foo')],
                [equal('bar'), const_function('is bar')],
                [equal('baz'), const_function('is baz')],
                [
                    const_function(true),
                    function ($x) {
                        return 'default is ' . $x;
                    }
                ],
            ]
        );

        self::assertEquals('is foo', $test('foo'));
        self::assertEquals('is bar', $test('bar'));
        self::assertEquals('is baz', $test('baz'));
        self::assertEquals('default is qux', $test('qux'));
    }

    public function testNothingMatching()
    {
        $test = matching(
            [
                [equal('foo'), const_function('is foo')],
                [equal('bar'), const_function('is bar')],
            ]
        );

        self::assertNull($test('baz'));
    }

    public function testMatchingConditionIsArray()
    {
        $this->expectArgumentError('Functional\matching() expects condition at key 1 to be array, string given');

        matching(
            [
                [const_function(null), const_function(null)],
                '',
            ]
        );
    }

    public function testMatchingConditionLength()
    {
        $this->expectArgumentError(
            'Functional\matching() expects size of condition at key 1 to be greater than or equals to 2, 1 given'
        );

        matching(
            [
                [const_function(''), const_function('')],
                [''],
            ]
        );
    }

    public function testMatchingConditionCallables()
    {
        $this->expectException(\Functional\Exceptions\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Functional\matching() expects first two items of condition at key 1 to be callables'
        );

        matching(
            [
                [const_function(null), const_function(null)],
                [const_function(null), ''],
            ]
        );
    }

    public function testDeprecatedAlias()
    {
        if (PHP_VERSION_ID >= 80000) {
            self::markTestSkipped('Only works with PHP <8.0');
        }

        $this->expectException(
            Deprecated::class,
            'Functional\match() is deprecated as it is incompatible with PHP 8. Use Functional\matching() instead'
        );
        match([]);
    }
}
