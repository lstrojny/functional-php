<?php
namespace Functional;

use ArrayIterator;

class SelectTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value', 'wrong', 'value');
        $this->iterator = new ArrayIterator($this->array);
        $this->keyedArray = array('k1' => 'value', 'k2' => 'wrong', 'k3' => 'value');
        $this->keyedIterator = new ArrayIterator($this->keyedArray); 
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return $v == 'value' && strlen($k) > 0;
        };
        $this->assertSame(array('value', 2 => 'value'), select($this->array, $fn));
        $this->assertSame(array('value', 2 => 'value'), select($this->iterator, $fn));
        $this->assertSame(array('k1' => 'value', 'k3' => 'value'), select($this->keyedArray, $fn));
        $this->assertSame(array('k1' => 'value', 'k3' => 'value'), select($this->keyedIterator, $fn));
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\select() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        select($this->array, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\select() expects parameter 1 to be array or instance of Traversable');
        select('invalidCollection', 'strlen');
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        select($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInKeyedArray()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        select($this->keyedArray, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        select($this->iterator, array($this, 'exception'));
    }

    function testExceptionIsThrownInKeyedIterator()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        select($this->keyedIterator, array($this, 'exception'));
    }
}
