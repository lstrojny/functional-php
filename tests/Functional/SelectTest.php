<?php
namespace Functional;

use ArrayIterator;

class SelectTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->array = array('value', 'wrong', 'value');
        $this->iterator = new ArrayIterator($this->array);
        $this->keyedArray = array('k1' => 'value', 'k2' => 'wrong', 'k3' => 'value');
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection);
            return $v == 'value' && strlen($k) > 0;
        };
        $this->assertSame(array('value', 2 => 'value'), select($this->array, $fn));
        $this->assertSame(array('value', 2 => 'value'), select($this->iterator, $fn));
        $this->assertSame(array('k1' => 'value', 'k3' => 'value'), select($this->keyedArray, $fn));
    }

    function testPassNonCallable()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid callback');
        select($this->array, 'undefinedFunction');
    }

    function testPassNoList()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid list');
        select('invalidList', 'undefinedFunction');
    }
}