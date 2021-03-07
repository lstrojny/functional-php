<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayObject;
use Functional\Exceptions\InvalidArgumentException;

use function Functional\pick;

class PickTest extends AbstractTestCase
{
    private $array_1;

    public function setUp(): void
    {
        parent::setUp();
        $this->array_1 = [
            'one' => '1',
            'two' => '2',
            3 => '3',
            'null-index' => null,
            'zero-index' => 0,
            'zero-string-index' => '0',
            'false-index' => false
        ];
    }

    public function test(): void
    {
        self::assertSame('2', pick($this->array_1, 'two'));
        self::assertNull(
            pick($this->array_1, 'non-existing-index'),
            'Non-existing index, should return null'
        );
        self::assertSame('3', pick($this->array_1, 3));
        self::assertSame(0, pick($this->array_1, 'zero-index', ':)'));
        self::assertSame('default', pick($this->array_1, 'non-existing-index', 'default'));
    }

    public function testInvalidCollectionShouldThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        pick(null, '');
    }

    public function testInvalidCallbackShouldThrowException(): void
    {
        $this->expectCallableArgumentError('Functional\pick', 4);
        pick($this->array_1, '', null, 'not-a-callback');
    }

    public function testDefaultValue(): void
    {
        self::assertSame(
            5,
            pick($this->array_1, 'dummy', 5),
            'Index does not exist, should return default value'
        );

        self::assertSame(
            '1',
            pick($this->array_1, 'one', 5),
            'Index does exists, should return the corresponding value'
        );

        self::assertNull(pick($this->array_1, 'null-index', 'default'), 'Will handle null correctly');
    }

    public function testCustomCallback(): void
    {
        //custom callback to check for false condition
        //false - index does not exist or value is 0
        //true - any other values other than 0, including false, 'false', null, and so on
        $customCallback = function ($collection, $key) {
            return isset($collection[$key]) && 0 !== $collection[$key];
        };

        self::assertSame(':)', pick($this->array_1, 'non-existing-index', ':)'));
        self::assertSame(':)', pick($this->array_1, 'zero-index', ':)', $customCallback));
        self::assertSame('0', pick($this->array_1, 'zero-string-index', ':)', $customCallback));
        self::assertFalse(pick($this->array_1, 'false-index', ':)', $customCallback));
    }

    public function testArrayAccess(): void
    {
        $object = new ArrayObject();

        $object['test'] = 5;
        $object['null'] = null;

        self::assertSame(5, pick($object, 'test'), 'Key exists');
        self::assertNull(pick($object, 'null'), 'Key exists');
        self::assertSame(10, pick($object, 'dummy', 10), 'Key does not exists');
    }
}
