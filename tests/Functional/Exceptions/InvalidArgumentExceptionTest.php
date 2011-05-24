<?php
namespace Functional\Exceptions;

class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    function testCallbackExceptionWithUndefinedStaticMethod()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'Invalid callback stdClass::method()'
        );

        InvalidArgumentException::assertCallback(array('stdClass', 'method'));
    }

    function testCallbackExceptionWithUndefinedFunction()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'Invalid callback undefinedFunction'
        );

        InvalidArgumentException::assertCallback('undefinedFunction');
    }

    function testCallbackExceptionWithUndefinedMethod()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'Invalid callback stdClass->method()'
        );

        InvalidArgumentException::assertCallback(array(new \stdClass(), 'method'));
    }

    function testCallbackExceptionWithIncorrectArrayIndex()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'Invalid callback stdClass->method()'
        );

        InvalidArgumentException::assertCallback(array(1 => new \stdClass(), 2 => 'method'));
    }

    function testExceptionIfStringIsPassedAsList()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'Invalid collection. Expected Traversable or array, got string'
        );

        InvalidArgumentException::assertCollection('string');
    }

    function testExceptionIfObjectIsPassedAsList()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'Invalid collection. Expected Traversable or array, got stdClass'
        );

        InvalidArgumentException::assertCollection(new \stdClass());
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
            'Invalid property name. Expected string, got object'
        );
        InvalidArgumentException::assertPropertyName(new \stdClass());
    }
}
