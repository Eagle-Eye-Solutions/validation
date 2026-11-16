<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Test\Rules\Locale;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Respect\Validation\Rules\Locale\PlIdentityCard;
use Respect\Validation\Test\Rules\RuleTestCase;

#[CoversClass(PlIdentityCard::class)]
#[Group('rule')]
class PlIdentityCardTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $rule = new PlIdentityCard();

        return [
            [$rule, 'APH505567'],
            [$rule, 'AYE205410'],
            [$rule, 'AYW036733'],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $rule = new PlIdentityCard();

        return [
            [$rule, 'AAAAAAAAA'],
            [$rule, 'APH 505567'],
            [$rule, 'AYE205411'],
            [$rule, 'AYW036731'],
        ];
    }
}
