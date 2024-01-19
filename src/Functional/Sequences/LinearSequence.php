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
 * @implements Iterator<0,int>
 */
class LinearSequence implements Iterator
{
    /** @var non-negative-int */
    private $start;

    /** @var int */
    private $amount;

    /** @var int */
    private $value;

    /**
     * @param non-negative-int $start
     * @param int $amount
     */
    public function __construct($start, $amount)
    {
        InvalidArgumentException::assertIntegerGreaterThanOrEqual($start, 0, __METHOD__, 1);
        InvalidArgumentException::assertInteger($amount, __METHOD__, 2);

        $this->start = $start;
        $this->amount = $amount;
    }

    /** @return int */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->value;
    }

    /** @return void */
    #[\ReturnTypeWillChange]
    public function next()
    {
        $this->value += $this->amount;
    }

    /** @return int */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return 0;
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
        $this->value = $this->start;
    }
}
