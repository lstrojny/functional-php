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

use function Functional\reduce_left;
use function Functional\reduce_right;

class ReduceTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp('Functional\reduce_right', 'Functional\reduce_left');
        $this->list = ['one', 'two', 'three'];
        $this->listIterator = new ArrayIterator($this->list);
    }

    public function testReducing()
    {
        $this->currentCollection = $this->list;
        $this->assertSame('0:one,1:two,2:three', reduce_left($this->list, [$this, 'functionalCallback']));
        $this->assertSame('default,0:one,1:two,2:three', reduce_left($this->list, [$this, 'functionalCallback'], 'default'));
        $this->assertSame('2:three,1:two,0:one', reduce_right($this->list, [$this, 'functionalCallback']));
        $this->assertSame('default,2:three,1:two,0:one', reduce_right($this->list, [$this, 'functionalCallback'], 'default'));

        $this->currentCollection = $this->listIterator;
        $this->assertSame('0:one,1:two,2:three', reduce_left($this->listIterator, [$this, 'functionalCallback']));
        $this->assertSame('default,0:one,1:two,2:three', reduce_left($this->listIterator, [$this, 'functionalCallback'], 'default'));
        $this->assertSame('2:three,1:two,0:one', reduce_right($this->listIterator, [$this, 'functionalCallback']));
        $this->assertSame('default,2:three,1:two,0:one', reduce_right($this->listIterator, [$this, 'functionalCallback'], 'default'));

        $this->assertSame('initial', reduce_left([], function () {
        }, 'initial'));
        $this->assertNull(reduce_left([], function () {
        }));
        $this->assertNull(reduce_left([], function () {
        }, null));
        $this->assertSame('initial', reduce_left(new ArrayIterator([]), function () {
        }, 'initial'));
        $this->assertNull(reduce_left(new ArrayIterator([]), function () {
        }));
        $this->assertNull(reduce_left(new ArrayIterator([]), function () {
        }, null));
        $this->assertSame('initial', reduce_right([], function () {
        }, 'initial'));
        $this->assertNull(reduce_right([], function () {
        }));
        $this->assertNull(reduce_right([], function () {
        }, null));
        $this->assertSame('initial', reduce_right(new ArrayIterator([]), function () {
        }, 'initial'));
        $this->assertNull(reduce_right(new ArrayIterator([]), function () {
        }));
        $this->assertNull(reduce_right(new ArrayIterator([]), function () {
        }, null));
    }

    public function testExceptionThrownInIteratorCallbackWhileReduceLeft()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception: 0');
        reduce_left($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionThrownInIteratorCallbackWhileReduceRight()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception: 2');
        reduce_right($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionThrownInArrayCallbackWhileReduceLeft()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception: 0');
        reduce_left($this->list, [$this, 'exception']);
    }

    public function testExceptionThrownInArrayCallbackWhileReduceRight()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception: 2');
        reduce_right($this->list, [$this, 'exception']);
    }

    public function functionalCallback($value, $key, $collection, $returnValue)
    {
        $this->assertContains($value, $this->currentCollection);
        $this->assertTrue(isset($this->currentCollection[$key]));
        $this->assertSame($collection, $this->currentCollection);

        $ret = $key . ':' . $value;
        if ($returnValue) {
            return $returnValue . ',' . $ret;
        }
        return $ret;
    }

    public function testPassNoCollectionToReduceLeft()
    {
        $this->expectArgumentError('Functional\reduce_left() expects parameter 1 to be array or instance of Traversable');
        reduce_left('invalidCollection', 'strlen');
    }

    public function testPassNonCallableToReduceLeft()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\\reduce_left() must be callable");
        reduce_left($this->list, 'undefinedFunction');
    }

    public function testPassNoCollectionToReduceRight()
    {
        $this->expectArgumentError('Functional\reduce_right() expects parameter 1 to be array or instance of Traversable');
        reduce_right('invalidCollection', 'strlen');
    }

    public function testPassNonCallableToReduceRight()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\\reduce_right() must be callable");
        reduce_right($this->list, 'undefinedFunction');
    }
}
