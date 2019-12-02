<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

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

    public function testShouldHaveDefinedConstantsForAllFunctions()
    {
        $functions = get_defined_functions(true);
        $functionalFunctions = preg_grep('/functional\\\(?!tests)/', $functions['user']);
        $expectedFunctions = array_map(function ($function) {
            return str_replace('functional\\', '\\Functional\\', $function);
        }, $functionalFunctions);

        $functionalClass = new \ReflectionClass(Functional::class);
        $constants = $functionalClass->getConstants();

        foreach ($expectedFunctions as $function) {
            $this->assertContains($function, $constants);
        }
    }
}
