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
class LinearSequence implements Iterator
{
    /**
     * @psalm-readonly
     * @var integer
     */
    private $start;

    /**
     * @psalm-readonly
     * @var integer
     */
    private $amount;

    /** @var integer */
    private $value = 0;

    public function __construct(int $start, int $amount)
    {
        InvalidArgumentException::assertIntegerGreaterThanOrEqual($start, 0, __METHOD__, 1);

        $this->start = $start;
        $this->amount = $amount;
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
        $this->value += $this->amount;
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
        $this->value = $this->start;
    }
}
