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

use Respect\Validation\Rules\Fibonacci;

/**
 * @group  rule
 * @covers Fibonacci
 */
class FibonacciTest extends RuleTestCase
{
    public function providerForValidInput(): array
    {
        $rule = new Fibonacci();

        return [
            [$rule, 1],
            [$rule, 2],
            [$rule, 3],
            [$rule, 5],
            [$rule, 8.0],
            [$rule, '3'],
            [$rule, 21],
            [$rule, 21.0],
            [$rule, '21.0'],
            [$rule, 34],
            [$rule, '34'],
            [$rule, 1346269],
            [$rule, 10610209857723],
        ];
    }

    public function providerForInvalidInput(): array
    {
        $rule = new Fibonacci();

        return [
            [$rule, 0],
            [$rule, 1346268],
            [$rule, ''],
            [$rule, null],
            [$rule, 7],
            [$rule, -1],
            [$rule, 5.2],
            [$rule, '-1'],
            [$rule, 'a'],
            [$rule, ' '],
            [$rule, false],
            [$rule, true],
        ];
    }
}
