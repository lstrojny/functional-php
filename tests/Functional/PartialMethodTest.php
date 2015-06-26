<?php
namespace Functional\Tests;

use function Functional\partial_method;

class PartialMethodTest extends AbstractPartialTestCase
{
    public function testWithNoArgs()
    {
        $method = partial_method('execute');
        $this->assertSame('default', $method($this));
    }

    public function testWithTwoArgs()
    {
        $method = partial_method('execute', ['hello', 'world']);
        $this->assertSame('hello world', $method($this));
    }

    public function testWithInvalidMethod()
    {
        $method = partial_method('undefinedMethod');
        $this->assertNull($method($this));
    }

    public function testWithInvalidMethodAndDefaultValue()
    {
        $method = partial_method('undefinedMethod', [], 'defaultValue');
        $this->assertSame('defaultValue', $method($this));
    }

    public function testWithNonObjectAndDefaultValue()
    {
        $method = partial_method('undefinedMethod', [], 'defaultValue');
        $this->assertSame('defaultValue', $method('non-object'));
    }

    public function testWithInvalidMethodName()
    {
        $this->expectArgumentError('Functional\partial_method() expects parameter 1 to be string, integer given');
        partial_method(1);
    }

    public function execute($arg1 = null, $arg2 = null)
    {
        return $arg1 ? $arg1 . ' ' . $arg2 : 'default';
    }
}
