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
use Functional\Exceptions\InvalidArgumentException;

use function Functional\flat_map;

class FlatMapTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['a', ['b'], ['C' => 'c'], [['d']], null];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['ka' => 'a', 'kb' => ['b'], 'kc' => ['C' => 'c'], 'kd' => [['d']], 'ke' => null, null];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function testList()
    {
        $flat = flat_map(
            ['a', ['b'], ['C' => 'c'], [['d']], null],
            function ($v, $k, $collection) {
                InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
                return $v;
            }
        );

        $this->assertSame(['a','b','c',['d']], $flat);
    }

    public function testListIterator()
    {
        $flat = flat_map(
            new ArrayIterator(['a', ['b'], ['C' => 'c'], [['d']], null]),
            function ($v, $k, $collection) {
                InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
                return $v;
            }
        );

        $this->assertSame(['a','b','c',['d']], $flat);
    }

    public function testHash()
    {
        $flat = flat_map(
            ['ka' => 'a', 'kb' => ['b'], 'kc' => ['C' => 'c'], 'kd' => [['d']], 'ke' => null, null],
            function ($v, $k, $collection) {
                InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
                return $v;
            }
        );

        $this->assertSame(['a','b','c',['d']], $flat);
    }

    public function testHashIterator()
    {
        $flat = flat_map(
            new ArrayIterator(['ka' => 'a', 'kb' => ['b'], 'kc' => ['C' => 'c'], 'kd' => [['d']], 'ke' => null, null]),
            function ($v, $k, $collection) {
                InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
                return $v;
            }
        );

        $this->assertSame(['a','b','c',['d']], $flat);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\flat_map() expects parameter 1 to be array or instance of Traversable');
        flat_map('invalidCollection', 'strlen');
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\\flat_map() must be callable");
        flat_map($this->list, 'undefinedFunction');
    }
}
