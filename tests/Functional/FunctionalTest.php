<?php

namespace Functional\Tests;

use Functional\Functional;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testAllDefinedConstantsAreValidCallables()
    {
        $functionalClass = new \ReflectionClass(Functional::class);
        $functions = $functionalClass->getConstants();

        foreach ($functions as $function) {
            $this->assertInternalType('callable', $function);
        }
    }
}
