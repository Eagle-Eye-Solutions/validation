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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\FalseValException;
use Respect\Validation\Rules\FalseVal;

#[CoversClass(FalseVal::class)]
#[CoversClass(FalseValException::class)]
#[Group('rule')]
class FalseValTest extends TestCase
{
    #[DataProvider('validFalseProvider')]
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

    #[DataProvider('invalidFalseProvider')]
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
