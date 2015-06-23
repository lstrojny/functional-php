<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional\Tests;

use ArrayIterator;
use ArrayObject;
use SplFixedArray;
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

    public function __isset($propertyName)
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

    public function __isset($propertyName)
    {
        if ($this->throwExceptionInIsset) {
            throw new \DomainException('__isset exception: ' . $propertyName);
        }
        return true;
    }

    public function __get($propertyName)
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

    public function call($collection, $property)
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
    public function setUp()
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

    public function getNullHash()
    {
        return $this->variateHash($this->nullHash, false);
    }

    public function getNullList()
    {
        return $this->variateList($this->nullHash, false);
    }

    public function variateList($hash, $asObject = true)
    {
        return $this->variate(array_values($hash), $asObject);
    }

    public function variateHash($hash, $asObject = true)
    {
        return $this->variate($hash, $asObject);
    }

    public function variate($array, $asObject)
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

    public function testPluckPropertyThatExistsEverywhere()
    {
        $this->assertSame([1, 2, '3', 4], pluck($this->propertyMagicGet, 'property'));
        $this->assertSame([1, 2], pluck($this->propertyExistsEverywhereArray, 'property'));
        $this->assertSame([1, 2], pluck($this->propertyExistsEverywhereIterator, 'property'));
    }

    public function testPluckPropertyThatExistsSomewhere()
    {
        $this->assertSame([1, null], pluck($this->propertyExistsSomewhere, 'property'));
        $this->assertSame([null, 2], pluck($this->propertyExistsSomewhere, 'otherProperty'));
    }

    public function testPluckPropertyFromMixedCollection()
    {
        $this->assertSame([1, null, 2], pluck($this->mixedCollection, 'property'));
    }

    public function testPluckProtectedProperty()
    {
        $this->assertSame([null, null], pluck([$this, 'foo'], 'preserveGlobalState'));
    }

    public function testPluckPropertyInKeyedCollection()
    {
        $this->assertSame(['test' => 1, 'test2' => 2], pluck($this->keyedCollection, 'property'));
    }

    public function testPluckNumericArrayIndex()
    {
        $this->assertSame(['one' => 1, 'two' => null, 'three' => null, 'four' => 2, 'five' => 3], pluck($this->numericArrayCollection, 0));
        $this->assertSame(['one' => 1, 'two' => null, 'three' => null, 'four' => 2, 'five' => 3], pluck($this->numericArrayCollection, 0));
        $this->assertSame(['one' => 1, 'two' => null, 'three' => null, 'four' => 2, 'five' => 3], pluck(new ArrayIterator($this->numericArrayCollection), 0));
        $this->assertSame([1, null, null, 2, 3], pluck(array_values($this->numericArrayCollection), 0));
        $this->assertSame([1, null, null, 2, 3], pluck(new ArrayIterator(array_values($this->numericArrayCollection)), 0));
        $this->assertSame(['one' => 1, 'two' => null, 'three' => null, 'four' => 2, 'five' => 3], pluck($this->numericArrayCollection, '0'));
    }

    /** @dataProvider getNullList */
    public function testNullLists($it)
    {
        $this->assertSame(['1', '2'], pluck($it, null));
    }

    /** @dataProvider getNullHash */
    public function testNullHash($it)
    {
        $this->assertSame(['one' => '1', 'two' => '2'], pluck($it, null));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\pluck() expects parameter 1 to be array or instance of Traversable');
        pluck('invalidCollection', 'property');
    }

    public function testPassNoPropertyName()
    {
        $this->expectArgumentError('Functional\pluck() expects parameter 2 to be a valid property name or array index, object given');
        pluck($this->propertyExistsSomewhere, new \stdClass());
    }

    public function testExceptionThrownInMagicIssetWhileIteratingArray()
    {
        $this->setExpectedException('DomainException', '__isset exception: foobar');
        pluck($this->issetExceptionArray, 'foobar');
    }

    public function testExceptionThrownInMagicIssetWhileIteratingIterator()
    {
        $this->setExpectedException('DomainException', '__isset exception: foobar');
        pluck($this->issetExceptionIterator, 'foobar');
    }

    public function testExceptionThrownInMagicGetWhileIteratingArray()
    {
        $this->setExpectedException('DomainException', '__get exception: foobar');
        pluck($this->getExceptionArray, 'foobar');
    }

    public function testExceptionThrownInMagicGetWhileIteratingIterator()
    {
        $this->setExpectedException('DomainException', '__get exception: foobar');
        pluck($this->getExceptionIterator, 'foobar');
    }

    public function testClassCallsPluck()
    {
        $caller = new PluckCaller();
        $this->assertSame(['test' => 1, 'test2' => 2], $caller->call($this->keyedCollection, 'property'));
    }
}
