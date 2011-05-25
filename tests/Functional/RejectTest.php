<?php
namespace Functional;

use ArrayIterator;

class RejectTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value', 'wrong', 'value');
        $this->iterator = new ArrayIterator($this->array);
        $this->keyedArray = array('k1' => 'value', 'k2' => 'wrong', 'k3' => 'value');
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return $v == 'wrong' && strlen($k) > 0;
        };
        $this->assertSame(array(0 => 'value', 2 => 'value'), reject($this->array, $fn));
        $this->assertSame(array(0 => 'value', 2 => 'value'), reject($this->iterator, $fn));
        $this->assertSame(array('k1' => 'value', 'k3' => 'value'), reject($this->keyedArray, $fn));
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\\reject() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        reject($this->array, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\reject() expects parameter 1 to be array or instance of Traversable');
        reject('invalidCollection', 'strlen');
    }
}
