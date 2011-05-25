<?php
namespace Functional;

use ArrayIterator;

class MapTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value', 'value');
        $this->iterator = new ArrayIterator($this->array);
        $this->keyedArray = array('k1' => 'val1', 'k2' => 'val2');
        $this->keyedIterator = new ArrayIterator($this->keyedArray);
    }

    function test()
    {
        $fn = function(&$v, &$k, &$collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return $k . $v;
        };
        $this->assertSame(array('0value', '1value'), map($this->array, $fn));
        $this->assertSame(array('0value', '1value'), map($this->iterator, $fn));
        $this->assertSame(array('k1' => 'k1val1', 'k2' => 'k2val2'), map($this->keyedArray, $fn));
        $this->assertSame(array('k1' => 'k1val1', 'k2' => 'k2val2'), map($this->keyedIterator, $fn));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\map() expects parameter 1 to be array or instance of Traversable');
        map('invalidCollection', 'strlen');
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\map() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        map($this->array, 'undefinedFunction');
    }
}
