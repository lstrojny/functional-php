<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use DomainException;
use Functional\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Functional as F;
use Traversable;

class AbstractTestCase extends TestCase
{
    /** @var array */
    protected $list;

    /** @var Traversable */
    protected $listIterator;

    /** @var array */
    protected $hash;

    /** @var Traversable */
    protected $hashIterator;

    private $functions = [];

    public function setUp()
    {
        $this->functions = F\flatten(
            (array) (
                func_num_args() > 0
               ? func_get_arg(0)
               : $this->getFunctionName()
            )
        );


        foreach ($this->functions as $function) {
            if (!function_exists($function)) {
                $this->markTestSkipped(
                    sprintf(
                        'Function "%s()" not implemented in %s version',
                        $function,
                        extension_loaded('functional') ? 'native C' : 'PHP userland'
                    )
                );
                break;
            }
        }
    }

    protected function expectArgumentError($message)
    {
        if (strpos($message, 'callable') !== false) {
            $expectedExceptionClass = version_compare('7.0', PHP_VERSION) < 1 ? 'TypeError' : 'PHPUnit_Framework_Error';
            $expectedMessage = defined('HHVM_VERSION')
                ? str_replace('must be callable', 'must be an instance of callable', $message)
                : $message;
            $this->expectException($expectedExceptionClass);
            $this->expectExceptionMessage($expectedMessage);
        } else {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage($message);
        }
    }

    public function exception()
    {
        if (func_num_args() < 3) {
            throw new DomainException('Callback exception');
        }

        $args = func_get_args();
        $this->assertGreaterThanOrEqual(3, count($args));
        throw new DomainException(sprintf('Callback exception: %s', $args[1]));
    }

    protected function sequenceToArray(Traversable $sequence, $limit)
    {
        $values = [];
        $sequence->rewind();
        for ($a = 0; $a < $limit; $a++) {
            $values[] = $sequence->current();
            $sequence->next();
        }

        return $values;
    }

    private function getFunctionName()
    {
        $testName = get_class($this);
        $namespaceSeperatorPosition = strrpos($testName, '\\') + 1;
        $testName = substr($testName, $namespaceSeperatorPosition);
        $function = strtolower(
            implode(
                '_',
                array_slice(
                    preg_split('/([A-Z][a-z]+)/', $testName, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY),
                    0,
                    -1
                )
            )
        );

        return 'Functional\\' . $function;
    }
}
