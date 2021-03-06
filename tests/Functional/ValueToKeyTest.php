<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayObject;
use Functional\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\Constraint\Constraint;
use stdClass;

use function Functional\ary;
use function Functional\filter;
use function Functional\pluck;
use function Functional\value_to_key;

use const NAN;
use const PHP_VERSION_ID;

class ValueToKeyTest extends AbstractTestCase
{
    public const OBJECT_REF_REGEX = '@^\[i:0;~%s:(?<hash>[^\[:]+)(:\d+)?(\[.*])?\]$@';

    public static function getSimpleTypeExpectations(): array
    {
        $binary = \random_bytes(10);

        return [
            'Nothing' => [[], '[]'],
            'NULL' => [[null], '[i:0;~N;]'],
            'null string' => [['null'], '[i:0;~s:4:"null";]'],
            'string' => [['string'], '[i:0;~s:6:"string";]'],
            'integers' => [[12, 123], '[i:0;~i:12;:i:1;~i:123;]'],
            'funky integers' => [[INF, NAN], '[i:0;~d:INF;:i:1;~d:NAN;]'],
            'integer & float' => [[12, 123.10], '[i:0;~i:12;:i:1;~d:123.1;]'],
            'array of string' => [[['foo', 'bar']], '[i:0;~[i:0;~s:3:"foo";:i:1;~s:3:"bar";]]'],
            'nested array of strings (1)' => [
                [['foo', 'bar', ['foo', 'bar']]],
                '[i:0;~[i:0;~s:3:"foo";:i:1;~s:3:"bar";:i:2;~[i:0;~s:3:"foo";:i:1;~s:3:"bar";]]]',
            ],
            'nested array of strings (variation)' => [
                [['foo', 'bar', ['foo', ['bar']]]],
                '[i:0;~[i:0;~s:3:"foo";:i:1;~s:3:"bar";:i:2;~[i:0;~s:3:"foo";:i:1;~[i:0;~s:3:"bar";]]]]',
            ],
            'multiple nested arrays of strings' => [
                [['foo', 'bar', ['foo', ['bar'], 'baz']]],
                '[i:0;~[i:0;~s:3:"foo";:i:1;~s:3:"bar";:i:2;~[i:0;~s:3:"foo";:i:1;~[i:0;~s:3:"bar";]:i:2;~s:3:"baz";]]]',
            ],
            'multiple nested arrays of strings (variation)' => [
                [['foo', 'bar', ['foo', ['bar', 'baz']]]],
                '[i:0;~[i:0;~s:3:"foo";:i:1;~s:3:"bar";:i:2;~[i:0;~s:3:"foo";:i:1;~[i:0;~s:3:"bar";:i:1;~s:3:"baz";]]]]',
            ],
            'hashes' => [[['foo' => 'bar']], '[i:0;~[s:3:"foo";~s:3:"bar";]]'],
            [[$binary], '[i:0;~s:10:"' . $binary . '";]'],
            [[new stdClass()], self::matchesRegularExpression(self::createObjectRefRegex('stdClass'))],
            [[new ArrayObject()], self::matchesRegularExpression(self::createObjectRefRegex('ArrayObject'))],
        ];
    }

    /** @dataProvider getSimpleTypeExpectations */
    public function testValueToRefOnSimpleTypes(array $input, $constraint): void
    {
        $ref = value_to_key(...$input);
        self::assertThat($ref, $constraint instanceof Constraint ? $constraint : self::identicalTo($constraint));

        $hash[$ref] = 'value';
        self::assertSame('value', $hash[$ref], 'Ref can be used as an array key');
    }

    public function testExpectationsAreNonIdentical(): void
    {
        $strings = filter(pluck(self::getSimpleTypeExpectations(), 1), ary('is_string', 1));
        while ($string = \array_pop($strings)) {
            foreach ($strings as $otherString) {
                if ($string === $otherString) {
                    self::fail($string);
                }
            }
        }
        self::assertTrue(true, 'All expectations are different');
    }

    public static function getErrorCases(): array
    {
        return [
            [\stream_context_create()],
            [[\stream_context_create()]],
            [['key' => \stream_context_create()]],
            [new ArrayObject(['key' => \stream_context_create()])],
        ];
    }

    /** @dataProvider getErrorCases */
    public function testResourcesAreForbidden($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        value_to_key($value);
    }

    public function testObjectReferencesWithStdClass(): void
    {
        $key1 = value_to_key(new stdClass());
        $key2 = value_to_key(new stdClass());
        self::assertNotSame($key1, $key2);

        self::assertSame(
            1,
            \preg_match(self::createObjectRefRegex('stdClass'), $key1, $key1Matches),
            'Can extract object hash from key1'
        );
        self::assertSame(
            1,
            \preg_match(self::createObjectRefRegex('stdClass'), $key2, $key2Matches),
            'Can extract object hash from key2'
        );

        if (PHP_VERSION_ID >= 70400) {
            self::assertSame($key1Matches['hash'], $key2Matches['hash'], 'Object hashes match');
            self::assertSame('[i:0;~stdClass:' . $key1Matches['hash'] . ':0]', $key1, 'Object versions do not match');
            self::assertSame('[i:0;~stdClass:' . $key1Matches['hash'] . ':1]', $key2, 'Object versions do not match');
        } else {
            self::assertNotSame($key1Matches['hash'], $key2Matches['hash'], 'Object hashes should not match');
        }
    }

    public function testObjectReferencesWithArrayObject(): void
    {
        $key1 = value_to_key(new ArrayObject());
        $key2 = value_to_key(new ArrayObject(['foo' => 'bar']));
        self::assertNotSame($key1, $key2);

        self::assertSame(
            1,
            \preg_match(self::createObjectRefRegex('ArrayObject'), $key1, $key1Matches),
            'Can extract object hash from key1'
        );
        self::assertSame(
            1,
            \preg_match(self::createObjectRefRegex('ArrayObject'), $key2, $key2Matches),
            'Can extract object hash from key2'
        );

        if (PHP_VERSION_ID >= 70400) {
            self::assertSame($key1Matches['hash'], $key2Matches['hash'], 'Object hashes match');
            self::assertSame('[i:0;~ArrayObject:' . $key1Matches['hash'] . ':2[]]', $key1, 'Object versions do not match');
            self::assertSame('[i:0;~ArrayObject:' . $key1Matches['hash'] . ':3[s:3:"foo";~s:3:"bar";]]', $key2, 'Object versions do not match');
        } else {
            self::assertNotSame($key1Matches['hash'], $key2Matches['hash'], 'Object hashes don’t match');
        }
    }

    private static function createObjectRefRegex(string $class = '.*'): string
    {
        return \sprintf(self::OBJECT_REF_REGEX, $class);
    }
}
