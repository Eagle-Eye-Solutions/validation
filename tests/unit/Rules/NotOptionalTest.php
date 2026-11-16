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
use Respect\Validation\Rules\NotOptional;
use stdClass;

#[CoversClass(NotOptional::class)]
#[Group('rule')]
class NotOptionalTest extends TestCase
{
    #[DataProvider('providerForNotOptional')]
    public function testShouldValidateWhenNotOptional($input): void
    {
        $rule = new NotOptional();

        static::assertTrue($rule->validate($input));
    }

    #[DataProvider('providerForOptional')]
    public function testShouldNotValidateWhenOptional($input): void
    {
        $rule = new NotOptional();

        static::assertFalse($rule->validate($input));
    }

    public static function providerForNotOptional(): array
    {
        return [
            [[]],
            [' '],
            [0],
            ['0'],
            [0],
            ['0.0'],
            [false],
            [['']],
            [[' ']],
            [[0]],
            [['0']],
            [[false]],
            [[[''], [0]]],
            [new stdClass()],
        ];
    }

    public static function providerForOptional(): array
    {
        return [
            [null],
            [''],
        ];
    }
}
