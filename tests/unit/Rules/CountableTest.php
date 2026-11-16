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
use Respect\Validation\Rules\Countable;

#[CoversClass(Countable::class)]
#[Group('rule')]
class CountableTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $rule = new Countable();

        return [
            [$rule, []],
            [$rule, new \ArrayObject()],
            [$rule, new \ArrayIterator()],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $rule = new Countable();

        return [
            [$rule, '1'],
            [$rule, 1.0],
            [$rule, new \stdClass()],
            [$rule, PHP_INT_MAX],
            [$rule, true],
        ];
    }
}
