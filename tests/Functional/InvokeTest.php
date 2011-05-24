<?php
namespace Functional;

use ArrayIterator;

class InvokeTest extends AbstractTestCase
{
    function setUp()
    {
        $this->array = array($this, $this, $this);
        $this->iterator = new ArrayIterator($this->array);
        $this->keyArray = array('k1' => $this, 'k2' => $this);
    }

    function test()
    {
        $this->assertSame(array('methodValue', 'methodValue', 'methodValue'), invoke($this->array, 'method'));
        $this->assertSame(array('methodValue', 'methodValue', 'methodValue'), invoke($this->iterator, 'method'));
        $this->assertSame(array(null, null, null), invoke($this->array, 'undefinedMethod'));
        $this->assertSame(array(null, null, null), invoke($this->array, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        $this->assertSame(array(array(1, 2), array(1, 2), array(1, 2)), invoke($this->array, 'returnArguments', array(1, 2)));
        $this->assertSame(array('k1' => 'methodValue', 'k2' => 'methodValue'), invoke($this->keyArray, 'method'));
    }

    function testPassNoCollection()
    {
        $this->_expectArgumentError('Functional\invoke() expects parameter 1 to be array or instance of Traversable');
        invoke('invalidCollection', 'method');
    }

    function testPassNoPropertyName()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid method name');
        invoke($this->array, new \stdClass());
    }

    function method()
    {
        return 'methodValue';
    }

    function returnArguments()
    {
        return func_get_args();
    }
}
