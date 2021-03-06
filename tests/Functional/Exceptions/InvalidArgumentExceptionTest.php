<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Exceptions;

use PHPUnit\Framework\TestCase;

class InvalidArgumentExceptionTest extends TestCase
{
    public function testCallbackExceptionWithUndefinedStaticMethod(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("func() expects parameter 1 to be a valid callback, method 'stdClass::method' not found or invalid method name");

        InvalidArgumentException::assertCallback(['stdClass', 'method'], 'func', 1);
    }

    public function testCallbackExceptionWithUndefinedFunction(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("func() expects parameter 1 to be a valid callback, function 'undefinedFunction' not found or invalid function name");

        InvalidArgumentException::assertCallback('undefinedFunction', 'func', 1);
    }

    public function testCallbackExceptionWithUndefinedMethod(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("func() expects parameter 2 to be a valid callback, method 'stdClass->method' not found or invalid method name");

        InvalidArgumentException::assertCallback([new \stdClass(), 'method'], 'func', 2);
    }

    public function testCallbackExceptionWithIncorrectArrayIndex(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("func() expects parameter 1 to be a valid callback, method 'stdClass->method' not found or invalid method name");

        InvalidArgumentException::assertCallback([1 => new \stdClass(), 2 => 'method'], 'func', 1);
    }

    public function testCallbackExceptionWithObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('func() expected parameter 1 to be a valid callback, no array, string, closure or functor given');

        InvalidArgumentException::assertCallback(new \stdClass(), 'func', 1);
    }

    public function testExceptionIfStringIsPassedAsList(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("func() expects parameter 4 to be array or instance of Traversable, string given");

        InvalidArgumentException::assertCollection('string', 'func', 4);
    }

    public function testExceptionIfObjectIsPassedAsList(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("func() expects parameter 2 to be array or instance of Traversable, stdClass given");

        InvalidArgumentException::assertCollection(new \stdClass(), 'func', 2);
    }

    public function testAssertArrayAccessValidCase(): void
    {
        $validObject = new \ArrayObject();

        InvalidArgumentException::assertArrayAccess($validObject, "func", 4);
        $this->addToAssertionCount(1);
    }

    public function testAssertArrayAccessWithString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('func() expects parameter 4 to be array or instance of ArrayAccess, string given');
        InvalidArgumentException::assertArrayAccess('string', "func", 4);
    }

    public function testAssertArrayAccessWithStandardClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('func() expects parameter 2 to be array or instance of ArrayAccess, stdClass given');
        InvalidArgumentException::assertArrayAccess(new \stdClass(), "func", 2);
    }

    public function testExceptionIfInvalidMethodName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('foo() expects parameter 2 to be string, stdClass given');
        InvalidArgumentException::assertMethodName(new \stdClass(), "foo", 2);
    }

    public function testExceptionIfInvalidPropertyName(): void
    {
        InvalidArgumentException::assertPropertyName('property', 'func', 2);
        InvalidArgumentException::assertPropertyName(0, 'func', 2);
        InvalidArgumentException::assertPropertyName(0.2, 'func', 2);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('func() expects parameter 2 to be a valid property name or array index, stdClass given');
        InvalidArgumentException::assertPropertyName(new \stdClass(), "func", 2);
    }

    public function testNoExceptionThrownWithPositiveInteger(): void
    {
        $this->expectNotToPerformAssertions();
        InvalidArgumentException::assertPositiveInteger('2', 'foo', 1);
        InvalidArgumentException::assertPositiveInteger(2, 'foo', 1);
    }

    public function testExceptionIfNegativeIntegerInsteadOfPositiveInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('func() expects parameter 2 to be positive integer, negative integer given');
        InvalidArgumentException::assertPositiveInteger(-1, 'func', 2);
    }

    public function testExceptionIfStringInsteadOfPositiveInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('func() expects parameter 2 to be positive integer, string given');
        InvalidArgumentException::assertPositiveInteger('str', 'func', 2);
    }

    public function testAssertIntegerAccessWithString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('func() expects parameter 4 to be integer, string given');
        InvalidArgumentException::assertInteger('string', "func", 4);
    }

    public function testAssertIntegerAccessWithObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('func() expects parameter 4 to be integer, stdClass given');
        InvalidArgumentException::assertInteger(new \stdClass(), "func", 4);
    }

    public function testAssertBooleanAccessWithString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('func() expects parameter 4 to be boolean, string given');
        InvalidArgumentException::assertBoolean('string', "func", 4);
    }

    public function testAssertBooleanAccessWithObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('func() expects parameter 4 to be boolean, stdClass given');
        InvalidArgumentException::assertBoolean(new \stdClass(), "func", 4);
    }
}
