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
use Functional\Exceptions\InvalidArgumentException;
use Traversable;

use function Functional\tail;

class TailTest extends AbstractTestCase
{
    /** @var string[] */
    private $badArray;

    /** @var Traversable */
    private $badIterator;

    public function setUp(): void
    {
        parent::setUp();
        $this->list = [1, 2, 3, 4];
        $this->listIterator = new ArrayIterator($this->list);
        $this->badArray = [-1, 0, 1];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test(): void
    {
        $fn = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
            return $v > 2;
        };

        self::assertSame([2 => 3, 3 => 4], tail($this->list, $fn));
        self::assertSame([2 => 3, 3 => 4], tail($this->listIterator, $fn));
        self::assertSame([], tail($this->badArray, $fn));
        self::assertSame([], tail($this->badIterator, $fn));
    }

    public function testWithoutCallback(): void
    {
        self::assertSame([1 => 2, 2 => 3, 3 => 4], tail($this->list));
        self::assertSame([1 => 2, 2 => 3, 3 => 4], tail($this->list, null));
        self::assertSame([1 => 2, 2 => 3, 3 => 4], tail($this->listIterator));
        self::assertSame([1 => 2, 2 => 3, 3 => 4], tail($this->listIterator, null));
        self::assertSame([1 => 0, 2 => 1], tail($this->badArray));
        self::assertSame([1 => 0, 2 => 1], tail($this->badArray, null));
        self::assertSame([1 => 0, 2 => 1], tail($this->badIterator));
        self::assertSame([1 => 0, 2 => 1], tail($this->badIterator, null));
    }

    public function testPassNonCallable(): void
    {
        $this->expectCallableArgumentError('Functional\tail', 2);
        tail($this->list, 'undefinedFunction');
    }

    public function testExceptionIsThrownInArray(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        tail($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInCollection(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        tail($this->listIterator, [$this, 'exception']);
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\tail() expects parameter 1 to be array or instance of Traversable');
        tail('invalidCollection', 'strlen');
    }
}
