<?php
namespace Functional;

use ArrayIterator;

class NoneTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->goodArray = array('value', 'value', 'value');
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = array('value', 'value', 'foo');
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    function test()
    {
        $this->assertTrue(none($this->goodArray, array($this, 'callback')));
        $this->assertTrue(none($this->goodIterator, array($this, 'callback')));
        $this->assertFalse(none($this->badArray, array($this, 'callback')));
        $this->assertFalse(none($this->badIterator, array($this, 'callback')));
    }

    function testPassNoCollection()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid collection');
        none('invalidCollection', 'method');
    }

    function testPassNonCallable()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid callback');
        none($this->goodArray, new \stdClass());
    }

    function callback($value, $key, $collection)
    {
        Exceptions\InvalidArgumentException::assertCollection($collection);
        return $value != 'value' && strlen($key) > 0;
    }
}
