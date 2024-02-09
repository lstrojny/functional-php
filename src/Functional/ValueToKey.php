<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;
use Traversable;
use WeakReference;
use function get_class;
use function gettype;

/**
 * @no-named-arguments
 */
function value_to_key(...$any)
{
    /** @var object[]|WeakReference[] $objectReferences */
    static $objectReferences = [];

    static $objectToRef = null;
    if (!$objectToRef) {
        $objectToRef = static function ($value) use (&$objectReferences) {
            $hash = spl_object_hash($value);
            /**
             * spl_object_hash() will return the same hash twice in a single request if an object goes out of scope
             * and is destructed.
             */
            /** @var int[] $collisions */
            static $collisions = [];

            if (isset($objectReferences[$hash])) {
                if ($objectReferences[$hash]->get() === null) {
                    $collisions[$hash] = ($collisions[$hash] ?? 0) + 1;
                    $objectReferences[$hash] = WeakReference::create($value);
                }
            } else {
                $objectReferences[$hash] = WeakReference::create($value);
            }

            $key = get_class($value) . ':' . $hash . ':' . ($collisions[$hash] ?? 0);

            return $key;
        };
    }

    static $valueToRef = null;
    if (!$valueToRef) {
        $valueToRef = static function ($value, $key = null) use (&$valueToRef, $objectToRef) {
            $type = gettype($value);
            if ($type === 'array') {
                $ref = '[' . implode(':', map($value, $valueToRef)) . ']';
            } elseif ($value instanceof Traversable) {
                $ref = $objectToRef($value) . '[' . implode(':', map($value, $valueToRef)) . ']';
            } elseif ($type === 'object') {
                $ref = $objectToRef($value);
            } elseif ($type === 'resource') {
                throw new InvalidArgumentException(
                    'Resource type cannot be used as part of a memoization key. Please pass a custom key instead'
                );
            } else {
                $ref = serialize($value);
            }

            return ($key !== null ? ($valueToRef($key) . '~') : '') . $ref;
        };
    }

    return $valueToRef($any);
}
