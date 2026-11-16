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

use PHPUnit\Framework\Attributes\Group;
use Respect\Validation\Rules\ArrayType;

#[Group('rule')]
class ArrayTypeTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $rule = new ArrayType();

        return [
            [$rule, []],

            [$rule, [1, 2, 3]],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $rule = new ArrayType();

        return [
            [$rule, 'test'],
            [$rule, 1],
            [$rule, 1.0],
            [$rule, true],
            [$rule, new \ArrayObject()],
            [$rule, new \ArrayIterator()],
        ];
    }
}
