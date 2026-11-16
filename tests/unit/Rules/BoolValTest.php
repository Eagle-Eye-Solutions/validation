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
use Respect\Validation\Rules\BoolVal;

#[CoversClass(BoolVal::class)]
#[Group('rule')]
class BoolValTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $rule = new BoolVal();

        return [
            [$rule, true],
            [$rule, 1],
            [$rule, 'on'],
            [$rule, 'yes'],
            [$rule, 0],
            [$rule, false],
            [$rule, 'off'],
            [$rule, 'no '],
            [$rule, ''],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $rule = new BoolVal();

        return [
            [$rule, 'ok'],
            [$rule, 'yep'],
            [$rule, 10],
        ];
    }
}
