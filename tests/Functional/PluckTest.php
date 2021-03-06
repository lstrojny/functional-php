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
use ArrayObject;
use SplFixedArray;
use Traversable;

use function Functional\pluck;

class MagicGetThrowException
{
    public function __get($propertyName)
    {
        throw new \Exception($propertyName);
    }
}

class MagicGet
{
    protected $properties;

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function __isset($propertyName): bool
    {
        return isset($this->properties[$propertyName]);
    }

    public function __get($propertyName)
    {
        return $this->properties[$propertyName];
    }
}

class MagicGetException
{
    protected $throwExceptionInIsset = false;
    protected $throwExceptionInGet = false;

    public function __construct($throwExceptionInIsset, $throwExceptionInGet)
    {
        $this->throwExceptionInIsset = $throwExceptionInIsset;
        $this->throwExceptionInGet = $throwExceptionInGet;
    }

    public function __isset($propertyName): bool
    {
        if ($this->throwExceptionInIsset) {
            throw new \DomainException('__isset exception: ' . $propertyName);
        }
        return true;
    }

    public function __get($propertyName): string
    {
        if ($this->throwExceptionInGet) {
            throw new \DomainException('__get exception: ' . $propertyName);
        }
        return "value";
    }
}

class PluckCaller
{
    protected $property;

    public function call($collection, $property): array
    {
        $this->property = 'value';
        $plucked = pluck($collection, $property);
        if (!isset($this->property)) {
            throw new \Exception('Property is no longer accessable');
        }
        return $plucked;
    }
}

class PluckTest extends AbstractTestCase
{
    /** @var object[] */
    private $propertyExistsEverywhereArray;

    /** @var Traversable */
    private $propertyExistsEverywhereIterator;

    /** @var object[] */
    private $propertyExistsSomewhere;

    /** @var array */
    private $propertyMagicGet;

    /** @var array */
    private $mixedCollection;

    /** @var object[] */
    private $keyedCollection;

    /** @var array */
    private $numericArrayCollection;

    /** @var array */
    private $issetExceptionArray;

    /** @var Traversable */
    private $issetExceptionIterator;

    /** @var array */
    private $getExceptionArray;

    /** @var Traversable */
    private $getExceptionIterator;

    public function setUp(): void
    {
        parent::setUp();
        $this->propertyExistsEverywhereArray = [(object)['property' => 1], (object)['property' => 2]];
        $this->propertyExistsEverywhereIterator = new ArrayIterator($this->propertyExistsEverywhereArray);
        $this->propertyExistsSomewhere = [(object)['property' => 1], (object)['otherProperty' => 2]];
        $this->propertyMagicGet = [new MagicGet(['property' => 1]), new MagicGet(['property' => 2]), ['property' => '3'], new ArrayObject(['property' => 4])];
        $this->mixedCollection = [(object)['property' => 1], ['key'  => 'value'], ['property' => 2]];
        $this->keyedCollection = ['test' => (object)['property' => 1], 'test2' => (object)['property' => 2]];
        $fixedArray = new SplFixedArray(1);
        $fixedArray[0] = 3;
        $this->numericArrayCollection = ['one' => [1], 'two' => [1 => 2], 'three' => ['idx' => 2], 'four' => new ArrayObject([2]), 'five' => $fixedArray];
        $this->issetExceptionArray = [(object)['property' => 1], new MagicGetException(true, false)];
        $this->issetExceptionIterator = new ArrayIterator($this->issetExceptionArray);
        $this->getExceptionArray = [(object)['property' => 1], new MagicGetException(false, true)];
        $this->getExceptionIterator = new ArrayIterator($this->getExceptionArray);
    }

    public $nullHash = [
        'one' => [null => '1'],
        'two' => [null => '2'],
    ];

    public function getNullHash(): array
    {
        return $this->variateHash($this->nullHash, false);
    }

    public function getNullList(): array
    {
        return $this->variateList($this->nullHash, false);
    }

    public function variateList($hash, $asObject = true): array
    {
        return $this->variate(\array_values($hash), $asObject);
    }

    public function variateHash($hash, $asObject = true): array
    {
        return $this->variate($hash, $asObject);
    }

    public function variate($array, $asObject): array
    {
        if (!$asObject) {
            return [
                [$array],
                [new ArrayIterator($array)],
            ];
        }

        $objectArray = [];
        foreach ($array as $key => $value) {
            $objectArray[$key] = (object) $value;
        }

        return [
            [$array],
            [new ArrayIterator($array)],
            [$objectArray],
            [new ArrayIterator($objectArray)],
        ];
    }

    public function testPluckPropertyThatExistsEverywhere(): void
    {
        self::assertSame([1, 2, '3', 4], pluck($this->propertyMagicGet, 'property'));
        self::assertSame([1, 2], pluck($this->propertyExistsEverywhereArray, 'property'));
        self::assertSame([1, 2], pluck($this->propertyExistsEverywhereIterator, 'property'));
    }

    public function testPluckPropertyThatExistsSomewhere(): void
    {
        self::assertSame([1, null], pluck($this->propertyExistsSomewhere, 'property'));
        self::assertSame([null, 2], pluck($this->propertyExistsSomewhere, 'otherProperty'));
    }

    public function testPluckPropertyFromMixedCollection(): void
    {
        self::assertSame([1, null, 2], pluck($this->mixedCollection, 'property'));
    }

    public function testPluckProtectedProperty(): void
    {
        self::assertSame([null, null], pluck([$this, 'foo'], 'preserveGlobalState'));
    }

    public function testPluckPropertyInKeyedCollection(): void
    {
        self::assertSame(['test' => 1, 'test2' => 2], pluck($this->keyedCollection, 'property'));
    }

    public function testPluckNumericArrayIndex(): void
    {
        self::assertSame(['one' => 1, 'two' => null, 'three' => null, 'four' => 2, 'five' => 3], pluck($this->numericArrayCollection, 0));
        self::assertSame(['one' => 1, 'two' => null, 'three' => null, 'four' => 2, 'five' => 3], pluck($this->numericArrayCollection, 0));
        self::assertSame(['one' => 1, 'two' => null, 'three' => null, 'four' => 2, 'five' => 3], pluck(new ArrayIterator($this->numericArrayCollection), 0));
        self::assertSame([1, null, null, 2, 3], pluck(\array_values($this->numericArrayCollection), 0));
        self::assertSame([1, null, null, 2, 3], pluck(new ArrayIterator(\array_values($this->numericArrayCollection)), 0));
        self::assertSame(['one' => 1, 'two' => null, 'three' => null, 'four' => 2, 'five' => 3], pluck($this->numericArrayCollection, '0'));
    }

    /** @dataProvider getNullList */
    public function testNullLists($it): void
    {
        self::assertSame(['1', '2'], pluck($it, null));
    }

    /** @dataProvider getNullHash */
    public function testNullHash($it): void
    {
        self::assertSame(['one' => '1', 'two' => '2'], pluck($it, null));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\pluck() expects parameter 1 to be array or instance of Traversable');
        pluck('invalidCollection', 'property');
    }

    public function testPassNoPropertyName(): void
    {
        $this->expectArgumentError('Functional\pluck() expects parameter 2 to be a valid property name or array index, stdClass given');
        pluck($this->propertyExistsSomewhere, new \stdClass());
    }

    public function testExceptionThrownInMagicIssetWhileIteratingArray(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('__isset exception: foobar');
        pluck($this->issetExceptionArray, 'foobar');
    }

    public function testExceptionThrownInMagicIssetWhileIteratingIterator(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('__isset exception: foobar');
        pluck($this->issetExceptionIterator, 'foobar');
    }

    public function testExceptionThrownInMagicGetWhileIteratingArray(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('__get exception: foobar');
        pluck($this->getExceptionArray, 'foobar');
    }

    public function testExceptionThrownInMagicGetWhileIteratingIterator(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('__get exception: foobar');
        pluck($this->getExceptionIterator, 'foobar');
    }

    public function testClassCallsPluck(): void
    {
        $caller = new PluckCaller();
        self::assertSame(['test' => 1, 'test2' => 2], $caller->call($this->keyedCollection, 'property'));
    }
}
