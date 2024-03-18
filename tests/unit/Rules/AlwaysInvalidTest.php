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
use Respect\Validation\Rules\AlwaysInvalid;

/**
 * @group  rule
 * @covers AlwaysInvalid
 */
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

    /**
     * @dataProvider providerForValidAlwaysInvalid
     */
    public function testShouldValidateInputWhenItIsAValidAlwaysInvalid($input): void
    {
        $rule = new AlwaysInvalid();

        static::assertFalse($rule->validate($input));
    }
}
