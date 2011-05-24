<?php
namespace Functional;

use ArrayIterator;

class AnyTest extends AbstractTestCase
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
        $this->_expectArgumentError("Functional\any() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        any($this->goodArray, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->_expectArgumentError('Functional\any() expects parameter 1 to be array or instance of Traversable');
        any('invalidCollection', 'undefinedFunction');
    }

    function callback($value, $key, $collection)
    {
        Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
        return $value == 'value' && $key === 0;
    }
}
