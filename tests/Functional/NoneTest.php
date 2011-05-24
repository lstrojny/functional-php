<?php
namespace Functional;

use ArrayIterator;

class NoneTest extends AbstractTestCase
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
        $this->_expectArgumentError('Functional\none() expects parameter 1 to be array or instance of Traversable');
        none('invalidCollection', 'method');
    }

    function testPassNonCallable()
    {
        $this->_expectArgumentError("Functional\\none() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        none($this->goodArray, 'undefinedFunction');
    }

    function callback($value, $key, $collection)
    {
        Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
        return $value != 'value' && strlen($key) > 0;
    }
}
