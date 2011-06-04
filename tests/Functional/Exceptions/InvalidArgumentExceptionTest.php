<?php
namespace Functional\Exceptions;

class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    function testCallbackExceptionWithUndefinedStaticMethod()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 1 to be a valid callback, method 'stdClass::method' not found or invalid method name"
        );

        InvalidArgumentException::assertCallback(array('stdClass', 'method'), 'func', 1);
    }

    function testCallbackExceptionWithUndefinedFunction()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 1 to be a valid callback, function 'undefinedFunction' not found or invalid function name"
        );

        InvalidArgumentException::assertCallback('undefinedFunction', 'func', 1);
    }

    function testCallbackExceptionWithUndefinedMethod()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 2 to be a valid callback, method 'stdClass->method' not found or invalid method name"
        );

        InvalidArgumentException::assertCallback(array(new \stdClass(), 'method'), 'func', 2);
    }

    function testCallbackExceptionWithIncorrectArrayIndex()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 1 to be a valid callback, method 'stdClass->method' not found or invalid method name"
        );

        InvalidArgumentException::assertCallback(array(1 => new \stdClass(), 2 => 'method'), 'func', 1);
    }

    function testExceptionIfStringIsPassedAsList()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 4 to be array or instance of Traversable"
        );

        InvalidArgumentException::assertCollection('string', 'func', 4);
    }

    function testExceptionIfObjectIsPassedAsList()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            "func() expects parameter 2 to be array or instance of Traversable"
        );

        InvalidArgumentException::assertCollection(new \stdClass(), 'func', 2);
    }

    function testExceptionIfInvalidMethodName()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'Invalid method name. Expected string, got object'
        );
        InvalidArgumentException::assertMethodName(new \stdClass());
    }

    function testExceptionIfInvalidPropertyName()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'func() expects parameter 2 to be string, object given'
        );
        InvalidArgumentException::assertPropertyName(new \stdClass(), "func", 2);
    }
}
