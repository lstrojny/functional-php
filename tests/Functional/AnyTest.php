<?php
namespace Functional;

use ArrayIterator;

class AnyTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->goodArray = array('value', 'wrong');
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = array('wrong', 'wrong', 'wrong');
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    function test()
    {
        $this->assertTrue(any($this->goodArray, array($this, 'callback')));
        $this->assertTrue(any($this->goodIterator, array($this, 'callback')));
        $this->assertFalse(any($this->badArray, array($this, 'callback')));
        $this->assertFalse(any($this->badIterator, array($this, 'callback')));
    }

    function testPassNonCallable()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid callback');
        any($this->goodArray, 'undefinedFunction');
    }

    function testPassNoList()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid list');
        any('invalidList', 'undefinedFunction');
    }

    function callback($value, $key, $collection)
    {
        Exceptions\InvalidArgumentException::assertCollection($collection);
        return $value == 'value' && $key === 0;
    }
}