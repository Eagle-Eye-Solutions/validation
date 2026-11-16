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
use Respect\Validation\Rules\LanguageCode;

#[CoversClass(LanguageCode::class)]
#[Group('rule')]
class LanguageCodeTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $ruleAlpha2 = new LanguageCode();
        $ruleAlpha3 = new LanguageCode('alpha-3');

        return [
            [$ruleAlpha2, 'pt'],
            [$ruleAlpha3, 'por'],
            [$ruleAlpha2, 'en'],
            [$ruleAlpha3, 'eng'],
            [$ruleAlpha2, 'it'],
            [$ruleAlpha3, 'ita'],
            [$ruleAlpha2, 'la'],
            [$ruleAlpha3, 'lat'],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $ruleAlpha2 = new LanguageCode();
        $ruleAlpha3 = new LanguageCode('alpha-3');

        return [
            [$ruleAlpha2, 'por'],
            [$ruleAlpha2, ''],
            [$ruleAlpha2, null],
            [$ruleAlpha2, false],
            [$ruleAlpha2, []],
            [$ruleAlpha3, 'pt'],
        ];
    }
}
