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
use Respect\Validation\Rules\AlwaysInvalid;

#[CoversClass(AlwaysInvalid::class)]
#[Group('rule')]
class AlwaysInvalidTest extends TestCase
{
    public static function providerForValidAlwaysInvalid(): array
    {
        return [
            [0],
            [1],
            ['string'],
            [true],
            [false],
            [null],
            [''],
            [[]],
            [['array_full']],
        ];
    }

    #[DataProvider('providerForValidAlwaysInvalid')]
    public function testShouldValidateInputWhenItIsAValidAlwaysInvalid($input): void
    {
        $rule = new AlwaysInvalid();

        static::assertFalse($rule->validate($input));
    }
}
