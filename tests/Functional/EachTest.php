<?php
namespace Functional;

use ArrayIterator;

class EachTest extends AbstractTestCase
{
    function setUp()
    {
        $this->cb = $this->getMockBuilder('cb')
                         ->setMethods(array('call'))
                         ->getMock();


        $this->array = array('value0', 'value1', 'value2', 'value3');
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array('k0' => 'value0', 'k1' => 'value1', 'k2' => 'value2');
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function testArray()
    {
        $this->prepareCallback($this->array);
        each($this->array, array($this->cb, 'call'));
    }

    function testIterator()
    {
        $this->prepareCallback($this->iterator);
        each($this->iterator, array($this->cb, 'call'));
    }

    function testHash()
    {
        $this->prepareCallback($this->hash);
        each($this->hash, array($this->cb, 'call'));
    }

    function testHashIterator()
    {
        $this->prepareCallback($this->hashIterator);
        each($this->hashIterator, array($this->cb, 'call'));
    }

    function prepareCallback($collection)
    {
        $i = 0;
        foreach ($collection as $key => $value) {
            $this->cb->expects($this->at($i++))->method('call')->with($value, $key, $collection);
        }
    }

    function testPassNonCallable()
    {
        $this->_expectArgumentError('to be a valid callback', 'Invalid callback');
        each($this->array, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->_expectArgumentError('argument is not an array or an instance of Traversable', 'Invalid collection');
        each('invalidCollection', 'strlen');
    }
}
