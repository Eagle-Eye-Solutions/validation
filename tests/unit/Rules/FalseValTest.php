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
use Respect\Validation\Rules\FalseVal;

/**
 * @group  rule
 * @covers FalseVal
 * @covers FalseValException
 */
class FalseValTest extends TestCase
{
    /**
     * @dataProvider validFalseProvider
     */
    public function testValidatePatternAccordingToTheDefinedLocale($input): void
    {
        $rule = new FalseVal();

        static::assertTrue($rule->validate($input));
    }

    public static function validFalseProvider(): array
    {
        return [
            [false],
            [0],
            ['0'],
            ['false'],
            ['off'],
            ['no'],
            ['FALSE'],
            ['OFF'],
            ['NO'],
            ['False'],
            ['Off'],
            ['No'],
        ];
    }

    /**
     * @dataProvider invalidFalseProvider
     */
    public function testNotValidatePatternAccordingToTheDefinedLocale($input): void
    {
        $rule = new FalseVal();

        static::assertFalse($rule->validate($input));
    }

    public static function invalidFalseProvider(): array
    {
        return [
            [true],
            [1],
            ['1'],
            [0.5],
            [2],
            ['true'],
            ['on'],
            ['yes'],
            ['anything'],
        ];
    }
}
