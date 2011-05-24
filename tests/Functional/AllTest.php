<?php
namespace Functional;

use ArrayIterator;

class AllTest extends AbstractTestCase
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
        $this->_expectArgumentError("Functional\all() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        all($this->goodArray, 'undefinedFunction');
    }

    function testPassNoCollection()
    {
        $this->_expectArgumentError('Functional\all() expects parameter 1 to be array or instance of Traversable');
        all('invalidCollection', 'undefinedFunction');
    }

    function callback($value, $key, $collection)
    {
        Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);

        return $value == 'value' && is_numeric($key);
    }
}
