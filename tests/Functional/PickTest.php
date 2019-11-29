<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayObject;

use function Functional\pick;

class PickTest extends AbstractTestCase
{
    private $array_1;

    public function setUp()
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

    public function test()
    {
        $this->assertSame('2', pick($this->array_1, 'two'));
        $this->assertSame(
            null,
            pick($this->array_1, 'non-existing-index'),
            'Non-existing index, should return null'
        );
        $this->assertSame('3', pick($this->array_1, 3));
        $this->assertSame(0, pick($this->array_1, 'zero-index', ':)'));
        $this->assertSame('default', pick($this->array_1, 'non-existing-index', 'default'));
    }

    public function testInvalidCollectionShouldThrowException()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');

        pick(null, '');
    }

    public function testInvalidCallbackShouldThrowException()
    {
        $this->expectArgumentError('Argument 4 passed to Functional\pick() must be callable');
        pick($this->array_1, '', null, 'not-a-callback');
    }

    public function testDefaultValue()
    {
        $this->assertSame(
            5,
            pick($this->array_1, 'dummy', 5),
            'Index does not exist, should return default value'
        );

        $this->assertSame(
            '1',
            pick($this->array_1, 'one', 5),
            'Index does exists, should return the corresponding value'
        );
    }

    public function testCustomCallback()
    {
        //custom callback to check for false condition
        //false - index does not exist or value is 0
        //true - any other values other than 0, including false, 'false', null, and so on
        $customCallback = function ($collection, $key) {
            if (!isset($collection[$key]) || 0 === $collection[$key]) {
                return false;
            } else {
                return true;
            }
        };

        $this->assertSame(':)', pick($this->array_1, 'non-existing-index', ':)'));
        $this->assertSame(':)', pick($this->array_1, 'zero-index', ':)', $customCallback));
        $this->assertSame('0', pick($this->array_1, 'zero-string-index', ':)', $customCallback));
        $this->assertSame(false, pick($this->array_1, 'false-index', ':)', $customCallback));
    }

    public function testArrayAccess()
    {
        $object = new ArrayObject();

        $object['test'] = 5;

        $this->assertSame(5, pick($object, 'test'), 'Key exists');
        $this->assertSame(10, pick($object, 'dummy', 10), 'Key does not exists');
    }
}
