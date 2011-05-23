<?php
namespace Functional;

use ArrayIterator;

class RejectTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->array = array('value', 'wrong', 'value');
        $this->iterator = new ArrayIterator($this->array);
        $this->keyedArray = array('k1' => 'value', 'k2' => 'wrong', 'k3' => 'value');
    }

    function test()
    {
        $fn = function($v, $k) {return $v == 'wrong' && strlen($k) > 0;};
        $this->assertSame(array(0 => 'value', 2 => 'value'), reject($this->array, $fn));
        $this->assertSame(array(0 => 'value', 2 => 'value'), reject($this->iterator, $fn));
        $this->assertSame(array('k1' => 'value', 'k3' => 'value'), reject($this->keyedArray, $fn));
    }

    function testPassNonCallable()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid callback');
        reject($this->array, 'undefinedFunction');
    }

    function testPassNoList()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid list');
        reject('invalidList', 'undefinedFunction');
    }
}