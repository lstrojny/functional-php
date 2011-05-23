<?php
namespace Functional;

use ArrayIterator;

class MapTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->array = array('value', 'value');
        $this->iterator = new ArrayIterator($this->array);
        $this->keyedArray = array('k1' => 'val1', 'k2' => 'val2');
        $this->keyedIterator = new ArrayIterator($this->keyedArray);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection);
            return $k . $v;
        };
        $this->assertSame(array('0value', '1value'), map($this->array, $fn));
        $this->assertSame(array('0value', '1value'), map($this->iterator, $fn));
        $this->assertSame(array('k1' => 'k1val1', 'k2' => 'k2val2'), map($this->keyedArray, $fn));
        $this->assertSame(array('k1' => 'k1val1', 'k2' => 'k2val2'), map($this->keyedIterator, $fn));
    }

    function testPassNoList()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid list');
        map('invalidList', 'method');
    }

    function testPassNonCallable()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid callback');
        map($this->array, new \stdClass());
    }
}