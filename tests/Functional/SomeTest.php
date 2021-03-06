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

use function Functional\some;

class SomeTest extends AbstractTestCase
{
    /** @var string[] */
    private $goodArray;

    /** @var Traversable|string[] */
    private $goodIterator;

    /** @var string[] */
    private $badArray;

    /** @var Traversable|string[] */
    private $badIterator;

    public function setUp(): void
    {
        parent::setUp();
        $this->goodArray = ['value', 'wrong'];
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = ['wrong', 'wrong', 'wrong'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test(): void
    {
        self::assertTrue(some($this->goodArray, [$this, 'functionalCallback']));
        self::assertTrue(some($this->goodIterator, [$this, 'functionalCallback']));
        self::assertFalse(some($this->badArray, [$this, 'functionalCallback']));
        self::assertFalse(some($this->badIterator, [$this, 'functionalCallback']));
    }

    public function testPassNonCallable(): void
    {
        $this->expectCallableArgumentError('Functional\some', 2);
        some($this->goodArray, 'undefinedFunction');
    }

    public function testPassNoCallable(): void
    {
        self::assertTrue(some($this->goodArray));
        self::assertTrue(some($this->goodIterator));
        self::assertTrue(some($this->badArray));
        self::assertTrue(some($this->badIterator));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\some() expects parameter 1 to be array or instance of Traversable');
        some('invalidCollection', 'strlen');
    }

    public function testExceptionThrownInArray(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        some($this->goodArray, [$this, 'exception']);
    }

    public function testExceptionThrownInCollection(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        some($this->goodIterator, [$this, 'exception']);
    }

    public function functionalCallback($value, $key, $collection): bool
    {
        InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
        return $value == 'value' && $key === 0;
    }
}
