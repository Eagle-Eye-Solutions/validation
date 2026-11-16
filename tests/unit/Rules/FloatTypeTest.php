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
use PHPUnit\Framework\Attributes\Group;
use Respect\Validation\Rules\FloatType;

#[CoversClass(FloatType::class)]
#[Group('rule')]
class FloatTypeTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $rule = new FloatType();

        return [
            [$rule, 165.23],
            [$rule, 1.3e3],
            [$rule, 7E-10],
            [$rule, 0.0],
            [$rule, -2.44],
            [$rule, 10 / 33.33],
            [$rule, PHP_INT_MAX + 1],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $rule = new FloatType();

        return [
            [$rule, '1'],
            [$rule, '1.0'],
            [$rule, '7E-10'],
            [$rule, 111111],
            [$rule, PHP_INT_MAX * -1],
        ];
    }
}
