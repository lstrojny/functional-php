<?php

/**
 * @package   Functional-php
 * @author    Hugo Sales <hugo@hsal.es>
 * @copyright 2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;
use Traversable;

/**
 * @param string|array $separator
 * @param array ...$collections
 * @return array
 * @no-named-arguments
 */
function cartesian_product($separator, ...$collections)
{
    InvalidArgumentException::assertIntegerGreaterThanOrEqual(\count($collections), 2, __FUNCTION__, 2); // TODO not great

    $aggregation = [];
    $left = \array_shift($collections);
    $index = 2;
    InvalidArgumentException::assertCollection($left, __FUNCTION__, $index);
    while (true) {
        $right = \array_shift($collections);
        InvalidArgumentException::assertCollection($right, __FUNCTION__, $index + 1);
        $left_index = 0;
        foreach ($left as $l) {
            $right_index = 0;
            foreach ($right as $r) {
                InvalidArgumentException::assertStringable($l, __FUNCTION__, $index, $left_index);
                InvalidArgumentException::assertStringable($r, __FUNCTION__, $index + 1, $right_index);
                if (\is_string($separator)) {
                    $aggregation[] = "{$l}{$separator}{$r}";
                } else if (\is_array($separator)) {
                    foreach ($separator as $sep) {
                        $aggregation[] = "{$l}{$sep}{$r}";
                    }
                } else {
                    // TODO assert that $separator is string or array of strings
                }
                ++$right_index;
            }
            ++$left_index;
        }
        ++$index;
        if (empty($collections)) {
            break;
        } else {
            $left = $aggregation;
            $aggregation = [];
        }
    }

    return $aggregation;
}
