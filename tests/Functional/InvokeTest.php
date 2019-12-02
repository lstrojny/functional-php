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

use function Functional\invoke;

class InvokeTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = [$this, $this, $this];
        $this->listIterator = new ArrayIterator($this->list);
        $this->keyArray = ['k1' => $this, 'k2' => $this];
        $this->keyIterator = new ArrayIterator(['k1' => $this, 'k2' => $this]);
    }

    public function test()
    {
        $this->assertSame(['methodValue', 'methodValue', 'methodValue'], invoke($this->list, 'method', [1, 2]));
        $this->assertSame(['methodValue', 'methodValue', 'methodValue'], invoke($this->listIterator, 'method'));
        $this->assertSame([null, null, null], invoke($this->list, 'undefinedMethod'));
        $this->assertSame([null, null, null], invoke($this->list, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        $this->assertSame([[1, 2], [1, 2], [1, 2]], invoke($this->list, 'returnArguments', [1, 2]));
        $this->assertSame(['k1' => 'methodValue', 'k2' => 'methodValue'], invoke($this->keyArray, 'method'));
        $this->assertSame(['k1' => 'methodValue', 'k2' => 'methodValue'], invoke($this->keyIterator, 'method'));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\invoke() expects parameter 1 to be array or instance of Traversable');
        invoke('invalidCollection', 'method');
    }

    public function testPassNoPropertyName()
    {
        $this->expectArgumentError('Functional\invoke() expects parameter 2 to be string');
        invoke($this->list, new \stdClass());
    }

    public function testException()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        invoke($this->list, 'exception');
    }

    public function method()
    {
        return 'methodValue';
    }

    public function returnArguments()
    {
        return func_get_args();
    }
}
