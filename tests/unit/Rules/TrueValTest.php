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
use Respect\Validation\Exceptions\TrueValException;
use Respect\Validation\Rules\TrueVal;

#[CoversClass(TrueVal::class)]
#[CoversClass(TrueValException::class)]
#[Group('rule')]
class TrueValTest extends TestCase
{
    #[DataProvider('validTrueProvider')]
    public function testShouldValidatePatternAccordingToTheDefinedLocale($input)
    {
        $rule = new TrueVal();

        static::assertTrue($rule->validate($input));
    }

    public static function validTrueProvider()
    {
        return [
            [true],
            [1],
            ['1'],
            ['true'],
            ['on'],
            ['yes'],
            ['TRUE'],
            ['ON'],
            ['YES'],
            ['True'],
            ['On'],
            ['Yes'],
        ];
    }

    #[DataProvider('invalidTrueProvider')]
    public function testShouldNotValidatePatternAccordingToTheDefinedLocale($input)
    {
        $rule = new TrueVal();

        static::assertFalse($rule->validate($input));
    }

    public static function invalidTrueProvider()
    {
        return [
            [false],
            [0],
            [0.5],
            [2],
            ['0'],
            ['false'],
            ['off'],
            ['no'],
            ['truth'],
        ];
    }
}
