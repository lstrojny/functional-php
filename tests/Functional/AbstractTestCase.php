<?php
namespace Functional;

class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    protected function _expectArgumentError($msg)
    {
        if (extension_loaded('functional')) {
            $this->setExpectedException('PHPUnit_Framework_Error_Warning', $msg);
        } else {
            $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', $msg);
        }
    }
}
