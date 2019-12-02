<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;

/**
 * Memoizes callbacks and returns their value instead of calling them
 *
 * @param callable|null $callback Callable closure or function. Pass null to reset memory
 * @param array $arguments Arguments
 * @param array|string $key Optional memoize key to override the auto calculated hash
 * @return mixed
 */
function memoize(callable $callback = null, $arguments = [], $key = null)
{
    static $storage = [];

    if ($callback === null) {
        $storage = [];

        return null;
    }

    if (\is_callable($arguments)) {
        $key = $arguments;
        $arguments = [];
    } else {
        InvalidArgumentException::assertCollection($arguments, __FUNCTION__, 2);
    }

    static $keyGenerator = null;
    if (!$keyGenerator) {
        $keyGenerator = function ($value) use (&$keyGenerator) {
            $type = \gettype($value);
            if ($type === 'array') {
                $key = \join(':', map($value, $keyGenerator));
            } elseif ($type === 'object') {
                $key = \get_class($value) . ':' . \spl_object_hash($value);
            } else {
                $key = (string) $value;
            }

            return $key;
        };
    }

    if ($key === null) {
        $key = $keyGenerator(\array_merge([$callback], $arguments));
    } elseif (\is_callable($key)) {
        $key = $keyGenerator($key());
    } else {
        $key = $keyGenerator($key);
    }

    if (!isset($storage[$key]) && !\array_key_exists($key, $storage)) {
        $storage[$key] = $callback(...$arguments);
    }

    return $storage[$key];
}
