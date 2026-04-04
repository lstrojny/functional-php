<?php

/**
 * @package   Functional-php
 * @author    Hugo Sales <hugo@hsal.es>
 * @copyright 2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\cartesian_product;

class CartesianProductTest extends AbstractTestCase
{
    public function testCartesianProduct(): void
    {
        self::assertSame(['1-one', '1-two', '2-one', '2-two'], cartesian_product('-', [1, 2], ['one', 'two']));
        self::assertSame(['1-one', '1_one', '1-two', '1_two', '2-one', '2_one', '2-two', '2_two'], cartesian_product(['-', '_'], [1, 2], ['one', 'two']));
        self::assertSame(['1one', '1two', '2one', '2two'], cartesian_product('', [1, 2], ['one', 'two']));
        self::assertSame(['1-one-H', '1-one-He', '1-two-H', '1-two-He', '2-one-H', '2-one-He', '2-two-H', '2-two-He'], cartesian_product('-', [1, 2], ['one', 'two'], ['H', 'He']));
    }
}
