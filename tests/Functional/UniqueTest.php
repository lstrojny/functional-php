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
use Traversable;

use function Functional\unique;

class UniqueTest extends AbstractTestCase
{
    /** @var array */
    private $mixedTypesArray;

    /** @var Traversable */
    private $mixedTypesIterator;

    public function setUp(): void
    {
        parent::setUp();
        $this->list = ['value1', 'value2', 'value1', 'value'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->mixedTypesArray = [1, '1', '2', 2, '3', 4];
        $this->mixedTypesIterator = new ArrayIterator($this->mixedTypesArray);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2', 'k3' => 'val2'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function testDefaultBehavior(): void
    {
        self::assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list));
        self::assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->listIterator));
        self::assertSame(['k1' => 'val1', 'k2' => 'val2'], unique($this->hash));
        self::assertSame(['k1' => 'val1', 'k2' => 'val2'], unique($this->hashIterator));
        $fn = function ($value, $key, $collection) {
            return $value;
        };
        self::assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list, $fn));
        self::assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->listIterator, $fn));
        self::assertSame(['k1' => 'val1', 'k2' => 'val2'], unique($this->hash, $fn));
        self::assertSame(['k1' => 'val1', 'k2' => 'val2'], unique($this->hashIterator, $fn));
    }

    public function testUnifyingByClosure(): void
    {
        $fn = static function ($value, $key, $collection) {
            return $key === 0 ? 'zero' : 'else';
        };
        self::assertSame([0 => 'value1', 1 => 'value2'], unique($this->list, $fn));
        self::assertSame([0 => 'value1', 1 => 'value2'], unique($this->listIterator, $fn));
        $fn = static function ($value, $key, $collection) {
            return 0;
        };
        self::assertSame(['k1' => 'val1'], unique($this->hash, $fn));
        self::assertSame(['k1' => 'val1'], unique($this->hashIterator, $fn));
    }

    public function testUnifyingStrict(): void
    {
        self::assertSame([0 => 1, 2 => '2', 4 => '3', 5 => 4], unique($this->mixedTypesArray, null, false));
        self::assertSame([1, '1', '2', 2, '3', 4], unique($this->mixedTypesArray));
        self::assertSame([0 => 1, 2 => '2', 4 => '3', 5 => 4], unique($this->mixedTypesIterator, null, false));
        self::assertSame([1, '1', '2', 2, '3', 4], unique($this->mixedTypesIterator));

        $fn = function ($value, $key, $collection) {
            return $value;
        };

        self::assertSame([0 => 1, 2 => '2', 4 => '3', 5 => 4], unique($this->mixedTypesArray, $fn, false));
        self::assertSame([1, '1', '2', 2, '3', 4], unique($this->mixedTypesArray, $fn));
        self::assertSame([0 => 1, 2 => '2', 4 => '3', 5 => 4], unique($this->mixedTypesIterator, null, false));
        self::assertSame([1, '1', '2', 2, '3', 4], unique($this->mixedTypesIterator, $fn));
    }

    public function testPassingNullAsCallback(): void
    {
        self::assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list));
        self::assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list, null));
        self::assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list, null, false));
        self::assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list, null, true));
    }

    public function testExceptionIsThrownInArray(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        unique($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        unique($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        unique($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        unique($this->hashIterator, [$this, 'exception']);
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\unique() expects parameter 1 to be array or instance of Traversable');
        unique('invalidCollection', 'strlen');
    }

    public function testPassNonCallableUndefinedFunction(): void
    {
        $this->expectCallableArgumentError('Functional\unique', 2);
        unique($this->list, 'undefinedFunction');
    }
}
