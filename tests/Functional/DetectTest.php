<?php
namespace Functional;

use ArrayIterator;

class DetectTest extends AbstractTestCase
{
    function setUp()
    {
        $this->array = array('first', 'second', 'third');
        $this->iterator = new ArrayIterator($this->array);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
            return $v == 'second' && $k == 1;
        };

        $this->assertSame('second', detect($this->array, $fn));
        $this->assertSame('second', detect($this->iterator, $fn));
    }

    function testPassNonCallable()
    {
        $this->_expectArgumentError("Functional\detect() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        detect($this->array, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->_expectArgumentError('Functional\detect() expects parameter 1 to be array or instance of Traversable');
        detect('invalidCollection', 'undefinedFunction');
    }
}
