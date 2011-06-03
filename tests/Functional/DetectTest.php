<?php
namespace Functional;

use ArrayIterator;

class DetectTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('first', 'second', 'third');
        $this->iterator = new ArrayIterator($this->array);
        $this->badArray = array('foo', 'bar', 'baz');
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
            return $v == 'second' && $k == 1;
        };

        $this->assertSame('second', detect($this->array, $fn));
        $this->assertSame('second', detect($this->iterator, $fn));
        $this->assertNull(detect($this->badArray, $fn));
        $this->assertNull(detect($this->badIterator, $fn));
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\detect() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        detect($this->array, 'undefinedFunction');
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        detect($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInCollection()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        detect($this->iterator, array($this, 'exception'));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\detect() expects parameter 1 to be array or instance of Traversable');
        detect('invalidCollection', 'strlen');
    }
}
