<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Exceptions;

use PHPUnit\Framework\TestCase;

class InvalidArgumentExceptionTest extends TestCase
{
    public function testCallbackExceptionWithUndefinedStaticMethod()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage("func() expects parameter 1 to be a valid callback, method 'stdClass::method' not found or invalid method name");

        InvalidArgumentException::assertCallback(['stdClass', 'method'], 'func', 1);
    }

    public function testCallbackExceptionWithUndefinedFunction()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage("func() expects parameter 1 to be a valid callback, function 'undefinedFunction' not found or invalid function name");

        InvalidArgumentException::assertCallback('undefinedFunction', 'func', 1);
    }

    public function testCallbackExceptionWithUndefinedMethod()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage("func() expects parameter 2 to be a valid callback, method 'stdClass->method' not found or invalid method name");

        InvalidArgumentException::assertCallback([new \stdClass(), 'method'], 'func', 2);
    }

    public function testCallbackExceptionWithIncorrectArrayIndex()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage("func() expects parameter 1 to be a valid callback, method 'stdClass->method' not found or invalid method name");

        InvalidArgumentException::assertCallback([1 => new \stdClass(), 2 => 'method'], 'func', 1);
    }

    public function testCallbackExceptionWithObject()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expected parameter 1 to be a valid callback, no array, string, closure or functor given');

        InvalidArgumentException::assertCallback(new \stdClass(), 'func', 1);
    }

    public function testExceptionIfStringIsPassedAsList()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage("func() expects parameter 4 to be array or instance of Traversable, string given");

        InvalidArgumentException::assertCollection('string', 'func', 4);
    }

    public function testExceptionIfObjectIsPassedAsList()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage("func() expects parameter 2 to be array or instance of Traversable, stdClass given");

        InvalidArgumentException::assertCollection(new \stdClass(), 'func', 2);
    }

    public function testAssertArrayAccessValidCase()
    {
        $validObject = new \ArrayObject();

        InvalidArgumentException::assertArrayAccess($validObject, "func", 4);
        $this->addToAssertionCount(1);
    }

    public function testAssertArrayAccessWithString()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expects parameter 4 to be array or instance of ArrayAccess, string given');
        InvalidArgumentException::assertArrayAccess('string', "func", 4);
    }

    public function testAssertArrayAccessWithStandardClass()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expects parameter 2 to be array or instance of ArrayAccess, stdClass given');
        InvalidArgumentException::assertArrayAccess(new \stdClass(), "func", 2);
    }

    public function testExceptionIfInvalidMethodName()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('foo() expects parameter 2 to be string, stdClass given');
        InvalidArgumentException::assertMethodName(new \stdClass(), "foo", 2);
    }

    public function testExceptionIfInvalidPropertyName()
    {
        InvalidArgumentException::assertPropertyName('property', 'func', 2);
        InvalidArgumentException::assertPropertyName(0, 'func', 2);
        InvalidArgumentException::assertPropertyName(0.2, 'func', 2);
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expects parameter 2 to be a valid property name or array index, stdClass given');
        InvalidArgumentException::assertPropertyName(new \stdClass(), "func", 2);
    }

    public function testNoExceptionThrownWithPositiveInteger()
    {
        $this->assertNull(InvalidArgumentException::assertPositiveInteger('2', 'foo', 1));
        $this->assertNull(InvalidArgumentException::assertPositiveInteger(2, 'foo', 1));
    }

    public function testExceptionIfNegativeIntegerInsteadOfPositiveInteger()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expects parameter 2 to be positive integer, negative integer given');
        InvalidArgumentException::assertPositiveInteger(-1, 'func', 2);
    }

    public function testExceptionIfStringInsteadOfPositiveInteger()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expects parameter 2 to be positive integer, string given');
        InvalidArgumentException::assertPositiveInteger('str', 'func', 2);
    }

    public function testAssertIntegerAccessWithString()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expects parameter 4 to be integer, string given');
        InvalidArgumentException::assertInteger('string', "func", 4);
    }

    public function testAssertIntegerAccessWithObject()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expects parameter 4 to be integer, stdClass given');
        InvalidArgumentException::assertInteger(new \stdClass(), "func", 4);
    }

    public function testAssertBooleanAccessWithString()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expects parameter 4 to be boolean, string given');
        InvalidArgumentException::assertBoolean('string', "func", 4);
    }

    public function testAssertBooleanAccessWithObject()
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expects parameter 4 to be boolean, stdClass given');
        InvalidArgumentException::assertBoolean(new \stdClass(), "func", 4);
    }
}
