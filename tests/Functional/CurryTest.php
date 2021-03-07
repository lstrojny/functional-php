<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\curry;

class CurryTest extends CurryNTest
{
    protected function getCurryiedCallable(callable $callback, array $params, bool $required): callable
    {
        return curry($callback, $required);
    }
}
