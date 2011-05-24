<?php
namespace Functional;

use ArrayIterator;

class DetectTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->array = array('first', 'second', 'third');
        $this->iterator = new ArrayIterator($this->array);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection);
            return $v == 'second' && $k == 1;
        };

        $this->assertSame('second', detect($this->array, $fn));
        $this->assertSame('second', detect($this->iterator, $fn));
    }

    function testPassNonCallable()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid callback');
        detect($this->array, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid collection');
        detect('invalidCollection', 'undefinedFunction');
    }
}
