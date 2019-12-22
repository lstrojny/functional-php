<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Sequences;

use Functional\Exceptions\InvalidArgumentException;
use Iterator;

/**
 * @psalm-external-mutation-free
 */
class ExponentialSequence implements Iterator
{
    /**
     * @psalm-readonly
     * @var integer
     */
    private $start;

    /**
     * @var integer
     * @psalm-readonly
     */
    private $percentage;

    /**
     * @var integer
     */
    private $value = 0;

    /**
     * @var integer
     */
    private $times = 0;

    public function __construct(int $start, int $percentage)
    {
        InvalidArgumentException::assertIntegerGreaterThanOrEqual($start, 1, __METHOD__, 1);
        InvalidArgumentException::assertIntegerGreaterThanOrEqual($percentage, 1, __METHOD__, 2);
        InvalidArgumentException::assertIntegerLessThanOrEqual($percentage, 100, __METHOD__, 2);

        $this->start = $start;
        $this->percentage = $percentage;
    }

    /**
     * @psalm-mutation-free
     */
    public function current(): int
    {
        return $this->value;
    }

    public function next()
    {
        $this->value = (int) \round(($this->start * (1 + $this->percentage / 100)) ** $this->times);
        $this->times++;
    }

    /**
     * @psalm-pure
     * @psalm-mutation-free
     */
    public function key()
    {
        return null;
    }

    /**
     * @psalm-pure
     * @psalm-mutation-free
     */
    public function valid(): bool
    {
        return true;
    }

    public function rewind()
    {
        $this->times = 1;
        $this->value = $this->start;
    }
}
