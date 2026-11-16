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
use Respect\Validation\Rules\CurrencyCode;

#[CoversClass(CurrencyCode::class)]
#[Group('rule')]
class CurrencyCodeTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $rule = new CurrencyCode();

        return [
            [$rule, 'EUR'],
            [$rule, 'GBP'],
            [$rule, 'XAU'],
            [$rule, 'xba'],
            [$rule, 'xxx'],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $rule = new CurrencyCode();

        return [
            [$rule, 'BTC'],
            [$rule, 'GGP'],
            [$rule, 'USA'],
        ];
    }
}
