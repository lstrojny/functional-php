<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use stdClass;
use ArrayIterator;

class MathDataProvider
{
    public static function injectErrorCollection()
    {
        $args = [];
        foreach ([new stdClass(), stream_context_create(), [], "str"] as $v) {
            $arg = [2, $v, "1.5", true, null];
            $args[] = [$arg];
            $args[] = [new ArrayIterator($arg)];
        }
        return $args;
    }

    public static function injectBooleanValues()
    {
        return [
            [false, true, 1]
        ];
    }
}
