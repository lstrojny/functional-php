<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;

trait IndexesTrait
{
    /** @before */
    public function createIndexTestData()
    {
        $this->list = ['value1', 'value', 'value', 'value2'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2', 'k3' => 'val1', 'k4' => 'val3'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }
}
