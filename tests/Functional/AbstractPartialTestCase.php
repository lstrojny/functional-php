<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\ratio;

abstract class AbstractPartialTestCase extends AbstractTestCase
{
    protected function ratio()
    {
        return function ($initial, ...$args) {
            return ratio($args, $initial);
        };
    }
}
