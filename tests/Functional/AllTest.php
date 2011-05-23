<?php
namespace Functional;

use ArrayIterator;

class AllTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->goodArray = array('value', 'value', 'value');
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = array('value', 'nope', 'value');
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    function test()
    {
        $this->assertTrue(all($this->goodArray, array($this, 'callback')));
        $this->assertTrue(all($this->goodIterator, array($this, 'callback')));
        $this->assertFalse(all($this->badArray, array($this, 'callback')));
        $this->assertFalse(all($this->badIterator, array($this, 'callback')));
    }

    function testPassNonCallable()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid callback');
        all($this->goodArray, 'undefinedFunction');
    }

    function testPassNoList()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid list');
        all('invalidList', 'undefinedFunction');
    }

    function callback($value, $key, $collection)
    {
        Exceptions\InvalidArgumentException::assertCollection($collection);

        return $value == 'value' && is_numeric($key);
    }
}
