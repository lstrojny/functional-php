<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Sequences;

use Functional\Exceptions\InvalidArgumentException;
use Iterator;

/**
 * @internal
 *
 * @implements Iterator<null,positive-int>
 */
class ExponentialSequence implements Iterator
{
    /** @var positive-int */
    private $start;

    /** @var int<1,100> */
    private $percentage;

    /** @var positive-int */
    private $value;

    /** @var non-negative-int */
    private $times;

    /**
     * @param positive-int $start
     * @param int<1,100> $percentage
     */
    public function __construct($start, $percentage)
    {
        InvalidArgumentException::assertIntegerGreaterThanOrEqual($start, 1, __METHOD__, 1);
        InvalidArgumentException::assertIntegerGreaterThanOrEqual($percentage, 1, __METHOD__, 2);
        InvalidArgumentException::assertIntegerLessThanOrEqual($percentage, 100, __METHOD__, 2);

        $this->start = $start;
        $this->percentage = $percentage;
    }

    /** @return positive-int */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->value;
    }

    /** @return void */
    #[\ReturnTypeWillChange]
    public function next()
    {
        $this->value = (int) \round(\pow($this->start * (1 + $this->percentage / 100), $this->times));
        $this->times++;
    }

    /** @return null */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return null;
    }

    /** @return bool */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        return true;
    }

    /** @return void */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->times = 1;
        $this->value = $this->start;
    }
}
