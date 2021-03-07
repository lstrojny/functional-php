<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;

use function Functional\reduce_left;
use function Functional\reduce_right;

class ReduceTest extends AbstractTestCase
{
    /** @var iterable */
    private $currentCollection;

    public function setUp(): void
    {
        parent::setUp();
        $this->list = ['one', 'two', 'three'];
        $this->listIterator = new ArrayIterator($this->list);
    }

    public function testReducing(): void
    {
        $this->currentCollection = $this->list;
        self::assertSame('0:one,1:two,2:three', reduce_left($this->list, [$this, 'functionalCallback']));
        self::assertSame('default,0:one,1:two,2:three', reduce_left($this->list, [$this, 'functionalCallback'], 'default'));
        self::assertSame('2:three,1:two,0:one', reduce_right($this->list, [$this, 'functionalCallback']));
        self::assertSame('default,2:three,1:two,0:one', reduce_right($this->list, [$this, 'functionalCallback'], 'default'));

        $this->currentCollection = $this->listIterator;
        self::assertSame('0:one,1:two,2:three', reduce_left($this->listIterator, [$this, 'functionalCallback']));
        self::assertSame('default,0:one,1:two,2:three', reduce_left($this->listIterator, [$this, 'functionalCallback'], 'default'));
        self::assertSame('2:three,1:two,0:one', reduce_right($this->listIterator, [$this, 'functionalCallback']));
        self::assertSame('default,2:three,1:two,0:one', reduce_right($this->listIterator, [$this, 'functionalCallback'], 'default'));

        self::assertSame(
            'initial',
            reduce_left(
                [],
                static function () {
                },
                'initial'
            )
        );
        self::assertNull(
            reduce_left(
                [],
                static function () {
                }
            )
        );
        self::assertNull(
            reduce_left(
                [],
                static function () {
                },
                null
            )
        );
        self::assertSame(
            'initial',
            reduce_left(
                new ArrayIterator([]),
                static function () {
                },
                'initial'
            )
        );
        self::assertNull(
            reduce_left(
                new ArrayIterator([]),
                static function () {
                }
            )
        );
        self::assertNull(
            reduce_left(
                new ArrayIterator([]),
                static function () {
                },
                null
            )
        );
        self::assertSame(
            'initial',
            reduce_right(
                [],
                static function () {
                },
                'initial'
            )
        );
        self::assertNull(
            reduce_right(
                [],
                static function () {
                }
            )
        );
        self::assertNull(
            reduce_right(
                [],
                static function () {
                },
                null
            )
        );
        self::assertSame(
            'initial',
            reduce_right(
                new ArrayIterator([]),
                static function () {
                },
                'initial'
            )
        );
        self::assertNull(
            reduce_right(
                new ArrayIterator([]),
                static function () {
                }
            )
        );
        self::assertNull(
            reduce_right(
                new ArrayIterator([]),
                static function () {
                },
                null
            )
        );
    }

    public function testExceptionThrownInIteratorCallbackWhileReduceLeft(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception: 0');
        reduce_left($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionThrownInIteratorCallbackWhileReduceRight(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception: 2');
        reduce_right($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionThrownInArrayCallbackWhileReduceLeft(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception: 0');
        reduce_left($this->list, [$this, 'exception']);
    }

    public function testExceptionThrownInArrayCallbackWhileReduceRight(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception: 2');
        reduce_right($this->list, [$this, 'exception']);
    }

    public function functionalCallback($value, $key, $collection, $returnValue): string
    {
        self::assertContains($value, $this->currentCollection);
        self::assertTrue(isset($this->currentCollection[$key]));
        self::assertSame($collection, $this->currentCollection);

        $ret = $key . ':' . $value;
        if ($returnValue) {
            return $returnValue . ',' . $ret;
        }
        return $ret;
    }

    public function testPassNoCollectionToReduceLeft(): void
    {
        $this->expectArgumentError('Functional\reduce_left() expects parameter 1 to be array or instance of Traversable');
        reduce_left('invalidCollection', 'strlen');
    }

    public function testPassNonCallableToReduceLeft(): void
    {
        $this->expectCallableArgumentError('Functional\reduce_left', 2);
        reduce_left($this->list, 'undefinedFunction');
    }

    public function testPassNoCollectionToReduceRight(): void
    {
        $this->expectArgumentError('Functional\reduce_right() expects parameter 1 to be array or instance of Traversable');
        reduce_right('invalidCollection', 'strlen');
    }

    public function testPassNonCallableToReduceRight(): void
    {
        $this->expectCallableArgumentError('Functional\reduce_right', 2);
        reduce_right($this->list, 'undefinedFunction');
    }
}
