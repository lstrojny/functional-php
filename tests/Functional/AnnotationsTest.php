<?php

/**
 * @package   Functional-php
 * @author    Hugo Sales <hugo@fc.up.pt>
 * @copyright 2020 Hugo Sales
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ReflectionFunction;

use function Functional\group;
use function get_defined_functions;

class AnnotationsTest extends AbstractTestCase
{
    public static function getFunctions(): array
    {
        return group(
            \array_values(
                \array_filter(
                    \get_defined_functions()['user'],
                    static function (string $function): bool {
                        return \stripos($function, 'Functional\\') === 0;
                    }
                )
            ),
            'Functional\id'
        );
    }

    /** @dataProvider getFunctions */
    public function testNamedArgumentsNotSupportedInFunctions(string $function): void
    {
        $refl = new ReflectionFunction($function);
        self::assertStringContainsString(
            '@no-named-arguments',
            $refl->getDocComment(),
            \sprintf(
                'Expected function "%s()" to have annotation @no-named-arguments',
                $function
            )
        );
    }
}
