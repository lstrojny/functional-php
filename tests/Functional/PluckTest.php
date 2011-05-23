<?php
namespace Functional;

use ArrayIterator;

class PluckTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->propertyExistsEverywhereArray = array((object)array('property' => 1), (object)array('property' => 2));
        $this->propertyExistsEverywhereIterator = new ArrayIterator($this->propertyExistsEverywhereArray);
        $this->propertyExistsSomewhere = array((object)array('property' => 1), (object)array('otherProperty' => 2));
        $this->mixedList = array((object)array('property' => 1), array('key'  => 'value'));
        $this->keyedList = array('test' => (object)array('property' => 1), 'test2' => (object)array('property' => 2));
    }

    function testPluckPropertyThatExistsEverywhere()
    {
        $this->assertSame(array(1, 2), pluck($this->propertyExistsEverywhereArray, 'property'));
        $this->assertSame(array(1, 2), pluck($this->propertyExistsEverywhereIterator, 'property'));
    }

    function testPluckPropertyThatExistsSomewhere()
    {
        $this->assertSame(array(1, null), pluck($this->propertyExistsSomewhere, 'property'));
        $this->assertSame(array(null, 2), pluck($this->propertyExistsSomewhere, 'otherProperty'));
    }

    function testPluckPropertyFromMixedList()
    {
        $this->assertSame(array(1, null), pluck($this->mixedList, 'property'));
    }

    function testPluckProtectedProperty()
    {
        $this->assertSame(array(null, null), pluck(array($this, 'foo'), 'preserveGlobalState'));
    }

    function testPluckPropertyInKeyedList()
    {
        $this->assertSame(array('test' => 1, 'test2' => 2), pluck($this->keyedList, 'property'));
    }

    function testPassNoList()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid list');
        pluck('invalidList', 'property');
    }

    function testPassNoPropertyName()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Invalid property name');
        pluck($this->propertyExistsSomewhere, new \stdClass());
    }
}