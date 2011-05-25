<?php
namespace Functional;

use ArrayIterator;

class EachTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
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
        $this->assertNull(each($this->array, array($this->cb, 'call')));
    }

    function testIterator()
    {
        $this->prepareCallback($this->iterator);
        $this->assertNull(each($this->iterator, array($this->cb, 'call')));
    }

    function testHash()
    {
        $this->prepareCallback($this->hash);
        $this->assertNull(each($this->hash, array($this->cb, 'call')));
    }

    function testHashIterator()
    {
        $this->prepareCallback($this->hashIterator);
        $this->assertNull(each($this->hashIterator, array($this->cb, 'call')));
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
        $this->expectArgumentError("Functional\each() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        each($this->array, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\each() expects parameter 1 to be array or instance of Traversable');
        each('invalidCollection', 'strlen');
    }
}
