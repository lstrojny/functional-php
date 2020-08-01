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
use BadFunctionCallException;
use stdClass;

use function Functional\zip;

class ZipTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['value', 'value'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function testZippingSameSizedArrays()
    {
        $result = [['one', 1, -1], ['two', 2, -2], ['three', 3, -3]];
        $this->assertSame($result, zip(['one', 'two', 'three'], [1, 2, 3], [-1, -2, -3]));
        $this->assertSame(
            $result,
            zip(
                new ArrayIterator(['one', 'two', 'three']),
                new ArrayIterator([1, 2, 3]),
                new ArrayIterator([-1, -2, -3])
            )
        );
    }

    public function testZippingDifferentlySizedArrays()
    {
        $result = [['one', 1, -1, true], ['two', 2, -2, false], ['three', 3, -3, null]];
        $this->assertSame(
            $result,
            zip(['one', 'two', 'three'], [1, 2, 3], [-1, -2, -3], [true, false])
        );
    }

    public function testZippingHashes()
    {
        $result = ['foo' => [1, -1], 'bar' => [2, -2], 0 => [true, false]];
        $this->assertSame(
            $result,
            zip(
                ['foo' => 1, 'bar' => 2, true],
                ['foo' => -1, 'bar' => -2, false, "ignore"]
            )
        );
        $this->assertSame(
            $result,
            zip(
                new ArrayIterator(['foo' => 1, 'bar' => 2, true]),
                new ArrayIterator(['foo' => -1, 'bar' => -2, false, "ignore"])
            )
        );
    }

    public function testZippingWithCallback()
    {
        $result = ['one1-11', 'two2-2', 'three3-3'];
        $this->assertSame(
            $result,
            zip(
                ['one', 'two', 'three'],
                [1, 2, 3],
                [-1, -2, -3],
                [true, false],
                function ($one, $two, $three, $four) {
                    return $one . $two . $three . $four;
                }
            )
        );
        $this->assertSame(
            $result,
            zip(
                new ArrayIterator(['one', 'two', 'three']),
                new ArrayIterator([1, 2, 3]),
                new ArrayIterator([-1, -2, -3]),
                new ArrayIterator([true, false]),
                function ($one, $two, $three, $four) {
                    return $one . $two . $three . $four;
                }
            )
        );
    }

    public function testZippingArraysWithVariousElements()
    {
        $object = new stdClass();
        $resource = stream_context_create();
        $result = [
            [[1], $object, [2]],
            [null, 'foo', null],
            [$resource, null, 2]
        ];

        $this->assertSame(
            $result,
            zip(
                [[1], null, $resource],
                [$object, 'foo', null],
                [[2], null, 2]
            )
        );
    }

    public function testZipSpecialCases()
    {
        $this->assertSame([], zip([]));
        $this->assertSame([], zip([], []));
        $this->assertSame([], zip([], [], function () {
            throw new BadFunctionCallException('Should not be called');
        }));
    }

    public function testPassNoCollectionAsFirstParam()
    {
        $this->expectArgumentError('Functional\zip() expects parameter 1 to be array or instance of Traversable');
        zip('invalidCollection');
    }

    public function testPassNoCollectionAsSecondParam()
    {
        $this->expectArgumentError('Functional\zip() expects parameter 2 to be array or instance of Traversable');
        zip([], 'invalidCollection');
    }

    public function testExceptionInCallback()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        zip([null], [$this, 'exception']);
    }
}
