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
use Respect\Validation\Rules\AlwaysValid;

/**
 * @group  rule
 * @covers AlwaysValid
 */
class AlwaysValidTest extends TestCase
{
    public static function providerForValidAlwaysValid()
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
     * @dataProvider providerForValidAlwaysValid
     */
    public function testShouldValidateInputWhenItIsAValidAlwaysValid($input)
    {
        $rule = new AlwaysValid();

        static::assertTrue($rule->validate($input));
    }
}
