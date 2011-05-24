<?php
namespace Functional;

class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    protected function _expectArgumentError($nativeMessage, $userspaceMessage)
    {
        if (extension_loaded('functional')) {
            $this->setExpectedException('PHPUnit_Framework_Error_Warning', $nativeMessage);
        } else {
            $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', $userspaceMessage);
        }
    }
}
