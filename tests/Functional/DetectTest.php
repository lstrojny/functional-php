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
        $fn = function($v, $k) {
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

    function testPassNoList()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid list');
        detect('invalidList', 'undefinedFunction');
    }
}