<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

final class Functional
{

    /**
     * @see \Function\ary
     */
    const ary = '\Functional\ary';

    /**
     * @see \Functional\average
     */
    const average = '\Functional\average';

    /**
     * @see \Functional\but_last
     */
    const but_last = '\Functional\but_last';

    /**
     * @see \Functional\capture
     */
    const capture = '\Functional\capture';

    /**
     * @see \Functional\compare_object_hash_on
     */
    const compare_object_hash_on = '\Functional\compare_object_hash_on';

    /**
     * @see \Functional\compare_on
     */
    const compare_on = '\Functional\compare_on';

    /**
     * @see \Functional\compose
     */
    const compose = '\Functional\compose';

    /**
     * @see \Functional\concat
     */
    const concat = '\Functional\concat';

    /**
     * @see \Functional\const_function
     */
    const const_function = '\Functional\const_function';

    /**
     * @see \Functional\contains
     */
    const contains = '\Functional\contains';

    /**
     * @see \Functional\converge
     */
    const converge = '\Functional\converge';

    /**
     * @see \Functional\curry
     */
    const curry = '\Functional\curry';

    /**
     * @see \Functional\curry_n
     */
    const curry_n = '\Functional\curry_n';

    /**
     * @see \Functional\difference
     */
    const difference = '\Functional\difference';

    /**
     * @see \Functional\drop_first
     */
    const drop_first = '\Functional\drop_first';

    /**
     * @see \Functional\drop_last
     */
    const drop_last = '\Functional\drop_last';

    /**
     * @see \Functional\each
     */
    const each = '\Functional\each';

    /**
     * @see \Functional\equal
     */
    const equal = '\Functional\equal';

    /**
     * @see \Functional\error_to_exception
     */
    const error_to_exception = '\Functional\error_to_exception';

    /**
     * @see \Functional\every
     */
    const every = '\Functional\every';

    /**
     * @see \Functional\false
     */
    const false = '\Functional\false';

    /**
     * @see \Functional\falsy
     */
    const falsy = '\Functional\falsy';

    /**
     * @see \Functional\filter
     */
    const filter = '\Functional\filter';

    /**
     * @see \Functional\first
     */
    const first = '\Functional\first';

    /**
     * @see \Functional\first_index_of
     */
    const first_index_of = '\Functional\first_index_of';

    /**
     * @see \Functional\flat_map
     */
    const flat_map = '\Functional\flat_map';

    /**
     * @see \Functional\flatten
     */
    const flatten = '\Functional\flatten';

    /**
     * @see \Functional\flip
     */
    const flip = '\Functional\flip';

    /**
     * @see \Functional\greater_than
     */
    const greater_than = '\Functional\greater_than';

    /**
     * @see \Functional\greater_than_or_equal
     */
    const greater_than_or_equal = '\Functional\greater_than_or_equal';

    /**
     * @see \Functional\group
     */
    const group = '\Functional\group';

    /**
     * @see \Functional\head
     */
    const head = '\Functional\head';

    /**
     * @see \Functional\id
     */
    const id = '\Functional\id';

    /**
     * @see \Functional\identical
     */
    const identical = '\Functional\identical';

    /**
     * @see \Functional\if_else
     */
    const if_else = '\Functional\if_else';

    /**
     * @see \Functional\indexes_of
     */
    const indexes_of = '\Functional\indexes_of';

    /**
     * @see \Functional\intersperse
     */
    const intersperse = '\Functional\intersperse';

    /**
     * @see \Functional\invoke
     */
    const invoke = '\Functional\invoke';

    /**
     * @see \Functional\invoke_first
     */
    const invoke_first = '\Functional\invoke_first';

    /**
     * @see \Functional\invoke_if
     */
    const invoke_if = '\Functional\invoke_if';

    /**
     * @see \Functional\invoke_last
     */
    const invoke_last = '\Functional\invoke_last';

    /**
     * @see \Functional\invoker
     */
    const invoker = '\Functional\invoker';

    /**
     * @see \Functional\last
     */
    const last = '\Functional\last';

    /**
     * @see \Functional\last_index_of
     */
    const last_index_of = '\Functional\last_index_of';

    /**
     * @see \Functional\less_than
     */
    const less_than = '\Functional\less_than';

    /**
     * @see \Functional\less_than_or_equal
     */
    const less_than_or_equal = '\Functional\less_than_or_equal';

    /**
     * @see \Functional\lexicographic_compare
     */
    const lexicographic_compare = '\Functional\lexicographic_compare';

    /**
     * @see \Functional\map
     */
    const map = '\Functional\map';

    /**
     * @see \Functional\matching
     * @deprecated
     */
    const match = '\Functional\match';

    /**
     * @see \Functional\matching
     */
    const matching = '\Functional\matching';

    /**
     * @see \Functional\maximum
     */
    const maximum = '\Functional\maximum';

    /**
     * @see \Functional\memoize
     */
    const memoize = '\Functional\memoize';

    /**
     * @see \Functional\minimum
     */
    const minimum = '\Functional\minimum';

    /**
     * @see \Functional\none
     */
    const none = '\Functional\none';

    /**
     * @see \Functional\noop
     */
    const noop = '\Functional\noop';

    /**
     * @see \Functional\not
     */
    const not = '\Functional\not';

    /**
     * @see \Functional\omit_keys
     */
    const omit_keys = '\Functional\omit_keys';

    /**
     * @see \Functional\partial_any
     */
    const partial_any = '\Functional\partial_any';

    /**
     * @see \Functional\…
     */
    const … = '\Functional\…';

    /**
     * @see \Functional\placeholder
     */
    const placeholder = '\Functional\placeholder';

    /**
     * @see \Functional\partial_left
     */
    const partial_left = '\Functional\partial_left';

    /**
     * @see \Functional\partial_method
     */
    const partial_method = '\Functional\partial_method';

    /**
     * @see \Functional\partial_right
     */
    const partial_right = '\Functional\partial_right';

    /**
     * @see \Functional\partition
     */
    const partition = '\Functional\partition';

    /**
     * @see \Functional\pick
     */
    const pick = '\Functional\pick';

    /**
     * @see \Functional\pluck
     */
    const pluck = '\Functional\pluck';

    /**
     * @see \Functional\poll
     */
    const poll = '\Functional\poll';

    /**
     * @see \Functional\product
     */
    const product = '\Functional\product';

    /**
     * @see \Functional\ratio
     */
    const ratio = '\Functional\ratio';

    /**
     * @see \Functional\reduce_left
     */
    const reduce_left = '\Functional\reduce_left';

    /**
     * @see \Functional\reduce_right
     */
    const reduce_right = '\Functional\reduce_right';

    /**
     * @see \Functional\reindex
     */
    const reindex = '\Functional\reindex';

    /**
     * @see \Functional\reject
     */
    const reject = '\Functional\reject';

    /**
     * @see \Functional\repeat
     */
    const repeat = '\Functional\repeat';

    /**
     * @see \Functional\retry
     */
    const retry = '\Functional\retry';

    /**
     * @see \Functional\select
     */
    const select = '\Functional\select';

    /**
     * @see \Functional\select_keys
     */
    const select_keys = '\Functional\select_keys';

    /**
     * @see \Functional\sequence_constant
     */
    const sequence_constant = '\Functional\sequence_constant';

    /**
     * @see \Functional\sequence_exponential
     */
    const sequence_exponential = '\Functional\sequence_exponential';

    /**
     * @see \Functional\sequence_linear
     */
    const sequence_linear = '\Functional\sequence_linear';

    /**
     * @see \Functional\some
     */
    const some = '\Functional\some';

    /**
     * @see \Functional\sort
     */
    const sort = '\Functional\sort';

    /**
     * @see \Functional\sum
     */
    const sum = '\Functional\sum';

    /**
     * @see \Functional\suppress_error
     */
    const suppress_error = '\Functional\suppress_error';

    /**
     * @see \Functional\tail
     */
    const tail = '\Functional\tail';

    /**
     * @see \Functional\tail_recursion
     */
    const tail_recursion = '\Functional\tail_recursion';

    /**
     * @see \Functional\take_left
     */
    const take = '\Functional\take_left';

    /**
     * @see \Functional\take_right
     */
    const take_right = '\Functional\take_right';

    /**
     * @see \Functional\tap
     */
    const tap = '\Functional\tap';

    /**
     * @see \Functional\true
     */
    const true = '\Functional\true';

    /**
     * @see \Functional\truthy
     */
    const truthy = '\Functional\truthy';

    /**
     * @see \Functional\unique
     */
    const unique = '\Functional\unique';

    /**
     * @see \Functional\value_to_key
     */
    const value_to_key = '\Functional\value_to_key';

    /**
     * @see \Functional\with
     */
    const with = '\Functional\with';

    /**
     * @see \Functional\zip
     */
    const zip = '\Functional\zip';

    /**
     * @see \Functional\zip_all
     */
    const zip_all = '\Functional\zip_all';

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
