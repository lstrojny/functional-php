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
namespace Functional\Exceptions;

class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCallbackExceptionWithUndefinedStaticMethod()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 1 to be a valid callback, method 'stdClass::method' not found or invalid method name"
        );

        InvalidArgumentException::assertCallback(['stdClass', 'method'], 'func', 1);
    }

    public function testCallbackExceptionWithUndefinedFunction()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 1 to be a valid callback, function 'undefinedFunction' not found or invalid function name"
        );

        InvalidArgumentException::assertCallback('undefinedFunction', 'func', 1);
    }

    public function testCallbackExceptionWithUndefinedMethod()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 2 to be a valid callback, method 'stdClass->method' not found or invalid method name"
        );

        InvalidArgumentException::assertCallback([new \stdClass(), 'method'], 'func', 2);
    }

    public function testCallbackExceptionWithIncorrectArrayIndex()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 1 to be a valid callback, method 'stdClass->method' not found or invalid method name"
        );

        InvalidArgumentException::assertCallback([1 => new \stdClass(), 2 => 'method'], 'func', 1);
    }

    public function testCallbackExceptionWithObject()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'func() expected parameter 1 to be a valid callback, no array, string, closure or functor given'
        );

        InvalidArgumentException::assertCallback(new \stdClass(), 'func', 1);
    }

    public function testExceptionIfStringIsPassedAsList()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 4 to be array or instance of Traversable"
        );

        InvalidArgumentException::assertCollection('string', 'func', 4);
    }

    public function testExceptionIfObjectIsPassedAsList()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 2 to be array or instance of Traversable"
        );

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
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'func() expects parameter 4 to be array or instance of ArrayAccess'
        );
        InvalidArgumentException::assertArrayAccess('string', "func", 4);
    }

    public function testAssertArrayAccessWithStandardClass()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'func() expects parameter 2 to be array or instance of ArrayAccess'
        );
        InvalidArgumentException::assertArrayAccess(new \stdClass(), "func", 2);
    }

    public function testExceptionIfInvalidMethodName()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'foo() expects parameter 2 to be string, object given'
        );
        InvalidArgumentException::assertMethodName(new \stdClass(), "foo", 2);
    }

    public function testExceptionIfInvalidPropertyName()
    {
        InvalidArgumentException::assertPropertyName('property', 'func', 2);
        InvalidArgumentException::assertPropertyName(0, 'func', 2);
        InvalidArgumentException::assertPropertyName(0.2, 'func', 2);
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'func() expects parameter 2 to be a valid property name or array index, object given'
        );
        InvalidArgumentException::assertPropertyName(new \stdClass(), "func", 2);
    }

    public function testNoExceptionThrownWithPositiveInteger()
    {
        $this->assertNull(InvalidArgumentException::assertPositiveInteger('2', 'foo', 1));
        $this->assertNull(InvalidArgumentException::assertPositiveInteger(2, 'foo', 1));
    }

    public function testExceptionIfNegativeIntegerInsteadOfPositiveInteger()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'func() expects parameter 2 to be positive integer, negative integer given'
        );
        InvalidArgumentException::assertPositiveInteger(-1, 'func', 2);
    }

    public function testExceptionIfStringInsteadOfPositiveInteger()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'func() expects parameter 2 to be positive integer, string given'
        );
        InvalidArgumentException::assertPositiveInteger('str', 'func', 2);
    }
}
