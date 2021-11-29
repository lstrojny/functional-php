<?php

/**
 * @package   Functional-php
 * @author    Adrian Panicek <apanicek@pixelfederation.com>
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/adrianpanicek/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;
use Functional as F;
use Functional\Exceptions\InvalidArgumentException;

use function Functional\key_sort;

class KeySortTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['cat', 'bear', 'aardvark'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['c' => 'cat', 'b' => 'bear', 'a' => 'aardvark'];
        $this->hashIterator = new ArrayIterator($this->hash);
        $this->ksortCallback = function ($left, $right, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return strcmp($left, $right);
        };
    }

    public function testWithoutCallback()
    {
        $this->assertSame(['cat', 'bear', 'aardvark'], F\key_sort($this->list));
        $this->assertSame(['cat', 'bear', 'aardvark'], F\key_sort($this->listIterator));
        $this->assertSame(['a' => 'aardvark', 'b' => 'bear', 'c' => 'cat'], F\key_sort($this->hash));
        $this->assertSame(['a' => 'aardvark', 'b' => 'bear', 'c' => 'cat'], F\key_sort($this->hashIterator));
    }

    public function testWithCallback()
    {
        $this->assertSame(['cat', 'bear', 'aardvark'], F\key_sort($this->list, $this->ksortCallback));
        $this->assertSame(['cat', 'bear', 'aardvark'], F\key_sort($this->listIterator, $this->ksortCallback));
        $this->assertSame(['a' => 'aardvark', 'b' => 'bear', 'c' => 'cat'], F\key_sort($this->hash, $this->ksortCallback));
        $this->assertSame(['a' => 'aardvark', 'b' => 'bear', 'c' => 'cat'], F\key_sort($this->hashIterator, $this->ksortCallback));
    }

    public function testImmutability()
    {
        F\key_sort($this->hash);
        $this->assertSame(['c' => 'cat', 'b' => 'bear', 'a' => 'aardvark'], $this->hash);
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\key_sort() must be callable or null");
        F\key_sort($this->list, 'undefinedFunction');
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('key_sort() expects parameter 1 to be array or instance of Traversable');
        F\key_sort('invalidCollection', 'strlen');
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        F\key_sort($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        F\key_sort($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        F\key_sort($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        F\key_sort($this->hashIterator, [$this, 'exception']);
    }
}
