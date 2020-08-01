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

    public function testExceptionIfInvalidArrayKey()
    {
        InvalidArgumentException::assertValidArrayKey('property', 'func', 2);
        InvalidArgumentException::assertValidArrayKey(0, 'func', 2);
        InvalidArgumentException::assertValidArrayKey(0.2, 'func', 2);
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage('func() expects parameter 2 to be a valid array key, "stdClass" given. Expected "boolean" or "NULL", "string", "integer", "double"');
        InvalidArgumentException::assertValidArrayKey(new \stdClass(), "func", 2);
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
}
