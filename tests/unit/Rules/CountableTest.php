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

use Respect\Validation\Rules\Countable;

/**
 * @group  rule
 * @covers Countable
 */
class CountableTest extends RuleTestCase
{
    public function providerForValidInput(): array
    {
        $rule = new Countable();

        return [
            [$rule, []],
            [$rule, new \ArrayObject()],
            [$rule, new \ArrayIterator()],
        ];
    }

    public function providerForInvalidInput(): array
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
