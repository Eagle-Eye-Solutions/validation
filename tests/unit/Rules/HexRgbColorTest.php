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
use Respect\Validation\Rules\HexRgbColor;

/**
 * @group  rule
 * @covers HexRgbColor
 * @covers HexRgbColorException
 */
class HexRgbColorTest extends TestCase
{
    /**
     * @dataProvider providerForValidHexRgbColor
     */
    public function testHexRgbColorValuesONLYShouldReturnTrue($validHexRgbColor): void
    {
        $validator = new HexRgbColor();

        static::assertTrue($validator->validate($validHexRgbColor));
    }

    /**
     * @dataProvider providerForInvalidHexRgbColor
     */
    public function testInvalidHexRgbColorValuesShouldReturnFalse($invalidHexRgbColor): void
    {
        $validator = new HexRgbColor();

        static::assertFalse($validator->validate($invalidHexRgbColor));
    }

    public static function providerForValidHexRgbColor(): array
    {
        return [
            ['#000'],
            ['#00000F'],
            ['#123'],
            ['#123456'],
            ['#FFFFFF'],
            ['123123'],
            ['FFFFFF'],
        ];
    }

    public static function providerForInvalidHexRgbColor(): array
    {
        return [
            ['#0'],
            ['#0000G0'],
            ['#0FG'],
            ['#1234'],
            ['#AAAAAA1'],
            ['#S'],
            ['1234'],
            ['foo'],
            [0x39F],
            [05],
            [1],
            [443],
            [[]],
            [new \stdClass()],
            [null],
        ];
    }
}
