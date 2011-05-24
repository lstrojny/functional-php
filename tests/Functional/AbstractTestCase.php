<?php
namespace Functional;

class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $function = str_replace('Test', '', get_class($this));
        if (!function_exists($function)) {
            $this->markTestSkipped('Function "' . $function . '" does not exist');
        }
    }

    function expectArgumentError($msg)
    {
        if (extension_loaded('functional')) {
            $this->setExpectedException('PHPUnit_Framework_Error_Warning', $msg);
        } else {
            $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', $msg);
        }
    }
}
