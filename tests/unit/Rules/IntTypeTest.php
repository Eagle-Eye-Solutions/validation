<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Test\Rules;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Rules\IntType;

/**
 * @group  rule
 * @covers IntType
 */
class IntTypeTest extends TestCase
{
    public static function providerForValidIntType(): array
    {
        return [
            [0],
            [123456],
            [PHP_INT_MAX],
            [PHP_INT_MAX * -1],
        ];
    }

    /**
     * @dataProvider providerForValidIntType
     */
    public function testShouldValidateInputWhenItIsAValidIntType($input): void
    {
        $rule = new IntType();

        static::assertTrue($rule->validate($input));
    }

    public static function providerForInvalidIntType(): array
    {
        return [
            ['1'],
            [1.0],
            [PHP_INT_MAX + 1],
            [true],
        ];
    }

    /**
     * @dataProvider providerForInvalidIntType
     */
    public function testShouldInvalidateInputWhenItIsNotAValidIntType($input): void
    {
        $rule = new IntType();

        static::assertFalse($rule->validate($input));
    }
}
