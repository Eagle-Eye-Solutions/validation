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
use Respect\Validation\Exceptions\MultipleException;
use Respect\Validation\Rules\Multiple;

/**
 * @group  rule
 * @covers Multiple
 * @covers MultipleException
 */
class MultipleTest extends TestCase
{
    /**
     * @dataProvider providerForMultiple
     * @throws \Exception
     */
    public function testValidNumberMultipleOf($multipleOf, $input): void
    {
        $multiple = new Multiple($multipleOf);
        static::assertTrue($multiple->validate($input));
        static::assertTrue($multiple->assert($input));
        static::assertTrue($multiple->check($input));
    }

    /**
     * @dataProvider providerForNotMultiple
     * @throws \Exception
     */
    public function testNotMultipleShouldThrowMultipleException($multipleOf, $input): void
    {
        $this->expectException(MultipleException::class);
        $multiple = new Multiple($multipleOf);
        static::assertFalse($multiple->validate($input));
        static::assertFalse($multiple->assert($input));
    }

    public static function providerForMultiple(): array
    {
        return [
            [5, 20],
            [5, 5],
            [5, 0],
            [5, -500],
            [1, 0],
            [1, 1],
            [1, 2],
            [1, 3],
            [0, 0], // Only 0 is multiple of 0
        ];
    }

    public static function providerForNotMultiple(): array
    {
        return [
            [5, 11],
            [5, 3],
            [5, -1],
            [3, 4],
            [10, -8],
            [10, 57],
            [10, 21],
            [0, 1],
            [0, 2],
        ];
    }
}
