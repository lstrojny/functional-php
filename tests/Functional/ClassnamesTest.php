<?php

/**
 * @package   Functional-php
 * @author    Hugo Sales <hugo@fc.up.pt>
 * @copyright 2020 Hugo Sales
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\classnames;

class ClassnamesTest extends AbstractTestCase 
{
    public function testValue(): void
    {

        $is_first = '';
        $child_num = 5;

        $permanent_class = 'permanent';

        $conditional_classes = array(
            'is-hide'   => true,
            'is-gray'   => false,
            'first-child'   => $is_first,
            'child-'.$child_num   => $child_num,
        );


        self::assertSame('permanent is-hide child-5', classnames($conditional_classes, $permanent_class));
        self::assertSame('is-hide child-5', classnames($conditional_classes));
        self::assertSame('', classnames());
    }
}